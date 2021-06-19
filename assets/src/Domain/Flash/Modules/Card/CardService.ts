import Axios from "../../../../Plugins/Axios";
import {ApiRouter} from "../../../App/ApiRouter";
import Card from "./Card";
import {Deck} from "../Deck/Deck";
import CardByDeckDTO from "../../DTO/CardByDeckDTO";

export default {
    async fetchCardsByDeck(deck: Deck) {
        return Axios.get(ApiRouter.getRouteByName('fetchCardsByDeck', {deckId: deck.getId()}).path);
    },
    async add(dto: CardByDeckDTO) {
        let deckId = dto.getDeck().getId();
        return Axios.post(ApiRouter.getRouteByName('addCard', { deckId }).path, dto.getCard());
    },
    async update(card: Card) {
        // @ts-ignore
        return Axios.put(ApiRouter.getRouteByName('updateCard', { id: card.getId() }).path, card);
    },
    async delete(card: Card) {
        // @ts-ignore
        return Axios.delete(ApiRouter.getRouteByName('deleteCard', { id: card.getId() }).path);
    },
    async get(card: Card) {
        // @ts-ignore
        return Axios.get(ApiRouter.getRouteByName('getCard', { id: card.getId() }).path);
    }
};
