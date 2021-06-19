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
    public get cardById(): (id: number) => Card {
        return (id) => this.byId[id] as Card
    }
    public get cardsByDeck() { return (id) => {
        let cards = Object.values(this.byId).filter(value => value.deck === +id);
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
        this.FETCH([ response.data ]);
        AppModule.unsetLoading();
        return response.data;
    }

    @Action({ rawError: true })
    public async update(card: Card): Promise<any> {
        AppModule.loading();
        const response  = await CardService.update(card);
        this.FETCH([response.data]);
        AppModule.unsetLoading();
        return response.data;
    }

    @Action({ rawError: true })
    public async delete(card: Card): Promise<any> {
        AppModule.loading();
        const response  = await CardService.delete(card);
        this.DELETE(card);
        AppModule.unsetLoading();
        return response.data;
    }

    @Mutation
    private DELETE(card: Card) {
        // @ts-ignore
        Vue.delete(this.byId, card.id);
        // @ts-ignore
        this.allIds.splice(this.allIds.indexOf(card.id), 1)
    }

    @Action({ rawError: true })
    public async get(card: Card): Promise<any> {
        AppModule.loading();
        const response  = await CardService.get(card);
        this.FETCH([response.data]);
        AppModule.unsetLoading();
        return response.data;
    }
}


export const CardModule = getModule(VuexCard);