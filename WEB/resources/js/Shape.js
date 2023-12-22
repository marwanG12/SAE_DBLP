export class Shape {
    constructor(x, y) {
        this.x =0
        this.y=0
        this.htmlElement = null;

        return this
    }

    setHtmlElement(element) {
        this.htmlElement = element;
    }

    getHtmlElement() {
        return $(this.htmlElement);
    }

    drawShape() {
        console.log("Drawing a shape");
    }
    calculateArea() {
        console.log("Don't know area of unknown shape");
        return 0;
    }

    getColor() {
        return this.color;
    }

    onClick(func) {
        $(this.htmlElement).on("click" , func)
        return this
    }

    move() {

    }

    setColor(color) {
        if(this.htmlElement != null) {
            this.htmlElement.setAttribute("fill", color);
        }
        this.color = color
        return this
    }

    getColor() {
        return this.color
    }

    appendTo(selector) {
        $(selector).append(this.htmlElement);
        return this
    }

    move(dx, dy) {

    }
}
