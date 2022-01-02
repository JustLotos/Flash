import Vue from "vue";
import Vuex from "vuex";
import {AppModule} from "./AppModule";
import {UserModule} from "../User/UserModule";
import {DeckModule} from "../Flash/Modules/Deck/DeckModule";
import {CommunityDeckModule} from "../Flash/Modules/Community/DeckModule";
import {CardModule} from "../Flash/Modules/Card/CardModule";
import {RepeatModule} from "../Flash/Modules/Repeat/RepeatModule";

Vue.use(Vuex);

export interface IRootState {
    UserModule: UserModule;
    AppModule: AppModule;
    DeckModule: DeckModule;
    CardModule: CardModule;
    RepeatModule: RepeatModule;
    CommunityDeckModule: CommunityDeckModule;
}

export const Store = new Vuex.Store<IRootState>({});
