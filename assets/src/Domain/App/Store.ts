import Vue from "vue";
import Vuex from "vuex";
import {AppModule} from "./AppModule";
import {UserModule} from "../User/UserModule";
import {DeckModule} from "../Flash/Deck/DeckModule";

Vue.use(Vuex);

export interface IRootState {
    UserModule: UserModule;
    AppModule: AppModule;
    DeckModule: DeckModule;
}

export const Store = new Vuex.Store<IRootState>({});
