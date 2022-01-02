<template>
  <v-layout align-center justify-center>
    <deck-list :decks="decks" :decks-by-id="decksById">
      <template v-slot:deck-item="{deckItem}">
        <community-deck-list-item :deck="deckItem" />
      </template>
    </deck-list>
  </v-layout>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import {DeckModule} from "../../Modules/Deck/DeckModule";
import DeckList from "../../Modules/Deck/Components/DeckList.vue";
import CommunityDeckListItem from "../../Modules/Deck/Components/Community/CommunityDeckListItem";
import {CommunityDeckModule} from "../../Modules/Community/DeckModule";

@Component({
  components: {DeckList, CommunityDeckListItem}
})
export default class CommunityCollectionPage extends Vue{
  get decks() { return CommunityDeckModule.decks || [] }
  get decksById() { return CommunityDeckModule.decksById || {} }

  beforeRouteEnter (to, from, next) {
    CommunityDeckModule.fetchDecks().then((item) => {
    }).catch(data => console.log(data));
    next();
  }
}
</script>