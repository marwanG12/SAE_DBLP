import {Shape} from './Shape.js'

export class Line extends Shape{
    constructor(x, y, endX, endY) {
        super(x,y)

        var ligne = document.createElementNS("http://www.w3.org/2000/svg", "line");

        // $(ligne).attr({
        //     'x1': nodes[key].position.x,
        //     'y1': nodes[key].position.y,
        //     'x2': nodes[edgeKey].position.x,
        //     'y2': nodes[edgeKey].position.y,
        //     'stroke': 'rgba(0,0,0, 0.1)',
        //     "stroke-width":5
        // })
        ;$(ligne).attr({
            'x1': x,
            'y1': y,
            'x2': endX,
            'y2': endY,
            'stroke': 'rgba(0,0,0, 0.1)',
            "stroke-width":5
        });

        $(ligne).hover(function(){
            $(this).css("stroke-width", 10);
            }, function(){
            $(this).css("stroke-width", 5);
        })

        super.setHtmlElement(ligne)

        return this
    }

    move(dx, dy) {
        super.getHtmlElement().attr('x1', parseFloat(super.getHtmlElement().attr('x1')) + dx);
        super.getHtmlElement().attr('y1', parseFloat(super.getHtmlElement().attr('y1')) + dy);
        super.getHtmlElement().attr('x2', parseFloat(super.getHtmlElement().attr('x2')) + dx);
        super.getHtmlElement().attr('y2', parseFloat(super.getHtmlElement().attr('y2')) + dy);
    }
}