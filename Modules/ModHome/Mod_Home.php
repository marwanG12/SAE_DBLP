<?php

require_once "cont_Home.php";
include_once "pdoConnection.php";

class ModHome extends PDOConnection
{

    private $controller;

    //Constructeur
    public function __construct()
    {
        $this->controller = new ContHome();

        switch ($this->controller->getAction()) 
        {
            case "author":
                $this->controller->author();
            break;
            case "affiliation":
                $this->controller->affiliation();
            break;
            case "tfidf":
                $this->controller->tfidf();
            break;
        }

        $this->controller->exec();
    }
}