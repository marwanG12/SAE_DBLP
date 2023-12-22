import {Shape} from './Shape.js'

export class Circle extends Shape{
    constructor(x, y, radius) {
        super(x,y)

        var cercle = document.createElementNS("http://www.w3.org/2000/svg", "circle");

        $(cercle).attr({
            'cx': x,
            'cy': y,
            'r': radius,
        });

        var self = this
        $(cercle).hover(function(){
            //$(this).css("fill", "blue");
            $(this).css("stroke", "rgba(0,0,0,0.1)")
            $(this).css("strokeWidth", 10)
            }, function(){
            $(this).css("fill", self.getColor());
            $(this).css("stroke", "")
          });

        super.setHtmlElement(cercle)

        return this
    }

    getColor() {
        return super.getColor()
    }

    move(dx, dy) {
        super.getHtmlElement().attr('cx', parseFloat(super.getHtmlElement().attr('cx') || 0) + dx);
        super.getHtmlElement().attr('cy', parseFloat(super.getHtmlElement().attr('cy') || 0) + dy);
    }
}
