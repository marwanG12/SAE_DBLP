<?php
    // require_once ("./Graph.php");
    require_once(__DIR__."./TableManager.php");
    require_once("./connection.php");
    //Connect to xampp
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dblp";


    class PageRank extends Connection{
        private $tableManager;

        function __construct() {
            parent::__construct();
            $this->tableManager = new TableManager();
        }

        function saveAffiliationPageRank($pageRankTable, $withWeight=false) {
            $tableName = $withWeight ? "pagerank_affiliation_weight" : "pagerank_affiliation";
        
            $sql = "DROP TABLE $tableName";
            self::$con->query($sql);

            $sql = "CREATE TABLE $tableName (
                affiliation_id INT PRIMARY KEY,
                val FLOAT NOT NULL
            )";
            self::$con->query($sql);

            //On commence la transaction pour gagner en performance en évitant les vérifications pour chaque insertion

            self::$con->begin_transaction();
            foreach($pageRankTable as $idAff => $value) {
                
                $query = "INSERT INTO $tableName (affiliation_id, val) VALUES (".$idAff.",". $value.")";
                self::$con->query($query);
            }

            self::$con->commit();
            
            self::$con->query($query);
        }

        function savePageRank($withWeight=false) {

            $tableName = $withWeight ? "pagerank_author_weight" : "pagerank_author";

            $sql = "DROP TABLE $tableName";
            self::$con->query($sql);

            $sql = "CREATE TABLE $tableName (
                author_id INT PRIMARY KEY,
                val FLOAT NOT NULL
            )";
            self::$con->query($sql);
            
            //On commence la transaction pour gagner en performance en évitant les vérifications pour chaque insertion

            self::$con->begin_transaction();
            foreach($this->tableManager->getAllNodes() as $node) {
                
                $query = "INSERT INTO $tableName (author_id, val) VALUES (".$node->getAuthor().",". $node->getPageRank().")";
                self::$con->query($query);
            }

            self::$con->commit();
            
            self::$con->query($query);
        }

        function calculatePageRank($iterations, $withWeight=false)
        {
            global $tableManager;

            $dampingFactor = 0.85;

            //Initialisation du PageRank pour chaque auteur
            foreach($tableManager->getAllNodes() as $node) {
                $node->setPageRank(1.0 / $tableManager->getNodesCount());
                $node->setTempPageRank(1.0 / $tableManager->getNodesCount());
            }

            for ($i = 0; $i < $iterations; $i++) {
                echo "Iteration " . $i . "<br>";
                foreach($tableManager->getAllNodes() as $node) {
                    $pageRank = 0;
                    $sum = 0;

                    /*
                    Pour simuler la sauvegarde des pagerank dans un tableau temporaire, on alterne entre les deux attributs
                    Ceci est fait en raison d'une tentative d'optimisation de la mémoire vive, car on ne peut pas charger tous les noeuds en mémoire
                    */
                    
                    if($i % 2 != 0) {
                        $node->setTempPageRank($node->getPageRank());
                        $pageRank = $node->getTempPageRank();
                    } else {
                        $node->setPageRank($node->getTempPageRank());
                        $pageRank = $node->getPageRank();
                    }

                    $nbCollaborators = count($node->getCollaborators());
                    if($nbCollaborators > 0) {
                        foreach($node->getCollaborators() as $collaborator => $weight) {
                            $collabPageRank = 0;
                            $collabNode = $tableManager->getNodeByAuthorId($collaborator);

                            if($i % 2 != 0) {
                                $collabPageRank = $collabNode->getPageRank();
                            } else {
                                $collabPageRank = $collabNode->getTempPageRank();
                            }

                            $countNeighborsCollaborators = count($collabNode->getCollaborators());
                            if($countNeighborsCollaborators > 0) {
                                if($withWeight) {
                                    $sum += $dampingFactor * ($collabPageRank* $weight) / $countNeighborsCollaborators;
                                } else {
                                    $sum += ($collabPageRank/ $countNeighborsCollaborators) * $dampingFactor;
                                }
                            }
                        }
                    }
                    $pageRank = (1 - $dampingFactor) / $tableManager->getNodesCount() + $dampingFactor * $sum;

                    if($i % 2 == 0) {
                        $node->setPageRank($pageRank);
                    } else {
                        $node->setTempPageRank($pageRank);
                    }
                }
            }

            $this->tableManager->saveAllFiles();
        } 

        //On fait en sorte de pouvoir soit seulement récupérer les auteurs qui ont une affiliation, soit d'également leur assigner une affiliation
        function assignAffiliation($assign=false) {
            //On récupère tous les auteurs qui ont une affiliation

            $sql = "SELECT id, affiliation FROM authors as a WHERE a.affiliation > 0";

            $result = self::$con->query($sql);

            $authorsIds = array();
            $authorsAff = array();

            //On affecte les affiliations aux auteurs
            while($row = $result->fetch_assoc()) {
                $authorId = $row["id"];

                #Si on ne trouve pas l'auteur dans l'index, c'est qu'on n'a pas récupérer ses informations, on devra donc relancer le script d'indexation
                if(!isset($this->tableManager->getIndex()[$authorId]))
                    continue;
                
                $node = $this->tableManager->getNodeByAuthorId($authorId);
                // #L'auteur n'a pas de noeud, donc il n'a rien publié
                if($assign) {
                    $node->setAffilitation($row["affiliation"]);
                }
                $authorsIds[$authorId] = $node;

                $authorsAff[$authorId] = $row["affiliation"];  
            }

            if($assign) {
                $this->tableManager->saveAllFiles();
            }

            return [$authorsIds, $authorsAff];
        }

        function generateAffiliationGraph() {

            $affiliationsTable = $this->assignAffiliation(true);
            $authorsNodes = $affiliationsTable[0];
            $affiliations_by_author = $affiliationsTable[1];

            $affiliationsRankTable = array();

            foreach($authorsNodes as $node) {
                $authorAff = $affiliations_by_author[$node->getAuthor()];
                
                if(!isset($affiliationsRankTable[$authorAff])) {
                    $affiliationsRankTable[$authorAff] = array();
                }
                foreach($node->getCollaborators() as $collaborator => $weight) {
                    //Si l'auteur n'a pas d'affiliation, on ne le prend pas en compte
                    if(isset($affiliations_by_author[$collaborator])) {
                    
                        $collabAff = $affiliations_by_author[$collaborator];

                        if(!isset($affiliationsRankTable[$authorAff][$collabAff])) {
                            $affiliationsRankTable[$authorAff][$collabAff] = $weight;
                        } else {
                            $affiliationsRankTable[$authorAff][$collabAff] += $weight;
                        }
                    }
                }
            }

            return $affiliationsRankTable;
        }

        function calculateAffiliationPageRank($afftab, $iterations=10, $withWeight=false) {
            $pageranktmp = array();
            $rank = array();
            $dampingFactor = 0.85;

            $rank = array_fill_keys(array_keys($afftab), 1.0 / count($afftab));

            for ($i = 0; $i < $iterations; $i++) {
                echo "Iteration " . $i . "<br>";
                foreach($afftab as $aff => $collabs) {

                    $nbCollaborators = count($collabs);

                    $pageranktmp[$aff] = 0;
                    $sum = 0;

                    foreach($collabs as $collab => $weight) {
                        if(count($afftab[$collab]) > 0)
                            if($withWeight) {
                                $sum += $rank[$collab] / count($afftab[$collab]);
                            } else {
                                $sum += ($rank[$collab] * $weight) / count($afftab[$collab]) ;
                            }
                    }
                        
                    $pageranktmp[$aff] += (( 1- $dampingFactor) / count($afftab)) + $dampingFactor * $sum;

                }

                $rank = $pageranktmp;
            }

            return $rank;
        }

        function main() {

            // On vérifie si les données sont déjà stockées sur le disque
            if (!$this->tableManager->checkIfTableIsSplit()) {
                //On évite les relations doubles
                $sql = "SELECT publication_id, GROUP_CONCAT(author_id) as authors FROM publicationauthors GROUP BY publication_id";
                $result = self::$con->query($sql);
                
                $this->tableManager->splitTableIntoFiles($result);

            } else {
                $this->tableManager->precacheAllFiles();
                //Temps d'exécution
                $start = microtime(true);

                // $pageRankAffiliation = generateAffiliationGraph();
                // saveAffiliationPageRank(calculateAffiliationPageRank($pageRankAffiliation, 10, false), false);
                // saveAffiliationPageRank(calculateAffiliationPageRank($pageRankAffiliation, 10, true), true);
                
                // $this->calculatePageRank(10, true);
                // $this->savePageRank(true);
                
                $time_elapsed_secs = microtime(true) - $start;
                echo "Execution time: " . $time_elapsed_secs . " seconds";
            }
        
        }

        function generateGraphForAuthor($name, $withWeight=false) {
            $output = array();

            $name = "%$name%";
            $sql = "SELECT id, name, affiliation FROM authors as a WHERE a.name LIKE :name LIMIT 1";
            
            $sth = self::$con->prepare($sql);
            $sth->bindValue(':name', $name, PDO::PARAM_STR);
            
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            

            $id = intval($result["id"]);
            $encoding = mb_detect_encoding($result["name"], 'UTF-8, ISO-8859-1, ISO-8859-15', true);

            $table = $withWeight ? "pagerank_author_weight" : "pagerank_author";

            $node = $this->tableManager->getNodeByAuthorId($id);
            $output = array();
            $output["id"] = intval($node->getAuthor());

            $implode = count($node->getCollaborators()) > 0 ? implode(",", array_keys($node->getCollaborators()))."," : "";
            $sql = "SELECT id, name, val, author_id FROM authors as a LEFT JOIN $table as pa ON a.id = pa.author_id WHERE pa.author_id IN ($implode $id)";
            $result = self::$con->query($sql);
            
            // On récupère les pagerank des collaborateurs
            foreach($result as $row) {
                if($id == intval($row["author_id"])) {
                    $output["rank"] = floatval($row["val"]);
                    $output["name"] = mb_convert_encoding($row["name"], 'UTF-8', $encoding);
                } else {
                    $output["edges"][$row["author_id"]] = array(
                        "rank"=>floatval($row["val"]),
                        "name"=>mb_convert_encoding($row["name"], 'UTF-8', $encoding),
                    );
                }
            }

            foreach($node->getCollaborators() as $collaborator => $weight) {
                $output["edges"][$collaborator]["weight"] = $weight;
                $output["edges"][$collaborator]["id"] = $collaborator;
            }
            

            return $output;
        }
    }

?>