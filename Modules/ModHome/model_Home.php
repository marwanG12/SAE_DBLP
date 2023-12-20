<?php

require_once('pdoConnection.php');

class ModelHome extends PDOConnection
{
    //Constructeur
    public function __construct()
    {

    }

    public function getAuthor($authorName){

        $stmt = parent::$db->prepare("SELECT dblp.authors.name, 
        dblp.affiliation.name AS affiliation, 
        COUNT(dblp.publicationdetails.publication_id) AS nbpublications,
        STRING_AGG(DISTINCT dblp.publications.title, ', ') AS publicationname
        FROM dblp.authors
        JOIN dblp.affiliation ON dblp.authors.affiliation = dblp.affiliation.idAff
        JOIN dblp.publicationauthors ON dblp.authors.id = dblp.publicationauthors.author_id
        JOIN dblp.publicationdetails ON dblp.publicationauthors.publication_id = dblp.publicationdetails.publication_id
        JOIN dblp.publications ON dblp.publicationdetails.publication_id = dblp.publications.id
        WHERE dblp.authors.name ILIKE :authorName
        GROUP BY dblp.authors.name, dblp.affiliation.name, dblp.affiliation.country
        ");

        $authorName = '%' . $authorName . '%';
        $stmt->bindParam(':authorName', $authorName);

        $stmt->execute();

        $results = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }
        return $results;
    }

    public function getAffiliation($authorName) {

        $stmt = parent::$db->prepare("SELECT dblp.affiliation.name AS name, dblp.affiliation.country AS country,
        COUNT(dblp.publications.id) AS nbpublications,
        STRING_AGG(DISTINCT dblp.publications.title, ', ') AS publicationlist,
        STRING_AGG(DISTINCT dblp.authors.name, ', ') AS affiliatedauthors
        FROM dblp.affiliation
        JOIN dblp.authors ON dblp.affiliation.idAff = dblp.authors.affiliation
        JOIN dblp.publicationauthors ON dblp.authors.id = publicationauthors.author_id
        JOIN dblp.publications ON dblp.publicationauthors.publication_id = dblp.publications.id
        WHERE dblp.affiliation.name ILIKE :authorName
        GROUP BY dblp.affiliation.idAff, dblp.affiliation.name, dblp.affiliation.country
        ORDER BY dblp.affiliation.idAff");

        $authorName = '%' . $authorName . '%';
        $stmt->bindParam(':authorName', $authorName);

        $stmt->execute();
        
        $results = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }
        return $results;

    } 

    public function getTfIdf($authorName){

    }

}