<template>
    <v-main>
        <v-card>
            <v-row justify="center">
                <v-col cols="9">
                    <list-objects :items="getDecks" :items-id="getDecksById" :pagination="{perPage: 10, buttonsCount: 7}">
                        <template v-slot:item="deck">
                            <deck-list-item :deck="deck.item"></deck-list-item>
                        </template>
                        <template v-slot:empty>
                            <v-row v-if="isLoading" justify="center">
                                <v-progress-circular :size="70" :width="7" color="primary" indeterminate />
                            </v-row>
                            <v-row v-else justify="center">
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

        <success-modal v-model="successModal">{{successMessage}}</success-modal>
    </v-main>
</template>

<script>
    import {mapGetters} from 'vuex';
    import DeckListItem from "./DeckListItem";
    import ListObjects from "../../../../App/Components/List/ListObjects.vue";
    import Pagination from "../../../../App/Components/Pagination/Pagination.vue";
    import DeckCreate from "./CRUD/DeckCreate.vue";
    import SuccessModal from "../../../../App/Components/Modal/SuccessModal.vue";
    import {DeckModule} from "../DeckModule";
    import {AppModule} from "../../../../App/AppModule";

    export default {
        name: 'DeckList',
        components: {SuccessModal, DeckCreate, ListObjects, DeckListItem, Pagination},
        data: function() {
            return {
                createModal: false,
                successModal: false,
                successMessage: '',
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
            getDecksById: function() { return this.decksById },
            createModalBtnPlacement: function() {
                let empty = !!this.decksById && !!this.decksById.length;
                return { 'on-side': empty, 'on-card': !empty }
            },
            isLoading: () => AppModule.isLoading
        },
        methods: {
            createModalToggle: function() {
                this.createModal = !this.createModal;
            },
            handleSuccessCreate: function(value) {
                this.successMessage = value;
                this.successModal = !this.successModal;
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