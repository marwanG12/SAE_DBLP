<?php

require_once("view_Nav.php");
require_once("model_Nav.php");

class ContNavigation
{

    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new ViewNavigation();
        $this->model = new ModelNavigation();
    }

    public function exec()
    {
        $this->view->navigation();
        $this->view->view();
    }
}