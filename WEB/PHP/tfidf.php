<?php

header("Access-Control-Allow-Origin: http://s5cdblp");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$dbname = 'njouini';
$user = 'njouini';
$password = 'Noufnouf-78800';
$host = 'database-etudiants.iut.univ-paris8.fr';
$port = '5432';

// Tentez de vous connecter à la base de données  
$conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");

function searchByTextQuery($query) {
    // Connexion à la base de données (assurez-vous d'avoir déjà établi la connexion)

    // Prétraitement de la requête
    $query = strtolower($query); // Convertir la requête en minuscules

    // Calcul des poids TF-IDF pour la requête
    $queryWords = explode(" ", $query);
    $queryVector = array();
    foreach ($queryWords as $word) {
        $queryVector[$word] = calculateTFIDF($word, $query); // Remplacez cette fonction par le calcul TF-IDF réel
    }

    // Récupérer tous les documents de la base de données (vous pouvez utiliser une requête SQL pour cela)
    $documents = getAllDocuments(); // Remplacez cette fonction pour obtenir les documents

    // Calculer la similarité cosinus entre la requête et chaque document
    $results = array();
    foreach ($documents as $document) {
        // Calculer le produit scalaire entre le vecteur de requête et le vecteur du document
        $dotProduct = 0;
        foreach ($queryVector as $word => $tfidf) {
            if (isset($document['tfidf'][$word])) {
                $dotProduct += $tfidf * $document['tfidf'][$word];
            }
        }

        // Calculer la norme (longueur) des vecteurs
        $queryNorm = sqrt(array_sum(array_map(function ($x) { return $x * $x; }, $queryVector)));
        $documentNorm = sqrt(array_sum(array_map(function ($x) { return $x * $x; }, $document['tfidf'])));

        // Calculer la similarité cosinus
        if ($queryNorm > 0 && $documentNorm > 0) {
            $similarity = $dotProduct / ($queryNorm * $documentNorm);
            $results[] = array('document' => $document, 'similarity' => $similarity);
        }
    }

    // Trier les résultats par similarité cosinus décroissante
    usort($results, function ($a, $b) {
        return $b['similarity'] <=> $a['similarity'];
    });

    // Retourner les résultats triés
    return $results;
}

function calculateTFIDF($word, $query) {
    $dbname = 'njouini';
    $user = 'njouini';
    $password = 'Noufnouf-78800';
    $host = 'database-etudiants.iut.univ-paris8.fr';
    $port = '5432';

    // Tentez de vous connecter à la base de données  
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
    
    
}

function getAllDocuments(){
    $dbname = 'njouini';
    $user = 'njouini';
    $password = 'Noufnouf-78800';
    $host = 'database-etudiants.iut.univ-paris8.fr';
    $port = '5432';

    // Tentez de vous connecter à la base de données  
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");

    // Exécutez la requête SQL appropriée pour extraire les données des documents
    $query = "SELECT  mot, tfidf FROM insertTfIdf;"; // Personnalisez cette requête si nécessaire
    $result = pg_query($conn, $query);

    if (!$result) {
        die("La requête a échoué : " . pg_last_error());
    }

    // Initialisez un tableau pour stocker les documents
    $documents = array();

    while ($row = pg_fetch_assoc($result)) {
        // Ajoutez chaque document au tableau
        $document = array(
            'id' => $row['id'],
            'title' => $row['title']
            // Ajoutez d'autres colonnes si nécessaire
        );

        $documents[] = $document;
    }

    // Fermeture de la connexion à la base de données
    pg_close($conn);

    // Retournez la liste des documents
    return $documents;
}
?>