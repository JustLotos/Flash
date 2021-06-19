import { Action, getModule, Module, Mutation, VuexModule } from "vuex-module-decorators";
import {Store} from "../../../App/Store";
import {AppModule} from "../../../App/AppModule";
import Vue from "vue";
import CardService from "./CardService";
import Card from "./Card";
import {Deck} from "../Deck/Deck";
import CardByDeckDTO from "../../DTO/CardByDeckDTO";

@Module({
    dynamic: true,
    store: Store,
    name: 'CardModule',
    namespaced: true
})
class VuexCard extends VuexModule {
    allIds = [];
    byId = {};

    public get cards() { return this.byId; }
    public get cardsById() { return this.allIds }
    // @ts-ignore
    public get cardById() {return  id => this.byId[id] }
    public get cardsByDeck() { return (id) => {
        console.log(this.allIds);
        console.log(this.byId);
        let cards = Object.values(this.byId).filter(value => value.deck === +id);
        console.log(cards);
        return cards.map((card: Card) => card.getId());
    }}


    @Action({ rawError: true })
    public async fetchCardsByDeck(deck: Deck): Promise<any> {
        AppModule.loading();
        const response  = await CardService.fetchCardsByDeck(deck);
        this.FETCH(response.data);
        AppModule.unsetLoading();
        return response.data;
    }

    @Mutation
    private FETCH(data: any) {
        data.forEach((dataItem: any)=>{
            let card: Card = Card.parseJSON(dataItem);
            // @ts-ignore
            Vue.set(this.byId, card.getId(), card);
            // @ts-ignore
            if (!this.allIds.includes(card.getId())) {
                // @ts-ignore
                this.allIds.push(card.getId());
            }
        });
    }
    //
    @Action({ rawError: true })
    public async add(dto: CardByDeckDTO): Promise<any> {
        AppModule.loading();
        const response  = await CardService.add(dto);
        console.log(response);
        debugger
        this.FETCH([response.data]);
        AppModule.unsetLoading();
        return response.data;
    }
    //
    // @Action({ rawError: true })
    // public async update(data: Deck): Promise<any> {
    //     AppModule.loading();
    //     const response  = await DeckService.update(data);
    //     this.FETCH_DECKS([response.data]);
    //     AppModule.unsetLoading();
    //     return response.data;
    // }
    //
    // @Action({ rawError: true })
    // public async delete(deck: Deck): Promise<any> {
    //     AppModule.loading();
    //
    //     const response  = await DeckService.delete(deck);
    //     this.DELETE(deck);
    //     AppModule.unsetLoading();
    //     return response.data;
    // }
    //
    // @Mutation
    // private DELETE(deck: Deck) {
    //     // @ts-ignore
    //     Vue.delete(this.byId, deck.id);
    //     // @ts-ignore
    //     this.allIds.splice(this.allIds.indexOf(deck.id), 1)
    // }
    //
    // @Action({ rawError: true })
    // public async get(deck: Deck): Promise<any> {
    //     AppModule.loading();
    //
    //     const response  = await DeckService.get(deck);
    //     this.FETCH_DECKS([response.data]);
    //     AppModule.unsetLoading();
    //     return response.data;
    // }
}


export const CardModule = getModule(VuexCard);