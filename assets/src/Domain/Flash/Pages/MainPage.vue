<template>
  <v-layout align-center justify-center>
    <deck-list :decks="decks" :decks-by-id="decksById"/>
  </v-layout>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import {AppModule} from "../../App/AppModule";
import {UserModule} from "../../User/UserModule";
import {DeckModule} from "../Modules/Deck/DeckModule";
import DeckList from "../Modules/Deck/Components/DeckList.vue";

@Component({
  components: {DeckList}
})
export default class MainPage extends Vue{
    get decks() { return DeckModule.decks || [] }
    get decksById() { return DeckModule.decksById || {} }

    beforeRouteEnter (to, from, next) {
        DeckModule.fetchDecks().catch(data => console.log(data));
        next();
    }
}
</script>