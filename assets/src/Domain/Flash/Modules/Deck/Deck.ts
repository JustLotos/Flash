export class Deck {
    private id: Number;
    private name: String;

    constructor(id: Number, name: String) {
        this.id = id;
        this.name = name;
    }

    getId(): Number {
        return this.id;
    }
}