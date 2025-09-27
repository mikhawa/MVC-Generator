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

require_once RACINE_PATH."/src/Controller/homeController.php";

$connectPDO = null;