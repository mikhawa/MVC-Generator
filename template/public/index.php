<?php
// public/index.php

# configuration
require_once '../config.php';

# autoload des classes PHP
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    // src est le dossier où se trouvent nos classes utilisateurs
    require RACINE_PATH.'/src/' .$class . '.php';
});

use Controller\Router;

# connexion à la base de données
try {
    $connectPDO = new PDO(
        DB_TYPE.':host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset='.DB_CHARSET,
        DB_LOGIN,
        DB_PWD,
        [
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        ]
    );
}catch(Exception $e){
    die($e->getMessage());
}

// Initialisation du routeur
$router = new Router('/MVC-Generator/template/public');

// Définition des routes
$router->get('/', function() {
    echo  "<h1>Page d'accueil</h1><p>Route avec closure!</p>";
});

$router->get('/home', 'HomeController@index');
$router->get('/about', 'HomeController@about');
$router->get('/user/{id}', 'HomeController@user');
$router->get('/product/{category}/{id}', 'HomeController@product');

$router->post('/contact', function() {
    return "<h1>Formulaire de contact</h1><p>Données reçues via POST</p>";
});

// Routes API
$router->get('/api/users', function() {
    header('Content-Type: application/json');
    return json_encode([
        'users' => [
            ['id' => 1, 'name' => 'Jean'],
            ['id' => 2, 'name' => 'Marie']
        ]
    ]);
});

// Résolution de la route
$router->resolve();

$connectPDO = null;