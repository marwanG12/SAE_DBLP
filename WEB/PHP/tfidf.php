<?php
// Configuration de l'en-tête pour autoriser l'accès depuis le domaine distant (remplacez par votre propre domaine)
header("Access-Control-Allow-Origin: http://votre_domaine");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json");

// Récupérez la requête du paramètre GET
$query = $_GET['query'];

// Ajoutez cette instruction de débogage pour vérifier la requête textuelle reçue
echo "Requête textuelle : " . $query . "\n";

// Appelez la fonction searchByTextQuery avec la requête de l'utilisateur
$results = searchByTextQuery($query);


if (!empty($results)) {
    $tfidfData = json_decode($results, true);
    
    if (is_array($tfidfData)) {
        $words = array_keys($tfidfData); // Liste des mots de la requête

        // Connexion à la base de données (à personnaliser avec vos informations)
        $dbname = 'njouini';
        $user = 'njouini';
        $password = 'Noufnouf-78800';
        $host = 'database-etudiants.iut.univ-paris8.fr';
        $port = '5432';
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");

        // Construisez la clause WHERE en utilisant les mots de la requête
        $whereClause = 'word IN (' . implode(',', array_map(function ($word) {
            return "'" . $word . "'";
        }, $words)) . ')';

        // Construisez la requête SQL pour récupérer les publications (à personnaliser selon votre structure de base de données)
        $sql = "SELECT id, title FROM dblp.publications WHERE $whereClause";

        // Exécution de la requête SQL
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $publications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($publications)) {
            // Affichage des publications
            echo json_encode($publications);
        } else {
            echo json_encode(array("message" => "Aucune publication trouvée."));
        }
    } else {
        echo json_encode(array("message" => "Aucun résultat trouvé."));
    }
} else {
    echo json_encode(array("message" => "Aucun résultat trouvé."));
}

// Fonction pour exécuter le script Python et récupérer les données
function searchByTextQuery($query) {
    // Échappez la requête pour éviter les problèmes de sécurité
    $escapedQuery = escapeshellarg($query);

    // Chemin vers le script Python
    $pythonScript = "python C:\wamp64\www\SAE_DBLP\WEB\PYTHON\\tfidf.py $escapedQuery";

    // Exécutez le script Python
    $result = shell_exec($pythonScript);

    // Ajoutez ces lignes pour afficher les résultats dans la réponse HTTP
    echo "Résultat du script Python : " . $result; // Affichez les résultats dans la réponse HTTP
    return $result;
}



?>
