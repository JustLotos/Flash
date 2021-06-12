<template>
    <v-layout align-center justify-center>
        <deck-detail v-if="!isLoading" :deck="deck"/>
        <v-row v-else justify="center">
            <v-progress-circular :size="70" :width="7" color="primary" indeterminate />
        </v-row>
    </v-layout>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import DeckDetail from "../Modules/Deck/Components/DeckDetail.vue";
import {AppModule} from "../../App/AppModule";
import {Deck} from "../Modules/Deck/Deck";
import {DeckModule} from "../Modules/Deck/DeckModule";
import Router from "../../App/Router";
@Component({ components: { DeckDetail } })
export default class DeckDetailPage extends Vue {
    deck: Deck|null = null;

    setDeck(deck: Deck) { this.deck = deck}
    get getDeck() {return this.deck || {}}
    get isLoading() { return AppModule.isLoading }

    beforeRouteEnter (to, from, next) {
        let deck: Deck = new Deck(to.params);

        DeckModule.get(deck).then(function () {
            next(vm => vm.setDeck(DeckModule.deckById(deck.getId())))
        }).catch(function (data) {
            console.log(data);
            Router.push({name: 'Collection'});
        });
    }
}
</script>