<?php

require_once('Model_TfIdf.php');
require_once('View_TfIdf.php');

class ContTfIdf
{
    private $view;
    private $model;
    private $action;

    //Constructeur
    public function __construct() 
    {
        $this->view = new ViewTfIdf();
        $this->model = new ModelTfIdf();
        $this->action = isset($_GET['action']) ? $_GET['action'] : "tfidf";
    }

    public function getAction() {
        return $this->action;
    }

    public function exec() {
        $this->view->view();
    }
}
