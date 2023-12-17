<?php

require_once "Cont_Home.php";
include_once "PDOConnection.php";

class ModHome extends PDOConnection
{

    private $controller;

    //Constructeur
    public function __construct()
    {
        $this->controller = new ContHome();

        switch ($this->controller->getAction()) 
        {

        }

        $this->controller->exec();
    }
}