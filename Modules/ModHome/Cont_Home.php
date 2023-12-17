<?php

require_once('Model_Home.php');
require_once('View_Home.php');

class ContHome
{
    private $view;
    private $model;
    private $action;

    //Constructeur
    public function __construct() 
    {
        $this->view = new ViewHome();
        $this->model = new ModelHome();
        $this->action = isset($_GET['action']) ? $_GET['action'] : "home";
    }

    public function getAction() {
        return $this->action;
    }

    public function exec() {
        $this->view->view();
    }
}