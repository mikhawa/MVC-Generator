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
        // Échapper les caractères spéciaux sauf les {}
        $path = str_replace(['/', '.', '+', '*', '?', '^',], ['\/', '\.', '\+', '\*', '\?', '\^',], $path);
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

