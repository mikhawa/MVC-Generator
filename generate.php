#!/usr/bin/env php
<?php

// Le script qui sera exécuté par le .phar

echo "🚀 Démarrage du générateur de projet MVC en PHP ...\n";

// 1. Définir les chemins
$source = __DIR__ . '/template';
// getcwd() est le dossier où l'utilisateur a lancé la commande (ex: '/home/user/nouveau-projet').
$destination = getcwd();

// 2. Parcourir récursivement le dossier templates DANS l'archive
$directoryIterator = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::SELF_FIRST);

// 3. Copier chaque dossier et fichier vers la destination
foreach ($iterator as $item) {
    // Chemin de la cible (ex: '/home/user/nouveau-projet/public/index.php')
    $targetPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();

    if ($item->isDir()) {
        // Si c'est un dossier, on le crée
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0755, true);
            echo "  Créé dossier : " . $iterator->getSubPathName() . "\n";
        }
    } else {
        // Si c'est un fichier, on le copie
        copy($item, $targetPath);
        echo "  Créé fichier : " . $iterator->getSubPathName() . "\n";
    }
}

echo "\n✅ L'arborescence de votre projet MVC a été créée avec succès !\n";
