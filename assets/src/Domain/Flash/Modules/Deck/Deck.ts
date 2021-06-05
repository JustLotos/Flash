export class Deck {
    private id: Number;
    private name: String;

    constructor(id: Number, name: String) {
        this.id = id;
        this.name = name;
    }

    get getId() {
        return this.id;
    }
}