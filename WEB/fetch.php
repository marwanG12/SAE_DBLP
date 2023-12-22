<?php
    require_once("./connection.php");
    require_once("./components/PageRank.php");
    
    header("Content-Type: application/json");
    class Fetch extends Connection {
        private $pageRank;
        function __construct() {
            parent::__construct();
            $this->pageRank = new PageRank();
        }

        function getPageRank(){
            return $this->pageRank;
        }

        function fetchAuthors() {
            $sql = "SELECT * FROM authors";
            $sth = self::$con->prepare($sql);
            $sth->execute();
            $result = $sth->fetch();

            while ($row = $result->fetch_assoc()) {
                $authors[] = $row;
            }

            return $authors;
        }

        function fetchAuthor($id) {
            $sql = "SELECT 
            a.id, 
            a.name,
            GROUP_CONCAT(p.title SEPARATOR ', ') AS publication_titles,
            GROUP_CONCAT(p.id SEPARATOR ', ') AS publication_ids
        FROM 
            authors a
        JOIN 
            publicationauthors pa ON a.id = pa.author_id
        JOIN 
            publications p ON pa.publication_id = p.id
        WHERE 
            a.id = :id
        GROUP BY 
            a.id, a.name;
        
            ";
            $sth = self::$con->prepare($sql);
            $sth->execute(array(':id' => $id));
            $result = $sth->fetch();
            
            if(!$result) {
                return array("error" => "Author not found");
            } else {

                //Format des titres de publications
                return array(
                    "id" => $result["id"],
                    "name" => $result["name"],
                    "publications_id" => $result["publication_ids"],
                );
            }
            
        }
    }

    $fetch = new Fetch();

    $type = $_GET["type"] ?? "graph";
    $id = isset($_GET["id"]) ? intval($_GET["id"]) : null;
    $weight = isset($_GET["weight"]) ? intval($_GET["weight"]) : null;

    $name = isset($_GET["name"]) ? $_GET["name"] : null;

    switch ($type) {
        case "author":
            echo json_encode($fetch->fetchAuthor($id));
            break;
        case "graph":
            if($name != null) {
                $output = $fetch->getPageRank()->generateGraphForAuthor($name, $weight==1);
                echo json_encode($output, JSON_UNESCAPED_UNICODE );
            }           
            break;
        default:
            echo json_encode(array("error" => "Unknown type"));
            
            break;
    }
?>