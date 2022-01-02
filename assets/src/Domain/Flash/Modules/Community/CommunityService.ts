import Axios from "../../../../Plugins/Axios";
import {ApiRouter} from "../../../App/ApiRouter";
import {Deck} from "./Deck";
import GetDeckDTO from "../../DTO/GetDeckDTO";

export default {
    async fetchDecks() {
        return Axios.get(ApiRouter.getRouteByName('fetchCommunityDecks').path);
    },
};
