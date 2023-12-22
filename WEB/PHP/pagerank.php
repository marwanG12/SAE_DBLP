<?php
    require_once ("./class/Graph.php");
    require_once("./class/TableManager.php");
    //Connect to xampp
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dblp";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed" . $conn->connect_error);
    }

    $graph = array();

    $tableManager = new TableManager();

    function saveAffiliationPageRank($pageRankTable, $withWeight=false) {
        global $conn;

        $tableName = $withWeight ? "pagerank_affiliation_weight" : "pagerank_affiliation";
       
        $sql = "DROP TABLE $tableName";
        $conn->query($sql);

        $sql = "CREATE TABLE $tableName (
            affiliation_id INT PRIMARY KEY,
            val FLOAT NOT NULL
        )";
        $conn->query($sql);

        //On commence la transaction pour gagner en performance en évitant les vérifications pour chaque insertion

        $conn->begin_transaction();
        foreach($pageRankTable as $idAff => $value) {
            
            $query = "INSERT INTO $tableName (affiliation_id, val) VALUES (".$idAff.",". $value.")";
            $conn->query($query);
        }

        $conn->commit();
        
        $conn->query($query);
    }

    function savePageRank($withWeight=false) {
        global $conn, $tableManager;

        $tableName = $withWeight ? "pagerank_author_weight" : "pagerank_author";

        $sql = "DROP TABLE $tableName";
        $conn->query($sql);

        $sql = "CREATE TABLE $tableName (
            author_id INT PRIMARY KEY,
            val FLOAT NOT NULL
        )";
        $conn->query($sql);
        
        //On commence la transaction pour gagner en performance en évitant les vérifications pour chaque insertion

        $conn->begin_transaction();
        foreach($tableManager->getAllNodes() as $node) {
            
            $query = "INSERT INTO $tableName (author_id, val) VALUES (".$node->getAuthor().",". $node->getPageRank().")";
            $conn->query($query);
        }

        $conn->commit();
        
        $conn->query($query);
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

        $tableManager->saveAllFiles();
    } 

    //On fait en sorte de pouvoir soit seulement récupérer les auteurs qui ont une affiliation, soit d'également leur assigner une affiliation
    function assignAffiliation($assign=false) {
        //On récupère tous les auteurs qui ont une affiliation
        global $conn, $tableManager;

        $sql = "SELECT id, affiliation FROM authors as a WHERE a.affiliation > 0";

        $result = $conn->query($sql);

        $authorsIds = array();
        $authorsAff = array();

        //On affecte les affiliations aux auteurs
        while($row = $result->fetch_assoc()) {
            $authorId = $row["id"];

            #Si on ne trouve pas l'auteur dans l'index, c'est qu'on n'a pas récupérer ses informations, on devra donc relancer le script d'indexation
            if(!isset($tableManager->getIndex()[$authorId]))
                continue;
            
            $node = $tableManager->getNodeByAuthorId($authorId);
            // #L'auteur n'a pas de noeud, donc il n'a rien publié
            if($assign) {
                $node->setAffilitation($row["affiliation"]);
            }
            $authorsIds[$authorId] = $node;

            $authorsAff[$authorId] = $row["affiliation"];  
        }

        if($assign) {
            $tableManager->saveAllFiles();
        }

        return [$authorsIds, $authorsAff];
    }

    function generateAffiliationGraph() {

        $affiliationsTable = assignAffiliation(true);
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

    function pagerank() {
        global $conn;
        global $tableManager;

        // On vérifie si les données sont déjà stockées sur le disque
        if (!$tableManager->checkIfTableIsSplit()) {
             //On évite les relations doubles
            $sql = "SELECT publication_id, GROUP_CONCAT(author_id) as authors FROM publicationauthors GROUP BY publication_id";
            $result = $conn->query($sql);
            
            $tableManager->splitTableIntoFiles($result);

        } else {
            $tableManager->precacheAllFiles();
            //Monitor execution time
            $start = microtime(true);

            // $pageRankAffiliation = generateAffiliationGraph();
            // saveAffiliationPageRank(calculateAffiliationPageRank($pageRankAffiliation, 10, false), false);
            // saveAffiliationPageRank(calculateAffiliationPageRank($pageRankAffiliation, 10, true), true);
            
            calculatePageRank(10, true);
            savePageRank(true);
            
            $time_elapsed_secs = microtime(true) - $start;
            echo "Execution time: " . $time_elapsed_secs . " seconds";
        }
       
    }

    pagerank();
?>