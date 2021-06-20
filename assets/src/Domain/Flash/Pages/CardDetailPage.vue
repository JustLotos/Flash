<template>
    <v-flex>
        <v-row justify="space-around" >
            <v-col cols="12" md="10" style="max-width: 900px">
                <v-card :elevation="18" class="pa-12" >
                    <v-card-title class="d-flex" style="justify-content: space-between">
                      {{ getCard.getLabel() }}
                      <div>
                        <v-btn @click="deleteModalToggle"><v-icon>mdi-delete</v-icon></v-btn>
                        <v-btn @click="updateModalToggle"><v-icon>mdi-pencil</v-icon></v-btn>
                      </div>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-row>
                        <v-card-subtitle>Содержимое карточки</v-card-subtitle>
                        <v-col cols="12">
                            <v-card-text>Ключ</v-card-text>
                            {{ getCard.getFrontData }}
                        </v-col>
                        <v-col cols="12">
                            <v-card-text>Значение</v-card-text>
                            {{ getCard.getBackData }}
                        </v-col>
                    </v-row>
                    <v-row>
                      <v-card-subtitle>Повторения</v-card-subtitle>
                      <v-col cols="12">
                        <v-data-table
                            dense
                            :headers="getRepeatHeaders"
                            :items="getDataForTable"
                            item-key="name"
                            class="elevation-1"
                        />
                      </v-col>
                    </v-row>
                </v-card>
            </v-col>
        </v-row>

        <v-dialog v-model="updateCardModal" max-width="750px">
            <v-container>
                <v-layout justify-center align-center style="position: relative">
                    <card-update :card="getCard" @updated="updateModalToggle" />
                    <v-btn absolute top right icon dark @click="updateModalToggle">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-layout>
            </v-container>
        </v-dialog>

        <v-dialog v-model="deleteCardModal" max-width="750px">
            <v-container>
                <v-layout justify-center align-center style="position: relative">
                    <card-delete :card="getCard" @deleted="onDeleteCard" />
                    <v-btn absolute top right icon dark @click="deleteModalToggle">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-layout>
            </v-container>
        </v-dialog>

    </v-flex>
</template>

<script lang="ts">
import Card from "../Modules/Card/Card";
import {CardModule} from "../Modules/Card/CardModule";
import CardDelete from "../Modules/Card/Components/CRUD/CardDelete.vue";
import CardUpdate from "../Modules/Card/Components/CRUD/CardUpdate.vue";
import Router from "../../App/Router";
import {RawLocation} from "vue-router/types/router";
import ListObjects from "../../App/Components/List/ListObjects.vue";
import {RepeatModule} from "../Modules/Repeat/RepeatModule";

export default {
        name: "CardDetailPage",
        components: {ListObjects, CardUpdate, CardDelete},
        data: function (){
            return {
                card: {},
                updateCardModal: false,
                deleteCardModal: false,
            }
        },
        computed: {
            getRepeatHeaders: function () {
                return [
                  {text: 'Дата повторения', value: 'updatedAt'},
                  {text: 'Время повторения', value: 'time'},
                  {text: 'Оценка повторения', value: 'ratingScore'},
                ];
            },
            getDataForTable: function () {
                return Object.values(RepeatModule.repeats);
            },
            getCard: function(): Card {
                let cardId = new Card(this.card).getId();
                return CardModule.cardById(cardId) || new Card();
            }
        },
        methods: {
            setCard(card) { this.card = card },
            updateModalToggle: function () {
                this.updateCardModal = !this.updateCardModal;
            },
            deleteModalToggle: function () {
                this.deleteCardModal =  !this.deleteCardModal;
            },
            onDeleteCard: function (deckId: number) {
                this.deleteCardModal = !this.deleteCardModal;
                let route = {name: 'DeckDetail', params: { id: deckId }} as RawLocation
                Router.push(route);
            }
        },
        beforeRouteEnter: async function (to , from , next) {
            let card = new Card(to.params as Card);
            await CardModule.get(card).catch(function (error) {
                console.log(error)
                next({name: 'Collection'});
            });

            next(vm => vm.setCard(CardModule.cardById(card.getId())));
        }
    }
</script>