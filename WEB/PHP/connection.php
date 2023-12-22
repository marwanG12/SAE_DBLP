<?php

class Connection {
    // Informations de connexion à la base de données
    protected static $con;
    function __construct() {
        $dbname = "dblp";
        $user = "root";
        $password = "";
        $host = "localhost";
        $port = "3306";
        
        // Tentez de vous connecter à la base de données
        try {
            self::$con = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
            // Vérification de la connexion
            
        } catch (PDOException $e) {
            die("La connexion à la base de données a échoué : " . $e->getMessage());
        }
    }

}


?>