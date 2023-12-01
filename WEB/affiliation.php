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



if (isset($_GET['authorName'])) {
    $authorName = $_GET['authorName'];

    // Échappez les caractères spéciaux et effectuez votre recherche ici
    //$sql = "SELECT * FROM dblp.authors WHERE dblp.authors.name ILIKE :authorName";

    $sql = "SELECT dblp.affiliation.name AS name, dblp.affiliation.country AS country,
    COUNT(dblp.publications.id) AS nbpublications,
    STRING_AGG(DISTINCT dblp.publications.title, ', ') AS publicationlist,
    STRING_AGG(DISTINCT dblp.authors.name, ', ') AS affiliatedauthors
    FROM dblp.affiliation
    JOIN dblp.authors ON dblp.affiliation.idAff = dblp.authors.affiliation
    JOIN dblp.publicationauthors ON dblp.authors.id = publicationauthors.author_id
    JOIN dblp.publications ON dblp.publicationauthors.publication_id = dblp.publications.id
    WHERE dblp.affiliation.name ILIKE :authorName
    GROUP BY dblp.affiliation.idAff, dblp.affiliation.name, dblp.affiliation.country
    ORDER BY dblp.affiliation.idAff";


/*$sql = "SELECT dblp.affiliation.name 
        FROM dblp.affiliation 
        WHERE dblp.affiliation.name ILIKE :authorName";*/


    $stmt = $conn->prepare($sql);
    $stmt->execute(['authorName' => '%' . $authorName . '%']);
    
    $results = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $row;
    }
    echo json_encode($results);
} else {
    echo json_encode(array('error' => 'Aucun résultat trouvé.'));
}
?>