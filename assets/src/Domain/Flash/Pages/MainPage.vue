<template>
  <v-layout align-center justify-center>
    <deck-list></deck-list>
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
    beforeRouteEnter (to, from, next) {
        !UserModule.isAuthenticated ? next(AppModule.getRedirectToUnAuth) : next()
        DeckModule.fetchDecks();
    }
}
</script>