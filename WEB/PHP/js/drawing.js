import {Circle} from './Circle.js'
import { Text } from './Text.js';
import { Line } from './Line.js';

function generateRandColor() {
    return `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`;
}

function showInformations() {

}

const nodes = {
    "10": {
      "id": 10,
      "edges": { "60": 1 },
      "rank": 9
    },
    "60": {
      "id": 60,
      "edges": { "10": 1 },
      "rank": 50
    },
    "80": {
      "id": 80,
      "edges": { "60": 1 },
      "rank": 5
    },
    "90": {
      "id": 90,
      "edges": { "10": 1, "60":2 },
      "rank": 5
    }
  };

const authors = {
    "10": {
        "id": 1,
        "name": "Paul Kocher",
        "email": ""
    },
    "60": {
        "id": 1,
        "name": "Farshad Nayeri",
        "email": ""
    },
    "80": {
        "id": 1,
        "name": "Moritz Lipp",
        "email": ""
    },
    "90": {
        "id": 1,
        "name": "Michael Stonebraker",
        "email": ""
    },
}

function searchAuthor(txt) {
    console.log(txt)
}

$(document).ready(function() {

    var shapes = []

    
    const centerX = $("#render").width() / 2;
    const centerY = $("#render").height() / 2;
    const radius = 150;
    const totalNodes = Object.keys(nodes).length;
    const maxRankSize = 20; // Plafond pour la taille du rayon

    // Calculer les positions des cercles
    Object.keys(nodes).forEach((key, index) => {
        const angle = (index / totalNodes) * 2 * Math.PI;
        const x = centerX + radius * Math.cos(angle);
        const y = centerY + radius * Math.sin(angle);
        nodes[key].position = { x, y };
    });

    // Dessiner les lignes
    Object.keys(nodes).forEach(key => {
        Object.keys(nodes[key].edges).forEach(edgeKey => {
            var line = new Line(nodes[key].position.x, nodes[key].position.y, nodes[edgeKey].position.x, nodes[edgeKey].position.y)
            .appendTo('#render')

            shapes.push(line)
        });
    });

    // Dessiner les cercles
    Object.keys(nodes).forEach(key => {
        var rankSize = Math.min(nodes[key].rank, maxRankSize); // Plafonner la taille du rayon
        var circle = new Circle(nodes[key].position.x, nodes[key].position.y, rankSize * 5)
        .setColor(generateRandColor())
        .appendTo('#render')
        .onClick(function(e) {
            $.ajax("./fetch.php", {
                method: "GET",
                data: {
                    id: nodes[key].id,
                    type:"author"
                },
                success: function(data) {
                    data = JSON.parse(data)
                    let pubId = data["publications_id"].split(",")
                    let pubTitle = data["publications_id"].split(",")

                    $("#publications").empty()
                    $("#authorName").text(data["name"])
                    $("#authorPublications").text("Nombre publications : "+pubId.length)

                    
                    for(var i = 0; i < pubId.length; i++) {
                        $("#publications").append(`<p>${pubTitle[i]}</p>`)
                    }
                }
            })
        })

        var text = new Text(nodes[key].position.x, nodes[key].position.y, authors[nodes[key].id + ""].name)
        .setColor('white')
        .setSize(rankSize * 1.5)
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
  })