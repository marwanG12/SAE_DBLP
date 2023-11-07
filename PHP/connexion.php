<?php

header("Access-Control-Allow-Origin: http://saedblpgit");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Informations de connexion à la base de données
/*$dbname = 'mghrairi';
$user = 'mghrairi';
$password = 'lenouveauMDP';
$host = 'database-etudiants.iut.univ-paris8.fr';
$port = '5432';*/

$dbname = 'njouini';
$user = 'njouini';
$password = 'Noufnouf-78800';
$host = 'database-etudiants.iut.univ-paris8.fr';
$port = '5432';

// Tentez de vous connecter à la base de données
try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");

    // Vérification de la connexion
    if ($conn) {
        echo "Connexion réussie à la base de données PostgreSQL.";
    }


} catch (PDOException $e) {
    die("La connexion à la base de données a échoué : " . $e->getMessage());
}


?>


























