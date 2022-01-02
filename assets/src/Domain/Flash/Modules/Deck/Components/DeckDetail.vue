<template>
    <v-row justify="space-around">
        <v-col cols="12" md="8">
            <v-card :elevation="18" class="pa-12">
                <v-row justify="center">
                    <v-col cols="12" sm="8">
                        <v-img v-if="false" class="white--text align-end justify-center" height="200px">
                            <v-card-title class="justify-center">{{ getDeck.getName() }}</v-card-title>
                        </v-img>
                        <v-toolbar dense short flat>
                            <v-row justify="center">
                                <v-speed-dial v-model="fab" direction="right">
                                    <template v-slot:activator>
                                        <v-btn v-model="fab" elevation="0">
                                            <v-toolbar-title class="text-center">
                                              {{ getDeck.getName() }}<v-icon v-if="fab">mdi-close</v-icon>
                                            </v-toolbar-title>
                                        </v-btn>
                                    </template>
                                    <v-btn @click="deleteDeckModalToggle" class="mb-2">
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                    <v-btn @click="editDeckModalToggle">
                                        <v-icon>mdi-pencil</v-icon>
                                    </v-btn>
                                </v-speed-dial>
                            </v-row>
                        </v-toolbar>
                        <v-card-subtitle class="text-center">{{getDeck.getDescription()}}</v-card-subtitle>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-flex>
                                <v-row>
                                    <v-col cols="12" sm8 >
                                        <v-hover open-delay="0.3s" v-slot:default="{hover}">
                                            <v-btn block depressed x-large color="primary" class="mb-2"
                                                :elevation="hover ? 24 : 0"
                                                :class="{'on-hover':hover}"
                                                :to="{ name: 'PrepareToLearn', params: {id: getDeck.getId()} }"
                                            >Учить</v-btn>
                                        </v-hover>
                                        <v-btn
                                            v-if="getDeck.isPublished()"
                                            block
                                            depressed
                                            x-large
                                            color="lighten"
                                            class="mb-2"
                                        >Опубликовано</v-btn>

                                        <v-btn
                                            v-else
                                            block
                                            depressed
                                            x-large
                                            color="primary"
                                            class="mb-2"
                                            @click="publish"
                                        >Опубликовать</v-btn>
                                    </v-col>
                                    <v-col cols="12" sm8 v-if="getCardsId.length">
                                        <v-expansion-panels flat hover class="mt-2">
                                            <v-expansion-panel>
                                                <v-expansion-panel-header
                                                    expand-icon="mdi-menu-down"
                                                    color="light"
                                                >Карточки</v-expansion-panel-header>
                                                <v-expansion-panel-content eager>
                                                    <card-list :cards-id="getCardsId" :cards="getCards"/>
                                                    <v-col cols="12" sm8>
                                                      <v-hover open-delay="0.3s" v-slot:default="{hover}">
                                                        <v-btn block depressed x-large color="primary" class="mb-2"
                                                               :elevation="hover ? 24 : 0"
                                                               :class="{'on-hover':hover}"
                                                               @click="createModalToggle"
                                                        >Добавить карту</v-btn>
                                                      </v-hover>
                                                    </v-col>
                                                </v-expansion-panel-content>
                                            </v-expansion-panel>
                                        </v-expansion-panels>
                                    </v-col>
                                </v-row>
                            </v-flex>
                        </v-card-actions>
                    </v-col>
                </v-row>
            </v-card>
        </v-col>

      <modal v-model="createCardModal" type="wide">
        <card-create @created="onCreateCard" :deck="getDeck" />
      </modal>
        <modal v-model="editDeckModal" type="short">
            <deck-update @updated="onUpdateDeck" :deck="getDeck"/>
        </modal>
        <modal v-model="deleteDeckModal" type="short">
            <deck-delete @deleted="onDeleteDeck" :deck="getDeck" />
        </modal>
        <modal v-model="successModal" type="short">
            <v-alert type="success">{{successMessage}}</v-alert>
        </modal>
    </v-row>
</template>

<script lang="ts">
    import Modal from "../../../../App/Components/Modal/Modal.vue";
    import DeckUpdate from "./CRUD/DeckUpdate.vue";
    import DeckDelete from "./CRUD/DeckDelete.vue";
    import {Deck} from "../Deck";
    import ListObjects from "../../../../App/Components/List/ListObjects";
    import Router from "../../../../App/Router";
    import {CardModule} from "../../Card/CardModule";
    import CardList from "../../Card/Components/CardList";
    import CardCreate from "../../Card/Components/CRUD/CardCreate";
    import {DeckModule} from "../DeckModule";
    export default {
        name: "DeckDetail",
        components: {CardCreate, CardList, ListObjects, DeckDelete, DeckUpdate, Modal},
        props: {
            deck: {
                required: true,
                default: new Deck()
            },
            cards: {
                required: true,
                default: []
            }
        },
        data: function () {
            return {
                editDeckModal: false,
                deleteDeckModal: false,
                createCardModal: false,
                successModal: false,
                successMessage: '',
                fab: false,
            }
        },
        computed: {
            getDeck(): Deck { return this.deck || {} },
            getCardsId() {
              return this.cards || {}
            },
            getCards() {
              return CardModule.cards || {}
            }
        },
        methods: {
            editDeckModalToggle: function() {
                this.editDeckModal = !this.editDeckModal;
            },
            deleteDeckModalToggle: function() {
                this.deleteDeckModal = !this.deleteDeckModal;
            },
            createModalToggle: function() {
                this.createCardModal = !this.createCardModal;
            },
            onUpdateDeck: function (value) {
                this.editDeckModal = !this.editDeckModal;
                this.successMessage = value;
                this.successModal = !this.successModal;
            },
            onDeleteDeck: function(value) {
                this.deleteDeckModal = !this.deleteDeckModal;
                this.successMessage = value;
                this.successModal = !this.successModal;
                Router.push({name: 'Community'});
            },
            onCreateCard: function (value) {
              this.createCardModal = !this.createCardModal;
            },
            publish: function() {
              DeckModule.publish(this.getDeck).then(function (data) {
                console.log(data);
              }).catch(function (error) {
                console.log(error)
              });

            }
        }
    }
</script>