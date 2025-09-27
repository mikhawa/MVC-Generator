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