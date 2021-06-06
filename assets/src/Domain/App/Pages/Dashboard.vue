<template>
    <v-main fluid fill-height>
        <v-layout align-center justify-center>
          <div>
            <h1 class="text-center text--white">{{$t("welcome")}}</h1>
<!--            <p class="text-center text&#45;&#45;white">Это главная вкладка приложения и скоро тут появятся виджеты</p>-->
          </div>
        </v-layout>
    </v-main>
</template>
<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
@Component
export default class Dashboard extends Vue{}
</script>