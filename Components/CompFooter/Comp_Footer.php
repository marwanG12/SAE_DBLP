<?php

require_once("Cont_Footer.php");

class CompFooter
{

    // Constructeur 
    public function __construct()
    {
        $controller = new ContFooter();
        $controller->exec();
    }
}