<?php
    require_once("Node.php");
    // require_once("Edge.php");

    class Graph {
        private $nodes = array();
        private $edges = array();
        private $edgesPosition = array();
        private $nodesPosition = array();

        public function __construct()
        {
            
        }

        public function addEdge(Edge $edge) {
            array_push($this->edges, $edge);
            $this->edgesPosition[$edge->getNode1()->getAuthor()."_".$edge->getNode2()->getAuthor()] = count($this->edges) - 1;
        }

        public function addNode(Node $node) {
            array_push($this->nodes, $node);
            $this->nodesPosition[$node->getAuthor()] = count($this->nodes) - 1;
        }

        public function getNodeByAuthor($author) {
            // foreach($this->nodes as $node) {
            //     if($node->getAuthor() == $author) {
            //         return $node;
            //     }
            // }
            $index = -1;
            if(isset($this->nodesPosition[$author])) {
                $index = $this->nodesPosition[$author];
            }
            if($index > -1) {
                return $this->getNode($index);
            }
        }

        public function getNode(int $index) {
            if($index < 0 || $index >= count($this->nodes)) {
                return false;
            }
            return $this->nodes[$index];
        }

        public function getEdge(int $index) {
            if($index < 0 || $index >= count($this->edges)) {
                return false;
            }
            return $this->edges[$index];
        }

        public function getEdgeByNodes(Node $node1, Node $node2) {
            $index = -1;
            // foreach($this->edges as $edge) {
            //     if(($edge->getNode1()->getAuthor() == $node1->getAuthor() && $edge->getNode1()->getAuthor() == $node2->getAuthor())
            //     || $edge->getNode1()->getAuthor() == $node2->getAuthor() && $edge->getNode1()->getAuthor() == $node1->getAuthor()) {
            //         return $edge;
            //     }
            // }
            // return false;

            if(isset($this->edgesPosition[$node1->getAuthor()."_".$node2->getAuthor()])) {
                $index = $this->edgesPosition[$node1->getAuthor()."_".$node2->getAuthor()];
            } elseif (isset($this->edgesPosition[$node2->getAuthor()."_".$node1->getAuthor()])) {
                $index = $this->edgesPosition[$node2->getAuthor()."_".$node1->getAuthor()];
            }

            if($index > -1) {
                return $this->getEdge($index);
            }

        }

        public function getNodes() {
            return $this->nodes;
        }

        public function getEdges() {
            return $this->edges;
        }

    }
?>