<?php 

    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    
    
    include_once("pdoConnection.php");
    include_once("controller.php");

    $view;
    PDOConnection::initPDO();

    $controller = new Controller();
    $controller->exec();
    

    include_once("Layout.php");

    
?>