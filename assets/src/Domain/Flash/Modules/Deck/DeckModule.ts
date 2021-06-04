import { Action, getModule, Module, Mutation, VuexModule } from "vuex-module-decorators";
import {Store} from "../../../App/Store";
import {AppModule} from "../../../App/AppModule";
import DeckService from "./DeckService";
import LoginResponse from "../../../User/Entity/API/Login/LoginResponse";
import RegisterResponse from "../../../User/Entity/API/Register/ByEmail/RegisterByEmailResponse";
import {Deck} from "./Deck";
import {cloneObject} from "../../../../Utils/Helpers";
import Vue from "vue";

@Module({dynamic: true, store: Store, name: 'DeckModule'})
class VuexDeck extends VuexModule {
    deck: Deck = new Deck(-1, "default");
    allIds = [];
    byId = {};

    public get decks() { return this.byId; }

    public get decksById() { return this.allIds }

    @Action({ rawError: true })
    public async fetchDecks(): Promise<any> {
        AppModule.loading();
        const response  = await DeckService.fetchDecks();
        this.FETCH_DECKS(response.data);
        AppModule.unsetLoading();
        return response.data;
    }

    @Mutation
    private FETCH_DECKS(data: any) {
        data.forEach((deck: Deck)=>{
            let newDeck = cloneObject(deck);
            if (newDeck.cards) {
                newDeck.cards = newDeck.cards.map(card => card.id);
            }
            Vue.set(this.byId, newDeck.id, newDeck);
            if (!this.allIds.includes(newDeck.id)) {
                this.allIds.push(newDeck.id);
            }
        });
    }
}


export const DeckModule = getModule(VuexDeck);