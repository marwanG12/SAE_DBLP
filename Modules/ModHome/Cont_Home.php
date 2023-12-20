<?php

require_once('model_Home.php');
require_once('view_Home.php');

class ContHome
{
    private $view;
    private $model;
    private $action;

    public function __construct() 
    {
        $this->view = new ViewHome();
        $this->model = new ModelHome();
        $this->action = isset($_GET['action']) ? $_GET['action'] : "home";
    }

    public function getAction() {
        return $this->action;
    }

    public function author() {

        $this->view->searchBar();
        $this->view->displayResultsAuthor($this->model->getAuthor());

    }

    public function affiliation() {

        $this->view->searchBar();
        $this->view->displayResultsAffiliation($this->model->getAffiliation());
 
    }

    public function tfidf() {

        $this->view->searchBar();
        $this->view->displayResultsTfIdf($this->model->getTfIdf());
 
    }

    public function exec() {
        $this->view->view();
    }
}