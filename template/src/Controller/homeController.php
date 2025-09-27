<?php
// src/Controller/homeController.php
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