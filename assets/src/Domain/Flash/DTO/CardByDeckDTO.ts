import Card from "../Modules/Card/Card";
import {Deck} from "../Modules/Deck/Deck";

export default class CardByDeckDTO {
    private readonly card: Card;
    private readonly deck: Deck;

    constructor(deck: Deck, card: Card) {
        this.card = card;
        this.deck = deck;
    }

    public getDeck(): Deck {
        return this.deck;
    }

    public getCard(): Card {
        return this.card;
    }
}