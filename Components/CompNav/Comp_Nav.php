<?php

require_once("cont_Nav.php");

class CompNavigation
{

    public function __construct()
    {
        $controller = new ContNavigation();
        $controller->exec();
    }
}