<?php

require_once("GenericView.php");

class ViewTfIdf extends GenericView
{

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