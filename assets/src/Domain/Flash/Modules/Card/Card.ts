import {Deck} from "../Deck/Deck";

export default class Card {
    private readonly id: Number;
    private readonly deck: Number;
    private records: [string] = [];


    // @ts-ignore
    constructor({id, deck} = {}) {
        this.id = id || 0;
        this.deck = deck || 0;
    }

    public getId(): Number|String { return this.id }
    public getDeck(): Number|String { return this.deck }
    get getFrontData(): String { return '123' }
    set getFrontData(data: string) {
        console.log(data)
    }
    get getBackData(): String { return '123' }
    set getBackData(data: string) {
        console.log(data)
    }

    public static parseJSON(data: any): Card {
        let cardString: string = JSON.stringify(data);
        let parsedDeck = JSON.parse(cardString,function (key, value) {
            if(key === 'deck') {
                return (new Deck(value)).getId();
            }
            return value;
        });

        return new Card(parsedDeck);
    }
}