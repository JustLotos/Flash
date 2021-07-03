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
                    <v-card-subtitle>Следующее повторение {{ nextDateRepeat }}</v-card-subtitle>
                  </v-row>

                    <v-row>
                      <v-col cols="12">
                        <v-data-table
                            dense
                            :headers="getRepeatHeaders"
                            :items="getDataForTable"
                            item-key="name"
                            class="elevation-24"
                        >
                          <template v-slot:top>
                            <v-toolbar flat>
                              <v-toolbar-title>Повторения</v-toolbar-title>
                              <v-divider class="mx-4" inset vertical />
                              <v-spacer />
                              <v-dialog v-model="deleteRepeatModal" max-width="500px">
                                <v-card>
                                  <v-card-title class="text-h5">
                                    Вы хотите удалить повторение?
                                  </v-card-title>
                                  <v-card-actions>
                                    <v-spacer />
                                    <v-btn color="blue darken-1" text @click="deleteRepeatToggle">Отмена</v-btn>
                                    <v-btn color="blue darken-1" text @click="deleteRepeatAction">Удалить</v-btn>
                                    <v-spacer />
                                  </v-card-actions>
                                </v-card>
                              </v-dialog>
                            </v-toolbar>
                          </template>
                          <template v-slot:item.actions="{ item }">
                            <v-icon small @click="deleteRepeatToggle(item)">mdi-delete</v-icon>
                          </template>
                        </v-data-table>
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
import Repeat from "../Modules/Repeat/Repeat";
import DateFormat from "../../../Utils/Mixins/DateFormat";
import {DateHelper} from "../../../Utils/DateHelper";

export default {
        name: "CardDetailPage",
        components: {ListObjects, CardUpdate, CardDelete},
        data: function (){
            return {
                card: {},
                updateCardModal: false,
                deleteCardModal: false,
                deleteRepeatModal: false,
                deletedItem: {}
            }
        },
        computed: {
            getRepeatHeaders: function () {
                return [
                  {text: 'Дата', value: 'updatedAt'},
                  {text: 'Длительность', value: 'time'},
                  {text: 'Оценка', value: 'ratingScore'},
                  {text: 'Интервал', value: 'interval'},
                  {text: '', value: 'actions', sortable: false },
                ];
            },
            getDataForTable: function () {
                let repeatsId: Array<number> = RepeatModule.repeatsByCard(this.getCard.getId());
                let repeats = RepeatModule.repeats;
                let resultRepeats = {};
                repeatsId.map((id: number) => {
                    resultRepeats[id] = repeats[id];
                });
                return Object.values(resultRepeats);
            },
            getCard: function(): Card {
                let cardId = new Card(this.card).getId();
                return CardModule.cardById(cardId) || new Card();
            },
            nextDateRepeat: function () {
              return DateHelper.dateFormat(this.getCard.getNextRepeatDate());
            }
        },
        methods: {
            setCard(card) { this.card = card },
            updateModalToggle: function () { this.updateCardModal = !this.updateCardModal; },
            deleteModalToggle: function () { this.deleteCardModal =  !this.deleteCardModal; },
            deleteRepeatToggle: function (repeat: Repeat) {
              this.deletedItem = new Repeat(repeat);
              this.deleteRepeatModal = !this.deleteRepeatModal;
            },
            onDeleteCard: function (deckId: number) {
                this.deleteCardModal = !this.deleteCardModal;
                let route = {name: 'DeckDetail', params: { id: deckId }} as RawLocation
                Router.push(route);
            },
            deleteRepeatAction: async function () {
                await RepeatModule.delete(this.deletedItem).catch((error) => {
                    console.log(error);
                })

                this.deleteRepeatModal = !this.deleteRepeatModal;
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