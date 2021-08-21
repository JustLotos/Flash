<template>
    <v-main style="padding-top: 0">
        <v-card max-width="900px" style="margin: auto">
            <v-row justify="center">
                <v-col cols="9">
                    <v-toolbar dense>
                        <v-text-field
                            v-if="!searchToggle"
                            hide-details
                            prepend-icon="mdi-magnify"
                            single-line
                            v-model="searchField"
                        />
                        <v-btn icon @click="clearToolbar()">
                          <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <list-objects
                        :items="getDecks"
                        :items-id="getDecksByIdSortByDate"
                        :pagination="{perPage: 10, buttonsCount: 7}"
                    >
                        <template v-slot:item="deck">
                          <slot name="deck-item" :deck-item="deck.item">
                            <deck-list-item :deck="deck.item" />
                          </slot>
                        </template>
                        <template v-slot:empty>
                            <v-row v-if="isLoading" justify="center" style="padding: 150px 100px 150px 100px">
                                <v-progress-circular :size="70" :width="7" color="primary" indeterminate />
                            </v-row>
                            <v-row v-else-if="searchField" justify="center" style="padding: 150px 100px 150px 100px">
                              <v-col cols="12" class="text-center">Ничего не найдено</v-col>
                            </v-row>
                            <v-row v-else justify="center" style="padding: 150px 100px 150px 100px">
                                <v-col cols="12" class="text-center">Колоды еще не добавлены</v-col>
                            </v-row>
                        </template>
                    </list-objects>
                </v-col>
            </v-row>
            <v-card-text v-if="!isLoading" :class="createModalBtnPlacement">
                <v-fab-transition>
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" @click="createModalToggle" color="primary" dark absolute top right fab>
                                <v-icon>mdi-plus</v-icon>
                            </v-btn>
                        </template>
                        <span>Добавить новую колоду</span>
                    </v-tooltip>
                </v-fab-transition>
            </v-card-text>
        </v-card>

        <v-dialog v-model="createModal" max-width="700px">
            <v-main>
                <v-layout justify-center align-center class="position-relative">
                    <deck-create @deck-created="handleSuccessCreate" />
                    <v-btn absolute top right icon dark @click="createModalToggle">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-layout>
            </v-main>
        </v-dialog>

        <success-modal v-model="successModal" modal >{{successMessage}}</success-modal>
    </v-main>
</template>

<script lang="ts">
    import {mapGetters} from 'vuex';
    import DeckListItem from "./DeckListItem";
    import ListObjects from "../../../../App/Components/List/ListObjects.vue";
    import Pagination from "../../../../App/Components/Pagination/Pagination.vue";
    import DeckCreate from "./CRUD/DeckCreate.vue";
    import SuccessModal from "../../../../App/Components/Modal/SuccessModal.vue";
    import {DeckModule} from "../DeckModule";
    import {AppModule} from "../../../../App/AppModule";
    import {Deck} from "../Deck";

    export default {
        name: 'DeckList',
        components: {SuccessModal, DeckCreate, ListObjects, DeckListItem, Pagination},
        data: function() {
            return {
                createModal: false,
                successModal: false,
                successMessage: '',
                searchToggle: false,
                searchField: "",
            }
        },
        props: {
            decks: {
                required: true,
                default: []
            },
            decksById: {
                required: true,
                default: {}
            }
        },
        computed: {
            getDecks: function() { return this.decks },
            getDecksByIdSortByDate: function () {
                let decks = this.getDecks;
                let deckEntries = Object.entries(decks);

                if(this.searchField) {
                    deckEntries = deckEntries.filter(([key, deck]) => {
                      return deck.getName().includes(this.searchField) ||
                          deck.getDescription().includes(this.searchField);
                    });
                }

                deckEntries.sort(function (a, b) {
                    return new Date(b[1].getUpdatedAt()) - new Date(a[1].getUpdatedAt());
                });

                let sortedDeck = [];
                deckEntries.forEach(item => sortedDeck.push(item[0]));
                return sortedDeck;
            },
            getDecksById: function() {
                return this.decksById
            },
            createModalBtnPlacement: function() {
                let empty = !!this.decksById && !!this.decksById.length;
                return { 'on-side': empty, 'on-card': !empty }
            },
            isLoading: () => AppModule.isLoading,
            showClearButton: function() {
                return this.searchToggle;
            }
        },
        methods: {
            createModalToggle: function() {
                this.createModal = !this.createModal;
            },
            handleSuccessCreate: function(value) {
                this.successMessage = value;
                this.successModal = !this.successModal;
            },
            toggleSearch: function () {
                console.log(this.searchToggle);
                this.searchToggle = !this.searchToggle;
            },
            clearToolbar: function() {
                this.searchToggle = false;
            },
        }
    }
</script>

<style scoped>
    .on-side{
        position: fixed;
        right: 7%;
        bottom: 12%;
        z-index: 10;
        padding: 0;
    }
    .on-card{
        position: absolute;
        z-index: 10;
        top: 50%;
        left: -30%;
        padding: 0;
    }
    .position-relative{
        position: relative;
    }
</style>