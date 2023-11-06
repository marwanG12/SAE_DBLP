<?php


header("Access-Control-Allow-Origin: http://s5cdblp");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$dbname = 'mghrairi';
$user = 'mghrairi';
$password = 'lenouveauMDP';
$host = 'database-etudiants.iut.univ-paris8.fr';
$port = '5432';

// Tentez de vous connecter à la base de données  
$conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");



if (isset($_GET['authorName'])) {
    $authorName = $_GET['authorName'];

    // Échappez les caractères spéciaux et effectuez votre recherche ici
    //$sql = "SELECT * FROM dblp.authors WHERE dblp.authors.name ILIKE :authorName";

    $sql = "SELECT dblp.authors.name, dblp.affiliation.name AS affiliation, COUNT(dblp.publicationdetails.publication_id) AS nbpublications
    FROM dblp.authors
    JOIN dblp.affiliation ON dblp.authors.affiliation = dblp.affiliation.idAff
    JOIN dblp.publicationauthors ON dblp.authors.id = dblp.publicationauthors.author_id
    JOIN dblp.publicationdetails ON dblp.publicationauthors.publication_id = dblp.publicationdetails.publication_id
    WHERE dblp.authors.name ILIKE :authorName
    GROUP BY dblp.authors.name, dblp.affiliation.name, dblp.affiliation.country
    ";

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
