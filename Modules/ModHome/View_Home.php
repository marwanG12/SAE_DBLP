<?php

require_once("GenericView.php");

class ViewHome extends GenericView
{

    //Constructeur
    public function __construct()
    {
        parent::__construct();

    }

    public function showDefaultMessage() {

        echo '
        Sélectionnez la recherche que vous voulez faire
        ';


    }

}
?>