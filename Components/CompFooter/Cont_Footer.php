<?php

require_once("View_Footer.php");
require_once("Model_Footer.php");

class ContFooter
{

    private $view;
    private $model;

    //Constructeur
    public function __construct()
    {
        $this->view = new ViewFooter();
        $this->model = new ModelFooter();
    }

    /**
     * Function exec
     * Execute 2 functions on $this->view
     * 
     * Fonction exec
     * Execute 2 fonctions sur $this->view
     */

    public function exec()
    {
        $this->view->footer();
        $this->view->view();
    }
}