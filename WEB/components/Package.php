<?php
    class Package {
        private $nodes = array();
        private $fileName;

        public function __construct($fileName) {
            $this->fileName = $fileName;
        }

        public function addNode($node) {
            if(!isset($this->nodes[$node->getAuthor()])) {
                $this->nodes[$node->getAuthor()] = $node;
            }
        }

        public function getNode($author) {
            return isset($this->nodes[$author]) ? $this->nodes[$author] : false;
        }

        public function getNodes() {
            return $this->nodes;
        }

        public function save() {
            file_put_contents($this->fileName, serialize($this));
        }
        
    }
?>