<template>
    <v-main fluid style="margin: 0; padding: 0">
        <v-layout justify-center align-center>
            <v-flex>
                <v-card color="primary" class="white--text pa-2">
                    <v-row justify="center">
                        <v-col v-if="getDeck.avatar" cols="4" class="justify-center text-center">
                            <v-hover v-slot:default="{ hover }">
                                <router-link :to="getLink(getDeck)">
                                    <v-avatar class="profile" color="grey" size="200" tile>
                                        <v-img class="white--text align-end" height="200px"
                                            src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"/>
                                    </v-avatar>
                                </router-link>
                            </v-hover>
                        </v-col>
                        <v-col cols="8">
                            <v-hover open-delay="0.3s" v-slot:default="{hover}">
                                <v-toolbar color="primary" dense short :elevation="hover ? 12 : 0">
                                    <v-toolbar-title>
                                        <v-btn :to="getLink(getDeck)" :color="hover ?  'primary' : 'light'">{{ getDeck.getName() }}</v-btn>
                                    </v-toolbar-title>
                                    <v-spacer></v-spacer>
                                    <v-speed-dial v-model="fab" direction="left">
                                        <template v-slot:activator>
                                            <v-hover open-delay="0.3s" v-slot:default="{hover}">
                                                <v-btn v-model="fab"
                                                   :elevation="hover ? 12 : 0"
                                                   :color="hover ? 'light' : 'primary'"
                                                >
                                                    <v-icon v-if="fab">mdi-close</v-icon>
                                                    <v-icon v-else>mdi-dots-horizontal</v-icon>
                                                </v-btn>
                                            </v-hover>
                                        </template>
                                        <v-btn @click="deleteModalToggle" class="mb-2">
                                            <v-icon>mdi-delete</v-icon>
                                        </v-btn>
                                        <v-btn @click="editModalToggle">
                                            <v-icon>mdi-pencil</v-icon>
                                        </v-btn>
                                    </v-speed-dial>
                                </v-toolbar>
                            </v-hover>
                            <v-card-subtitle dark class="card-description white--text" style="margin: 0; ">
                                {{ getDeck.getDescription() }}
                            </v-card-subtitle>
                            <v-card-actions>
                                <v-row justify="space-between" no-gutters>
                                    <div style="padding: 8px">{{ getFormattedDate }}</div>
                                    <v-btn :to="getLink(getDeck)">
                                        Перейти
                                        <v-icon>{{ 'mdi-chevron-right' }}</v-icon>
                                    </v-btn>
                                </v-row>
                            </v-card-actions>
                        </v-col>
                    </v-row>
                </v-card>
            </v-flex>

            <modal v-model="editModal" type="wide">
                <deck-update @deck-updated="handleSuccessEdit" :id="deck.id"></deck-update>
            </modal>
            <modal v-model="deleteModal" type="wide">
                <deck-delete @deleted="handleSuccessDelete" :deck="deck"/>
            </modal>
            <modal v-model="successModal" type="short">{{successMessage}}
                <v-alert type="success"><slot>Операция выполнена успешно!</slot></v-alert>
            </modal>
        </v-layout>
    </v-main>
</template>

<script>
    import DeckUpdate from "./CRUD/DeckUpdate";
    import DeckDelete from "./CRUD/DeckDelete";
    import SuccessModal from "../../../../App/Components/Modal/SuccessModal.vue";
    import Modal from "../../../../App/Components/Modal/Modal.vue";
    import {Deck} from "../Deck";
    export default {
        name: 'DeckListItem',
        components: {
            Modal,
            SuccessModal,
            DeckUpdate,
            DeckDelete
        },
        props: {
            deck: {
                required: true,
                default: {}
            },
        },
        data: function () {
            return {
                editModal: false,
                deleteModal: false,
                successModal: false,
                successMessage: '',
                fab: false,
            };
        },
        computed: {
            getDeck() {return new Deck(this.deck) || {} },
            getFormattedDate() { return this.getDeck.getFormattedDate() || '' }
        },
        methods: {
            editModalToggle() { this.editModal = !this.editModal },
            deleteModalToggle() {this.deleteModal = !this.deleteModal },
            handleSuccessEdit: function(value) {
                this.editModal = !this.editModal;
                this.successMessage = value;
                this.successModal = !this.successModal;
            },
            handleSuccessDelete: function(value) {
                this.deleteModal = !this.deleteModal;
                this.successMessage = value;
                this.successModal = !this.successModal;
            },
            getLink: (deck) => { return {name: 'DeckDetail', params: { id: deck.getId() }}}
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
        height: 54px;
    }
</style>