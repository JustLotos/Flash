import Axios from "../../../Plugins/Axios";
import {ApiRouter} from "../../App/ApiRouter";

export default {
    async fetchDecks() {
        return Axios.get(ApiRouter.getRouteByName('fetchDecks').path);
    },
};
