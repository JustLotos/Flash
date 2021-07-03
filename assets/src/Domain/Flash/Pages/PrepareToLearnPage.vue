<template>
  <v-layout align-center justify-center>
    <v-flex sm10 md8 lg6>
      <v-card class="mx-auto" color="#26c6da" dark>
        <v-card-title>
          <v-icon large lef>mdi-twitter</v-icon>
          <span class="text-h6 font-weight-light">{{ deck.getName() }}</span>
        </v-card-title>
        <v-card-text class="text-h5 font-weight-bold">{{ deck.getDescription() }}</v-card-text>

        <v-card-actions>
          <v-list-item class="grow">
            <v-list-item-action>Test</v-list-item-action>
            <v-row align="center" justify="end"></v-row>
          </v-list-item>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script lang="ts">
import {Deck} from "../Modules/Deck/Deck";
import {DeckModule} from "../Modules/Deck/DeckModule";
import Router from "../../App/Router";
import GetDeckDTO from "../DTO/GetDeckDTO";

export default {
  name: "PrepareToLearnPage",
  data: function() {
    return  {
      deck: new Deck()
    }
  },
  computed: {

  },
  methods: {
    setDeck: function (deck) { this.deck = deck }
  },
  async beforeRouteEnter(to, from, next) {
    let deck: Deck = new Deck(to.params);
    let dto: GetDeckDTO = new GetDeckDTO(deck, GetDeckDTO.FOR_LEARN);

    await DeckModule.get(dto).catch(function (data) {
      console.log(data);
      Router.push({name: 'Collection'});
    });

    next(function (vm) {
      vm.setDeck(DeckModule.deckById(deck.getId()))
    });
  }
}
</script>