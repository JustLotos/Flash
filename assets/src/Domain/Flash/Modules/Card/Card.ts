import {Deck} from "../Deck/Deck";

export default class Card {
    private readonly id: Number;
    private readonly deck: Number;

    // @ts-ignore
    constructor({id, deck}) {
        this.id = id || 0;
        this.deck = deck || 0;
    }

    public getId(): Number|String { return this.id }
    public getDeck(): Number|String { return this.deck }

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