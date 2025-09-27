<?php

namespace Model;


use PDO;

class __TestManager implements ManagerInterface
{

    protected PDO $connect;
    public function __construct(PDO $connect)
    {
        $this->connect = $connect;
    }
}