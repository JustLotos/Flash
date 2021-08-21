<template>
    <v-main fluid style="margin: 0; padding: 0">
        <v-layout justify-center align-center>
            <v-flex>
                <v-card color="primary" class="white--text pa-2">
                    <v-row justify="center" class="m-0 pa-0">
                        <v-col cols="8">
                            <v-hover open-delay="0.3s" v-slot:default="{hover}">
                                <v-toolbar color="primary" dense short :elevation="hover ? 12 : 0">
                                    <v-toolbar-title class="d-flex  " style="justify-content: space-between">
                                        <v-btn :color="hover?'primary':'light'">
                                            <span v-if="getDeck.getName() && getDeck.getName().length > 40">
                                                {{getDeck.getName().slice(0, 40)}} ...
                                            </span>
                                            <span v-else>{{ getDeck.getName() }}</span>
                                        </v-btn>
                                    </v-toolbar-title>
                                    <v-divider></v-divider>
                                    <v-btn v-if="true" >
                                        <v-icon>{{ 'mdi-plus' }}</v-icon>
                                    </v-btn>
                                    <v-btn v-else>
                                        <v-icon>{{ 'mdi-minus' }}</v-icon>
                                    </v-btn>
                                </v-toolbar>
                            </v-hover>
                            <v-card-subtitle dark class="card-description white--text" style="margin: 0; ">
                                <span v-if="getDeck.getDescription() && getDeck.getDescription().length > 100">
                                   <span v-if="shortDescription">
                                        {{ getDeck.getDescription().slice(0, 100) }}
                                       <v-btn plain small color="white" @click="toggleFullDescription">...</v-btn>
                                   </span>
                                    <span v-else>
                                        {{ getDeck.getDescription() }}
                                        <br>
                                        <v-btn plain small color="white" @click="toggleFullDescription">Скрыть</v-btn>
                                    </span>
                                </span>
                                <span v-else>
                                    {{ getDeck.getDescription() }}
                                </span>
                            </v-card-subtitle>
                        </v-col>
                    </v-row>
                </v-card>
            </v-flex>
        </v-layout>
    </v-main>
</template>

<script>
    import SuccessModal from "../../../../../App/Components/Modal/SuccessModal.vue";
    import Modal from "../../../../../App/Components/Modal/Modal.vue";
    import {Deck} from "../../Deck";
    export default {
        name: 'CommunityDeckListItem',
        components: {
            Modal,
            SuccessModal
        },
        props: {
            deck: {
                required: true,
                default: {}
            },
        },
        data: function () {
            return {
                shortDescription: true,
                editModal: false,
                deleteModal: false,
                successModal: false,
                successMessage: '',
                fab: false,
            };
        },
        computed: {
            getDeck() { return new Deck(this.deck) || {} },
            getFormattedDate() { return this.getDeck.getFormattedDate() || '' }
        },
        methods: {
            editModalToggle() { this.editModal = !this.editModal },
            deleteModalToggle() {this.deleteModal = !this.deleteModal },
            onDeckUpdate: function(value) {
                this.editModal = !this.editModal;
                this.successMessage = value;
                this.successModal = !this.successModal;
            },
            onDeckDelete: function(value) {
                this.deleteModal = !this.deleteModal;
                this.successMessage = value;
                this.successModal = !this.successModal;
            },
            getLink: function(deck) {
                return {name: 'DeckDetail', params: { id: deck.getId() }}
            },
            toggleFullDescription: function () {
                this.shortDescription = !this.shortDescription;
            }
        }
    }
</script>

<style scoped>
    .position-top{
        position: absolute;
        bottom: 100%;
        left: 100%;
        margin-bottom: -100px;
        margin-left: -75px;
    }
    .card-description{
        min-height: 54px;
    }
</style>