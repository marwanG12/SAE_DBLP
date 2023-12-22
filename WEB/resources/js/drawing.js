import {Circle} from './Circle.js'
import { Text } from './Text.js';
import { Line } from './Line.js';

function generateRandColor() {
    return `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`;
}

$(document).ready(function() {

    function normalizeAroundOne(originalValue, minValue, maxValue, targetValue = 1) {
        if (minValue === maxValue) {
            return targetValue;
        }
    
        const normalizedValue = (originalValue - minValue) / (maxValue - minValue) * (targetValue - 1) + 1;
    
        return normalizedValue;
    }


    function drawGraph(node) {

        //On supprime les anciens éléments
        $("#render").empty()

        let shapes = [];
        let minVal = 10
        let maxVal = 30

        if(node.edges != undefined) {
            minVal = Math.min(...Object.keys(node.edges).map(key => node.edges[key].rank));
            maxVal = Math.max(...Object.keys(node.edges).map(key => node.edges[key].rank));
        }


        let centerX = $("#render").width() / 2;
        let centerY = $("#render").height() / 2;
        let radius = 300;
        let totalNodes = node.edges != undefined ? Object.keys(node.edges).length + 1 : 1;
        let maxRankSize = 20; // Plafond pour la taille du rayon
        let minRankSize = 1;

        let nodes = {};
        // Calculer les positions des cercles

        nodes[node.id] = { id: node.id, rank: 0, edges: node.edges, position: { x: centerX, y: centerY }, name: node.name };

        if(node.edges != undefined) {
            Object.keys(node.edges).forEach((key, index) => {
                if(nodes[key] == undefined) {
                    nodes[key] = { id: key, rank: node.edges[key].rank, weight: node.edges[key].weight, name: node.edges[key].name };
                }

                const angle = (index / totalNodes) * 2 * Math.PI;
                const x = centerX + radius * Math.cos(angle);
                const y = centerY + radius * Math.sin(angle);
                nodes[key].position = { x, y };
            });


            // Dessiner les lignes
            Object.keys(node.edges).forEach(key => {
                //console.log(node.edges[key])
                var line = new Line(nodes[node.id].position.x, nodes[node.id].position.y, nodes[key].position.x, nodes[key].position.y)
                .appendTo('#render')

                shapes.push(line)
            });
        }
        

        // Dessiner les cercles
        Object.keys(nodes).forEach(key => {
            var rank = normalizeAroundOne(nodes[key].rank, minVal, maxVal, 2);
            var rankSize = Math.max(minRankSize, Math.min(rank, maxRankSize)); // Plafonner la taille du rayon
            console.log(rankSize)
            var circle = new Circle(nodes[key].position.x, nodes[key].position.y, rankSize * 35)
            .setColor(generateRandColor())
            .appendTo('#render')
            .onClick(function(e) {
                searchAuthor(nodes[key].name)
            })

            var text = new Text(nodes[key].position.x, nodes[key].position.y, nodes[key].name)
            .setColor('white')
            .setSize(rankSize * 10)
            .appendTo('#render')
            .onClick(function(e) {
                console.log("Clicked on " + e)
            })

            shapes.push(circle)
            shapes.push(text)
        });


        var isDragging = false;
        var previousMousePosition = { x: 0, y: 0 };

        $('#render').mousedown(function(e) {
            isDragging = true;
            previousMousePosition = { x: e.clientX, y: e.clientY };
        });

        $(document).mousemove(function(e) {
            if (isDragging) {
                var dx = e.clientX - previousMousePosition.x;
                var dy = e.clientY - previousMousePosition.y;
                
                for(var i = 0; i < shapes.length; i++) {
                    shapes[i].move(dx, dy)
                }

                previousMousePosition = { x: e.clientX, y: e.clientY };
            }
        });

        $(document).mouseup(function() {
            isDragging = false;
        });

        var viewBox = { x: 0, y: 0, width: 500, height: 500 };

        $('#render').on('wheel', function(event) {
            event.preventDefault();

            var svg = event.currentTarget;
            var pt = svg.createSVGPoint();
            pt.x = event.clientX;
            pt.y = event.clientY;
            var cursorPt = pt.matrixTransform(svg.getScreenCTM().inverse());

            // Calcul du facteur de zoom
            var scaleFactor = Math.pow(1.1, -event.originalEvent.deltaY * 0.05);
            viewBox.width /= scaleFactor;
            viewBox.height /= scaleFactor;

            // Ajuster la position de la viewBox pour zoomer sur le curseur
            viewBox.x = cursorPt.x - (cursorPt.x - viewBox.x) / scaleFactor;
            viewBox.y = cursorPt.y - (cursorPt.y - viewBox.y) / scaleFactor;

            $(this).attr('viewBox', `${viewBox.x} ${viewBox.y} ${viewBox.width} ${viewBox.height}`);
        });
    }

    function searchAuthor(authorName) {
        //Requête ajax sur la page fetch.php
        $.ajax("./fetch.php", {
            method: "GET",
            data: {
                name: authorName,
                type:"graph",
                weight:$("input[name='weight']:checked") != undefined ? 1 : 0
            },
            success: function(data) {
                drawGraph(data)
            }
        })
    }

    $("#search").on("keydown", function(event) {
        //Si on appuie sur entrée 
        if(event.originalEvent.keyCode == 13) {
            if(event.target.value != "") {
                searchAuthor(event.target.value)
            }
        }
    });

  })