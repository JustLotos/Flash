import { Action, getModule, Module, Mutation, VuexModule } from "vuex-module-decorators";
import {Store} from "../../App/Store";
import {AppModule} from "../../App/AppModule";
import DeckService from "./DeckService";
import LoginResponse from "../../User/Entity/API/Login/LoginResponse";
import RegisterResponse from "../../User/Entity/API/Register/ByEmail/RegisterByEmailResponse";
import {Deck} from "./Deck";

@Module({dynamic: true, store: Store, name: 'DeckModule'})
class VuexDeck extends VuexModule {
    deck: Deck = new Deck(-1, "default");

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
        console.log(data);
    }
}


export const DeckModule = getModule(VuexDeck);