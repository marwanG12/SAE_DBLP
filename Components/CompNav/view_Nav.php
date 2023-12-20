<?php

require_once("genericView.php");

class ViewNavigation extends GenericView
{
    private $view;

    public function __construct()
    {
        parent::__construct();
    }

    public function navigation()
    {
        $this->view = '<nav class="navigation">



        <a href="./" class="homeButton"> SAE DBLP </a>

        ';
    
        $this->view = $this->view . '

        <p> Bonjour </p>

        <div class="links">';

            $this->view = $this->view . '
            
            <a href="./?module=auth&action=logout"> Déconnexion </a>

        </div>

        </nav>';
        
    }

    public function view()
    {
        echo $this->view;
    }
}
?>