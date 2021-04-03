<template>
  <v-layout align-center justify-center>
  </v-layout>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import {AppModule} from "../../App/AppModule";
import {UserModule} from "../../User/UserModule";

@Component({})
export default class MainPage extends Vue{
    beforeRouteEnter (to, from, next) { UserModule.isAuthenticated ? next(AppModule.getRedirectToAuth) : next() }
}
</script>