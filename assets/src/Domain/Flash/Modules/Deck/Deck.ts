export class Deck {
    private id: Number;
    private name: String;

    constructor(id: Number = 0, name: String = "") {
        this.id = id;
        this.name = name;
    }

    public getId(): Number {
        return this.id;
    }
}