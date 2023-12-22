<?php
    // require_once("Edge.php");
    class Node {
        private $author;
        private $collaborations;
        private $pageRank = 0;
        private $tempPageRank = 0;
        private $affiliationId = -1;

        public function __construct($author) {
            $this->author = $author;
            $this->collaborations = array();
        }

        public function getAuthor() {
            return $this->author;
        }

        public function setAffilitation($id) {
            $this->affiliationId = $id;
        }

        public function getAffiliation() {
            return $this->affiliationId;
        }

        public function addCollaborations($authorDatas) {

            if(is_array($authorDatas)) {
                foreach($authorDatas as $a) {
                    //Do not count self collaborations
                    if($a == $this->author) {
                        continue;
                    }
                    if(!isset($this->collaborations[$a])) {
                        $this->collaborations[$a] = 1;
                    } else {
                        $this->collaborations[$a]++;
                    }
                }
            } else {
                if(!isset($this->collaborations[$authorDatas])) {
                    $this->collaborations[$authorDatas] = 1;
                } else {
                    $this->collaborations[$authorDatas]++;
                }
            }
        }

        public function getCollaborators() {
            return $this->collaborations;
        }

        public function getPageRank() {
            return $this->pageRank;
        }

        public function setPageRank($pageRank) {
            $this->pageRank = $pageRank;
        }

        public function setTempPageRank($pageRank) {
            $this->tempPageRank = $pageRank;
        }

        public function getTempPageRank() {
            return $this->tempPageRank;
        }

        public function savePageRank() {
            $this->pageRank = $this->tempPageRank;
        }
    }
?>