<?php

require_once "Cont_Author.php";
include_once "PDOConnection.php";

class ModAuthor extends PDOConnection
{

    private $controller;

    //Constructeur
    public function __construct()
    {
        $this->controller = new ContAuthor();

        switch ($this->controller->getAction()) 
        {

        }

        $this->controller->exec();
    }
}
