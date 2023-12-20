<?php
require_once("genericView.php");

class Controller
{

    private $view;
    private $module;

    public function __construct()
    {
        $this->view = new GenericView();
        $this->module = isset($_GET['module']) ? $_GET['module'] : "home";
    }

    public function navigation()
    {
        new CompNavigation();
    }

    public function exec()
    {
        switch ($this->module) {
            case 'home':
                new ModHome();
            break;
            default :
                die("Le module demandé n'existe pas.");
        }

    }
}
?>