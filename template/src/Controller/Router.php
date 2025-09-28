<?php
// src/Controller/Router.php
namespace Controller;
use Exception;
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

// Debug : afficher les informations de routing (à supprimer en production)
if (isset($_GET['debug'])) {
echo "<h3>Debug Router</h3>";
echo "<p><strong>Method:</strong> $method</p>";
echo "<p><strong>URI:</strong> $uri</p>";
echo "<p><strong>Base Path:</strong> {$this->basePath}</p>";
echo "<h4>Routes disponibles:</h4><ul>";
    foreach ($this->routes as $route) {
    $pattern = $this->getRoutePattern($route);
    echo "<li>$pattern</li>";
    }
    echo "</ul>";
}

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
* Obtenir le pattern d'une route pour le debug
*/
private function getRoutePattern($route)
{
$reflection = new ReflectionClass($route);
$methodProp = $reflection->getProperty('method');
$pathProp = $reflection->getProperty('path');
$methodProp->setAccessible(true);
$pathProp->setAccessible(true);

return $methodProp->getValue($route) . ' ' . $pathProp->getValue($route);
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

// Supprimer le basePath si défini
if (!empty($this->basePath)) {
$basePath = rtrim($this->basePath, '/');
if (strpos($uri, $basePath) === 0) {
$uri = substr($uri, strlen($basePath));
}
}

// S'assurer que l'URI commence par /
if (empty($uri) || $uri[0] !== '/') {
$uri = '/' . ltrim($uri, '/');
}

// Normaliser l'URI (supprimer les / en fin sauf pour la racine)
return $uri === '/' ? '/' : rtrim($uri, '/');
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