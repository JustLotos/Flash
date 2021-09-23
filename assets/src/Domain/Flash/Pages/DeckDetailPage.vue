<template>
    <v-layout align-center justify-center>
        <deck-detail v-if="true" :deck="deck" :cards="cards"/>
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
import {CardModule} from "../Modules/Card/CardModule";
import GetDeckDTO from "../DTO/GetDeckDTO";

@Component({ components: { DeckDetail } })
export default class DeckDetailPage extends Vue {
    deck: Deck = new Deck();

    setDeck(deck: Deck) { this.deck = deck}
    get cards() {
      return CardModule.cardsByDeck(this.deck.getId())
    }
    get isLoading() { return AppModule.isLoading }

    async beforeRouteEnter(to, from, next) {
      let deck: Deck = new Deck(to.params);
      await DeckModule.get(new GetDeckDTO(deck)).catch(function (data) {
          console.log(data);
          Router.push({name: 'Community'});
      });

      await CardModule.fetchCardsByDeck(deck).catch(function (data) {
          console.log(data);
          Router.push({name: 'Community'});
      })

      next(function (vm) {
          vm.setDeck(DeckModule.deckById(deck.getId()))
      });
    }
}
</script>