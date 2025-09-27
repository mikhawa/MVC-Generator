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



// src/Controller/homeController.php
