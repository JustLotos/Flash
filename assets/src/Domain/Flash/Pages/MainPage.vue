<template>
  <v-layout align-center justify-center>
      <h1 class="text-center text--white">{{$t("welcome")}}</h1>
  </v-layout>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import {AppModule} from "../../App/AppModule";
import {UserModule} from "../../User/UserModule";
import {DeckModule} from "../Deck/DeckModule";

@Component({})
export default class MainPage extends Vue{
    beforeRouteEnter (to, from, next) {
        !UserModule.isAuthenticated ? next(AppModule.getRedirectToUnAuth) : next()
        DeckModule.fetchDecks();
    }
}
</script>