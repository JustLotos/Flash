import Axios from "../../../../Plugins/Axios";
import {ApiRouter} from "../../../App/ApiRouter";
import {Deck} from "./Deck";

export default {
    async fetch() {
        return Axios.get(ApiRouter.getRouteByName('fetchDecks').path);
    },
    async add(deck: Deck) {
        debugger;
        return Axios.post(ApiRouter.getRouteByName('addDeck').path, deck);
    },
    async update(deck: Deck) {
        return Axios.post(ApiRouter.getRouteByName('updateDeck').path, deck);
    },
    async delete(deck: Deck) {
        return Axios.post(ApiRouter.getRouteByName('updateDeck').path, deck);
    },
};
