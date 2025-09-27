<?php

namespace Controller;

class Route
{
    private $method;
    private $path;
    private $callback;
    private $parameters = [];

    public function __construct($method, $path, $callback)
    {
        $this->method = strtoupper($method);
        $this->path = $path;
        $this->callback = $callback;
    }

    /**
     * Vérifie si la route correspond à la requête
     */
    public function matches($method, $uri)
    {
        if ($this->method !== strtoupper($method)) {
            return false;
        }

        // Convertir la route en regex
        $pattern = $this->convertToRegex($this->path);

        if (preg_match($pattern, $uri, $matches)) {
            // Extraire les paramètres
            array_shift($matches); // Supprimer le match complet
            $this->parameters = $matches;
            return true;
        }

        return false;
    }

    /**
     * Convertit un pattern de route en regex
     */
    private function convertToRegex($path)
    {
        // Échapper les caractères spéciaux
        $path = preg_quote($path, '/');

        // Remplacer {param} par des groupes de capture
        $path = preg_replace('/\\\{([^}]+)\\\}/', '([^/]+)', $path);

        return '/^' . $path . '$/';
    }

    /**
     * Exécute le callback de la route
     */
    public function execute()
    {
        if (is_callable($this->callback)) {
            return call_user_func_array($this->callback, $this->parameters);
        }

        if (is_string($this->callback) && strpos($this->callback, '@') !== false) {
            return $this->executeControllerAction();
        }

        throw new Exception("Callback invalide pour la route");
    }

    /**
     * Exécute une action de contrôleur (format: "Controller@method")
     */
    private function executeControllerAction()
    {
        list($controller, $method) = explode('@', $this->callback);

        if (!class_exists($controller)) {
            throw new Exception("Contrôleur $controller introuvable");
        }

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            throw new Exception("Méthode $method introuvable dans $controller");
        }

        return call_user_func_array([$instance, $method], $this->parameters);
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}

/**
 * Classe Router - Gestionnaire principal des routes
 */
class Router
{
    private $routes = [];
    private $basePath = '';

    public function __construct($basePath = '')
    {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * Ajouter une route GET
     */
    public function get($path, $callback)
    {
        return $this->addRoute('GET', $path, $callback);
    }

    /**
     * Ajouter une route POST
     */
    public function post($path, $callback)
    {
        return $this->addRoute('POST', $path, $callback);
    }

    /**
     * Ajouter une route PUT
     */
    public function put($path, $callback)
    {
        return $this->addRoute('PUT', $path, $callback);
    }

    /**
     * Ajouter une route DELETE
     */
    public function delete($path, $callback)
    {
        return $this->addRoute('DELETE', $path, $callback);
    }

    /**
     * Ajouter une route pour toutes les méthodes
     */
    public function any($path, $callback)
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        foreach ($methods as $method) {
            $this->addRoute($method, $path, $callback);
        }
        return $this;
    }

    /**
     * Ajouter une route avec méthode spécifique
     */
    private function addRoute($method, $path, $callback)
    {
        $path = $this->basePath . $path;
        $route = new Route($method, $path, $callback);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Résoudre la route actuelle
     */
    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getCurrentUri();

        foreach ($this->routes as $route) {
            if ($route->matches($method, $uri)) {
                try {
                    return $route->execute();
                } catch (Exception $e) {
                    $this->handleError($e);
                    return;
                }
            }
        }

        // Aucune route trouvée - 404
        $this->handle404();
    }

    /**
     * Obtenir l'URI actuelle
     */
    private function getCurrentUri()
    {
        $uri = $_SERVER['REQUEST_URI'];

        // Supprimer les paramètres de requête
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        return $uri;
    }

    /**
     * Gérer les erreurs 404
     */
    private function handle404()
    {
        http_response_code(404);
        echo "<h1>404 - Page Non Trouvée</h1>";
        echo "<p>La page demandée n'existe pas.</p>";
    }

    /**
     * Gérer les erreurs
     */
    private function handleError(Exception $e)
    {
        http_response_code(500);
        echo "<h1>Erreur 500</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }

    /**
     * Définir un gestionnaire d'erreur 404 personnalisé
     */
    public function set404Handler($callback)
    {
        // Cette méthode pourrait être étendue pour personnaliser la gestion des 404
    }
}

