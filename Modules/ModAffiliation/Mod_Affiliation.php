<?php

require_once "Cont_Affiliation.php";
include_once "PDOConnection.php";

class ModAffiliation extends PDOConnection
{

    private $controller;

    //Constructeur
    public function __construct()
    {
        $this->controller = new ContAffiliation();

        switch ($this->controller->getAction()) 
        {

        }

        $this->controller->exec();
    }
}
