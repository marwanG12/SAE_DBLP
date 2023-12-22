import {Shape} from './Shape.js'

export class Text extends Shape{
    constructor(x, y, txt) {
        super(x,y)

        var texte = document.createElementNS("http://www.w3.org/2000/svg", "text");
        $(texte).attr({
            'x': x,
            'y': y,
            'fill': 'white',
            'font-size': '10px',
            'text-anchor': 'middle',
            'dominant-baseline': 'central'
        }).text(txt); // Utiliser 'key' ou toute autre propriété comme texte

        super.setHtmlElement(texte)

        return this
    }

    setSize(size) {
        this.htmlElement.setAttribute("font-size", size)
        return this
    }

    getColor() {
        return super.getColor()
    }

    move(dx, dy) {
        super.getHtmlElement().attr('x', parseFloat(super.getHtmlElement().attr('x') || 0) + dx);
        super.getHtmlElement().attr('y', parseFloat(super.getHtmlElement().attr('y') || 0) + dy);
    }
}