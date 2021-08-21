<template>
  <v-layout align-center justify-center>
    <v-card class="mx-auto" max-width="900">
      <v-container fluid>
        <v-row dense>
          <v-col v-for="page in pages" :key="page.title" :cols="page.flex">
            <v-card>
              <v-img
                  :src="page.src"
                  class="white--text align-end"
                  gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                  height="200px"
              >
                <v-card-title v-text="page.title"></v-card-title>
              </v-img>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn :to="{name: 'CommunityCollection'}">
                  Перейти<v-icon>{{ 'mdi-chevron-right' }}</v-icon>
                </v-btn>
<!--                <v-btn icon><v-icon>mdi-share-variant</v-icon></v-btn>-->
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </v-layout>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({})
export default class CommunityPage extends Vue{
    pages = [
      {
        title: 'Коллекции',
        src: 'https://cdn.vuetifyjs.com/images/cards/house.jpg',
        flex: 12
      }
    ];
}
</script>