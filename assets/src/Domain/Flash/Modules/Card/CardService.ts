import Axios from "../../../../Plugins/Axios";
import {ApiRouter} from "../../../App/ApiRouter";
import Card from "./Card";
import {Deck} from "../Deck/Deck";

export default {
    async fetchCardsByDeck(deck: Deck) {
        return Axios.get(ApiRouter.getRouteByName('fetchCardsByDeck', {deckId: deck.getId()}).path);
    },
    // async add(card: Card) {
    //     return Axios.post(ApiRouter.getRouteByName('addCard').path, deck);
    // },
    // async update(deck: Deck) {
    //     // @ts-ignore
    //     return Axios.put(ApiRouter.getRouteByName('updateDeck', { id: deck.getId() }).path, deck);
    // },
    // async delete(deck: Deck) {
    //     // @ts-ignore
    //     return Axios.delete(ApiRouter.getRouteByName('deleteDeck', { id: deck.getId() }).path);
    // },
    // async get(deck: Deck) {
    //
    //     // @ts-ignore
    //     return Axios.get(ApiRouter.getRouteByName('getDeck', { id: deck.getId() }).path);
    // }
};
