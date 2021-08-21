<template>
  <v-layout align-center justify-center>
    <v-row v-if="isMainPage" justify="center">
      <v-col cols="7">
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
                  <div style="display: flex; justify-content: space-between; padding: 20px">
                    <v-card-title v-text="page.title"></v-card-title>
                    <v-btn :to="{name: 'CommunityCollection'}" style="position: relative; margin-top: 10px">
                      Перейти<v-icon>{{ 'mdi-chevron-right' }}</v-icon>
                    </v-btn>
                  </div>
                </v-img>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
      </v-col>
    </v-row>
    <router-view></router-view>
  </v-layout>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({})
export default class CommunityPage extends Vue{
    pages = [
      {
        title: 'Коллекции сообщества',
        src: 'https://cdn.vuetifyjs.com/images/cards/house.jpg',
        flex: 12
      }
    ];

    get isMainPage() {
      return this.$route.matched.length === 1
    }
}
</script>