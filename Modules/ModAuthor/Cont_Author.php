<?php

require_once('Model_Author.php');
require_once('View_Author.php');

class ContAuthor
{
    private $view;
    private $model;
    private $action;

    //Constructeur
    public function __construct() 
    {
        $this->view = new ViewAuthor();
        $this->model = new ModelAuthor();
        $this->action = isset($_GET['action']) ? $_GET['action'] : "author";
    }

    public function getAction() {
        return $this->action;
    }

    public function exec() {
        $this->view->view();
    }
}
