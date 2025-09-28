<?php
// src/Controller/homeController.php
use Model\__TestMapping;
use Model\__TestManager;


// test d'un mapping
$__TestMapping1 = new __TestMapping([
    'id' => 1,
    'name' => "Test d'un nom complexe comme hôpital",
    'description' => 'This is a test description.',
    'autretest' => 'valeur',
]);

// test d'un manager
$__TestManager1 = new __TestManager($connectPDO);

// test de la méthode slugify du trait SlugifyTrait dans le manager
$nameSlugify = $__TestManager1->slugify($__TestMapping1->getName());

include_once RACINE_PATH.'/src/View/homepage.html.php';