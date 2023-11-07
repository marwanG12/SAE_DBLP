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

// Ajoutez cette instruction de débogage pour vérifier les résultats du script Python
echo "Résultats du script Python : " . $results . "\n";

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
    $keywords = explode(', ', $query); // Divisez la chaîne en mots-clés

    // Échappez chaque mot-clé pour éviter les problèmes de sécurité
    $escapedKeywords = array_map(function ($keyword) {
        return escapeshellarg($keyword);
    }, $keywords);

    // Chemin vers le script Python et passage des mots-clés
    $pythonScript = "python C:\wamp64\www\SAE_DBLP\WEB\PYTHON\\tfidf.py " . implode(',', $escapedKeywords);

    // Exécution du script Python
    $result = shell_exec($pythonScript);

    return $result;
}
?>
