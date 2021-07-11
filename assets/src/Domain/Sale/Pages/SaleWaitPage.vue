<template>
  <v-layout align-center justify-center>
    <v-flex sm10 md8 lg6>
      <v-card class="mx-auto" color="red" dark>
        <v-card-title>
          <v-icon large lef>mdi-success</v-icon>
          <span>Минуту</span>
        </v-card-title>
        <v-card-text class="text-h5 font-weight-bold">Подождите пока оплата завершится!</v-card-text>
        <v-card-actions>
          <v-list-item class="grow">
            <v-list-item-action></v-list-item-action>
            <v-row align="center" justify="end"></v-row>
          </v-list-item>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "SaleWaitPage"
}
</script>