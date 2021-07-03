import {Deck} from "../Modules/Deck/Deck";

export default class GetDeckDTO {
    public static FOR_LEARN = 'forLearn';
    public deck: Deck
    public status: string;

    constructor(deck: Deck, status: string = '') {
        this.deck = deck;
        this.status = status;
    }

    public isForLearn() {
        return this.status === GetDeckDTO.FOR_LEARN;
    }
}