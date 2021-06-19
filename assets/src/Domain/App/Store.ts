import Vue from "vue";
import Vuex from "vuex";
import {AppModule} from "./AppModule";
import {UserModule} from "../User/UserModule";
import {DeckModule} from "../Flash/Modules/Deck/DeckModule";
import {CardModule} from "../Flash/Modules/Card/CardModule";

Vue.use(Vuex);

export interface IRootState {
    UserModule: UserModule;
    AppModule: AppModule;
    DeckModule: DeckModule;
    CardModule: CardModule;
}

export const Store = new Vuex.Store<IRootState>({});
