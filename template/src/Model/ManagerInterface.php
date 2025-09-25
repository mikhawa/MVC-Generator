<?php
// src/Model/ManagerInterface.php

namespace Model;

use PDO;

interface ManagerInterface
{
    public function __construct(PDO $connect);
}