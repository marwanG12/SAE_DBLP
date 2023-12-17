<?php

require_once "Cont_TfIdf.php";
include_once "PDOConnection.php";

class ModTfIdf extends PDOConnection
{

    private $controller;

    //Constructeur
    public function __construct()
    {
        $this->controller = new ContTfIdf();

        switch ($this->controller->getAction()) 
        {

        }

        $this->controller->exec();
    }
}