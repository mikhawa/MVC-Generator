<?php

// ============================================
// EXEMPLE D'UTILISATION
// ============================================
namespace Controller;
// Contrôleur d'exemple
class HomeController
{
    public function index()
    {
        return "<h1>Page d'accueil</h1><p>Bienvenue sur notre site!</p>";
    }

    public function about()
    {
        return "<h1>À propos</h1><p>Informations sur notre entreprise.</p>";
    }

    public function user($id)
    {
        return "<h1>Profil utilisateur</h1><p>ID: " . htmlspecialchars($id) . "</p>";
    }

    public function product($category, $id)
    {
        return "<h1>Produit</h1>" .
            "<p>Catégorie: " . htmlspecialchars($category) . "</p>" .
            "<p>ID: " . htmlspecialchars($id) . "</p>";
    }
}

// Initialisation du routeur
$router = new Router();

// Définition des routes
$router->get('/', function() {
    return "<h1>Page d'accueil</h1><p>Route avec closure!</p>";
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

// src/Controller/homeController.php
use Controller\Router;
use Model\__TestMapping;
use Model\__TestManager;

$__TestMapping1 = new __TestMapping([
    'id' => 1,
    'name' => 'Test Name',
    'description' => 'This is a test description.',
    'autretest' => 'valeur',
]);
$__TestManager1 = new __TestManager($connectPDO);

include_once RACINE_PATH.'/src/View/homepage.html.php';