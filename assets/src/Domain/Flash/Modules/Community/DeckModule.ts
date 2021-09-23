import { Action, getModule, Module, Mutation, VuexModule } from "vuex-module-decorators";
import {Store} from "../../../App/Store";
import {AppModule} from "../../../App/AppModule";
import Vue from "vue";
import GetDeckDTO from "../../DTO/GetDeckDTO";
import EventEmitter from "../../../../Utils/EventModule/EventEmitter";
import Event from "../../../../Utils/EventModule/Event";
import {VuexDeck} from '../Deck/DeckModule';
import CommunityService from "./CommunityService";
import {Deck} from "../Deck/Deck";

@Module({
    dynamic: true,
    store: Store,
    name: 'CommunityDeckModule',
    namespaced: true
})
class VuexCommunityDeck extends VuexModule {
    allIds = [];
    byId = {};


    public get decks() { return this.byId; }
    public get decksById() { return this.allIds }
    // @ts-ignore
    public get deckById() {return  id => this.byId[id] }

    @Action({ rawError: true })
    public async fetchDecks(): Promise<any> {
        AppModule.loading();
        const response  = await CommunityService.fetchDecks();
        this.FETCH_DECKS(response.data, this);
        AppModule.unsetLoading();
        return response.data;
    }

    @Mutation
    protected FETCH_DECKS(data: any, module = null) {
        let self = module ? module : this;
        data.forEach((dataItem: any)=>{
            let deck: Deck = Deck.parseJSON(dataItem);

            // @ts-ignore
            Vue.set(self.byId, deck.getId(), deck);
            // @ts-ignore
            if (!self.allIds.includes(deck.getId())) {
                // @ts-ignore
                self.allIds.push(deck.getId());
            }
        });
    }
}

export const CommunityDeckModule = getModule(VuexCommunityDeck, Store);