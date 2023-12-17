<?php

require_once('Model_Affiliation.php');
require_once('View_Affiliation.php');

class ContAffiliation
{
    private $view;
    private $model;
    private $action;

    //Constructeur
    public function __construct() 
    {
        $this->view = new ViewAffiliation();
        $this->model = new ModelAffiliation();
        $this->action = isset($_GET['action']) ? $_GET['action'] : "affiliation";
    }

    public function getAction() {
        return $this->action;
    }

    public function exec() {
        $this->view->view();
    }
}
