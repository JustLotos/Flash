import { Action, getModule, Module, Mutation, VuexModule } from "vuex-module-decorators";
import {Store} from "../../../App/Store";
import {AppModule} from "../../../App/AppModule";
import DeckService from "./DeckService";
import {Deck} from "./Deck";
import {cloneObject} from "../../../../Utils/Helpers";
import Vue from "vue";
import Card from "./Card/Card";

@Module({dynamic: true, store: Store, name: 'DeckModule', namespaced: true})
class VuexDeck extends VuexModule {
    allIds = [];
    byId = {};

    public get decks() { return this.byId; }
    public get decksById() { return this.allIds }
    // @ts-ignore
    public get deckById() {return  id => this.byId[id] }


    @Action({ rawError: true })
    public async fetchDecks(): Promise<any> {
        AppModule.loading();
        const response  = await DeckService.fetch();
        this.FETCH_DECKS(response.data);
        AppModule.unsetLoading();
        return response.data;
    }

    @Mutation
    private FETCH_DECKS(data: any) {
        data.forEach((dataItem: any)=>{
            let deck: Deck = Deck.parseJSON(dataItem);

            // @ts-ignore
            Vue.set(this.byId, deck.getId(), deck);
            // @ts-ignore
            if (!this.allIds.includes(deck.getId())) {
                // @ts-ignore
                this.allIds.push(deck.getId());
            }
        });
    }

    @Action({ rawError: true })
    public async add(data: Deck): Promise<any> {
        AppModule.loading();
        const response  = await DeckService.add(data);
        this.FETCH_DECKS([response.data]);
        AppModule.unsetLoading();
        return response.data;
    }

    @Action({ rawError: true })
    public async update(data: Deck): Promise<any> {
        AppModule.loading();
        const response  = await DeckService.update(data);
        this.FETCH_DECKS([response.data]);
        AppModule.unsetLoading();
        return response.data;
    }

    @Action({ rawError: true })
    public async delete(deck: Deck): Promise<any> {
        AppModule.loading();
        const response  = await DeckService.delete(deck);
        this.DELETE(deck);
        AppModule.unsetLoading();
        return response.data;
    }

    @Mutation
    private DELETE(deck: Deck) {
        // @ts-ignore
        Vue.delete(this.byId, deck.id);
        // @ts-ignore
        this.allIds.splice(this.allIds.indexOf(deck.id), 1)
    }

    @Action({ rawError: true })
    public async get(deck: Deck): Promise<any> {
        AppModule.loading();
        const response  = await DeckService.get(deck);
        this.FETCH_DECKS([response.data]);
        AppModule.unsetLoading();
        return response.data;
    }
}


export const DeckModule = getModule(VuexDeck);