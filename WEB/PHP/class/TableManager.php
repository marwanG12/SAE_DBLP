<?php

    require_once("Node.php");
    require_once("Package.php");
    // require_once("Edge.php");

    class TableManager {
        private $maxFileSize = 8 * 1024 * 1024; // 5 Mo en octets
        private $dataPath = "./tmp/";
        private $index = array();
        private $numPrecacheFiles = 0;
        private $precachedFiles = array();
        private $currentFileIndex = 0;
        
        public function __construct() {

        }

        public function precacheAllFiles() {
            for($i = 0; $i < $this->index['max_file_index']; $i++) {
                $this->precacheFile($i);
            }
        }

        public function precacheFile($fileIndex) {
            if (!isset($this->precachedFiles[$fileIndex])) {
                // Get file content and unserialize it
                $this->precachedFiles[$fileIndex] = unserialize(file_get_contents($this->dataPath . "file_$fileIndex.tmp"));
            }
        
            // If there are more than 5 files in the precache array, remove the first one but not the current one
            //if (count($this->precachedFiles) > $this->numPrecacheFiles) {
            if ($this->numPrecacheFiles > 0 && count($this->precachedFiles) > $this->numPrecacheFiles) {
                $keys = array_keys($this->precachedFiles);

                
                foreach($keys as $key) {
                    if ($key != $fileIndex && $this->currentFileIndex != $key) {
                        // Save the file before removing it
                        $this->precachedFiles[$key]->save();
                        unset($this->precachedFiles[$key]);
                        break;
                    }
                }
            }

            return;
        }        

        public function checkIfTableIsSplit() {
            if (file_exists($this->dataPath . "index.tmp")) {
                $this->index = unserialize(file_get_contents($this->dataPath . "index.tmp"));
                return true;
            }
            return false;
        }

        private function cleanFiles() {
            $files = glob($this->dataPath . '*'); // get all file names
            foreach($files as $file){ // iterate files
                if(is_file($file)) {
                    unlink($file); // delete file
                }
            }
        }

        public function getIndex() {
            return $this->index;
        }
    
        public function splitTableIntoFiles($queryResult) {
            
            if ($this->checkIfTableIsSplit()) {
                return;
            }
            
            $this->cleanFiles();

            
            $this->currentFileIndex = 0;
            // $currentData = '';
            $averageSize = 0;
            $this->precachedFiles[$this->currentFileIndex] = new Package($this->dataPath. "file_$this->currentFileIndex.tmp");
            
            while ($row = $queryResult->fetch_assoc()) {
                //On dsplit étant donné qu'on a le resultat sous la forme publication = [auteur1, auteur2, auteur3, ...]
                $authorsResult = explode(",", $row["authors"]);
                
                //On stock 
                foreach($authorsResult as $author) {

                    if(isset($this->index[$author])) {
                        $this->getNodeByAuthorId($author)->addCollaborations($authorsResult);
                    } else {
                        $node = new Node($author);
                        $node->addCollaborations($authorsResult);

                        // On ajoute le noeud dans le package actuel
                        $this->precachedFiles[$this->currentFileIndex]->addNode($node);

                        $averageSize += strlen(serialize($node));
                        $this->index[$author] = $this->currentFileIndex;

                        if ($averageSize > $this->maxFileSize) {
                            // Wait for the current package to be saved before creating a new one
                            $this->precachedFiles[$this->currentFileIndex]->save();

                            //Remove the current package from memory

                            unset($this->precachedFiles[$this->currentFileIndex]);
                            $this->currentFileIndex++;
                            $averageSize = 0;

                            $this->precachedFiles[$this->currentFileIndex] = new Package($this->dataPath. "file_$this->currentFileIndex.tmp");
                        }
                    }
                }
            }

            $this->precachedFiles[$this->currentFileIndex]->save();

            $this->index["num_nodes"] = count($this->index);
            $this->index["max_file_index"] = $this->currentFileIndex;
            file_put_contents($this->dataPath . "index.tmp", serialize($this->index));
            
        }
    
        public function getValue($key) {
            // Implémenter la logique pour trouver et lire la valeur depuis le fichier approprié
            // Charger le fichier en mémoire si ce n'est pas déjà fait
        }

        public function getNodeByAuthorId($authorId) {
            if(!isset($this->index[$authorId])) {
                return false;
            } else {
                if(!isset($this->precachedFiles[$this->index[$authorId]])) {
                    $this->precacheFile($this->index[$authorId]);
                }

                return $this->precachedFiles[$this->index[$authorId]]->getNode($authorId);
            }
        }

        public function getAllNodes() {
            for($i = 0; $i <= $this->index['max_file_index']; $i++) {
                
                if(!isset($this->precachedFiles[$i])) {
                    $this->precacheFile($i);
                }

                foreach($this->precachedFiles[$i]->getNodes() as $node) {
                    yield $node;
                }
            }

        }

        public function getNodesCount() {
            return $this->index['num_nodes'];
        }

        public function saveAllFiles() {
            foreach($this->precachedFiles as $file) {
                $file->save();
            }
        }

        // Autres méthodes utiles...
    }
    
?>