import Axios from "../../../../Plugins/Axios";
import {ApiRouter} from "../../../App/ApiRouter";

export default {
    async add(dto) {
        return Axios.post('https://sci.interkassa.com/', {
            ik_co_id: '60eadb421981f556366c5ede',
            ik_pm_no: "ID_4233",
            ik_am: "100.00",
            ik_cur: "RUB",
            ik_desc: "Тестовый платеж",
            ik_exp: "2021-07-12",
            ik_ltm: "2592000",
            ik_loc: "ru",
            ik_enc: "utf-8",
            ik_int: "json"
        });
    },
};
