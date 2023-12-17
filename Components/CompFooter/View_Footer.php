<?php

require_once("GenericView.php");

class ViewFooter extends GenericView
{
    private $view;

    //Constructeur
    public function __construct()
    {
        parent::__construct();
    }

    public function footer()
    {
        $this->view = '<p> Footer </p>';
        
    }


    public function view()
    {
        echo $this->view;
    }
}
?>