import Axios from "../../../../Plugins/Axios";
import {ApiRouter} from "../../../App/ApiRouter";
import Repeat from "./Repeat";

export default {
    async delete(repeat: Repeat) {
        // @ts-ignore
        return Axios.delete(ApiRouter.getRouteByName('deleteRepeat', { id: repeat.getId }).path);
    },
};
