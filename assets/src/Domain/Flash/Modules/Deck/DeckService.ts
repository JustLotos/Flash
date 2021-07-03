import Axios from "../../../../Plugins/Axios";
import {ApiRouter} from "../../../App/ApiRouter";
import {Deck} from "./Deck";
import GetDeckDTO from "../../DTO/GetDeckDTO";

export default {
    async fetch() {
        return Axios.get(ApiRouter.getRouteByName('fetchDecks').path);
    },
    async add(deck: Deck) {
        return Axios.post(ApiRouter.getRouteByName('addDeck').path, deck);
    },
    async update(deck: Deck) {
        // @ts-ignore
        return Axios.put(ApiRouter.getRouteByName('updateDeck', { id: deck.getId() }).path, deck);
    },
    async delete(deck: Deck) {
        // @ts-ignore
        return Axios.delete(ApiRouter.getRouteByName('deleteDeck', { id: deck.getId() }).path);
    },
    async get(dto: GetDeckDTO) {
        // @ts-ignore
        let route = ApiRouter.getRouteByName('getDeck', { id: dto.deck.getId() });
        return Axios.get(route.path, { params:{ isLearn: dto.isForLearn() }});
    }
};
