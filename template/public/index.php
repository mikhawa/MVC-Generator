<?php
// public/index.php

# configuration
require_once '../config.php';

# autoload des classes PHP
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require RACINE_PATH.'/' .$class . '.php';
});