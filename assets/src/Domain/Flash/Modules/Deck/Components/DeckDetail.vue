<template>
    <v-row justify="space-around">
        <v-col cols="12" md="8">
            <v-card :elevation="18" class="pa-12">
                <v-row justify="center">
                    <v-col cols="12" sm="8">
                        <v-img v-if="getDeck.avatar" class="white--text align-end justify-center" height="200px">
                            <v-card-title class="justify-center">{{ getDeck.name }}</v-card-title>
                        </v-img>
                        <v-toolbar dense short flat>
                            <v-row justify="center">
                                <v-speed-dial v-model="fab" direction="right">
                                    <template v-slot:activator>
                                        <v-btn v-model="fab" elevation="0">
                                            <v-toolbar-title class="text-center">{{ getDeck.name }}
                                                <v-icon v-if="fab">mdi-close</v-icon>
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
                        <v-card-subtitle class="text-center">{{getDeck.description}}</v-card-subtitle>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-flex>
                                <v-row>
                                    <v-col cols="12" sm8>
                                        <v-hover open-delay="0.3s" v-slot:default="{hover}">
                                            <v-btn block depressed x-large color="primary" class="mb-2"
                                                :elevation="hover ? 24 : 0"
                                                :class="{'on-hover':hover}"
                                                :to="{name: 'train', params: {id: getDeck.id}}"
                                            >Учить</v-btn>
                                        </v-hover>
                                    </v-col>
                                    <v-col cols="12" sm8>
                                        <v-hover open-delay="0.3s" v-slot:default="{hover}">
                                            <v-btn block depressed x-large color="primary" class="mb-2"
                                                :elevation="hover ? 24 : 0"
                                                :class="{'on-hover':hover}"
                                                @click="createModalToggle"
                                            >Добавить новые карточки</v-btn>
                                        </v-hover>
                                    </v-col>
                                    <v-col cols="12" sm8 v-if="false">
                                        <v-expansion-panels flat hover class="mt-2">
                                            <v-expansion-panel>
                                                <v-expansion-panel-header expand-icon="mdi-menu-down" color="light">Карточки</v-expansion-panel-header>
                                                <v-expansion-panel-content eager>
<!--                                                    <list-objects :items="cards" :items-id="cardsId" pagination="true">-->
<!--                                                        <template v-slot:item="{item}"></template>-->
<!--                                                    </list-objects>-->
<!--                                                    <card-list :cards-id="deck.cards"/>-->
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

        <modal v-model="editDeckModal" type="short">
            <deck-update @deck-updated="onUpdateDeck" :id="getDeck.id"/>
        </modal>
        <modal v-model="deleteDeckModal" type="short">
            <deck-delete @deleted="onDeleteDeck" :deck="getDeck" />
        </modal>
        <modal v-model="successModal" type="short">
            <v-alert type="success">{{successMessage}}</v-alert>
        </modal>
    </v-row>
</template>

<script>
    import SuccessModal from "../../../../App/Components/Modal/SuccessModal.vue";
    import Modal from "../../../../App/Components/Modal/Modal.vue";
    import DeckUpdate from "./CRUD/DeckUpdate.vue";
    import DeckDelete from "./CRUD/DeckDelete.vue";
    import {Deck} from "../Deck";
    import Router from "../../../../App/Router";
    import ListObjects from "../../../../App/Components/List/ListObjects";
    export default {
        name: "DeckDetail",
        components: {ListObjects, DeckDelete, DeckUpdate, Modal},
        props: {
            deck: {
                required: true,
                default: new Deck()
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
            getDeck() { return this.deck || {}}
        },
        methods: {
            editDeckModalToggle() { this.editDeckModal = !this.editDeckModal;},
            deleteDeckModalToggle() { this.deleteDeckModal = !this.deleteDeckModal},
            createModalToggle() { this.createModal = !this.createModal },
            onUpdateDeck(value) {
                this.editDeckModal = !this.editDeckModal;
                this.successMessage = value;
                this.successModal = !this.successModal;
            },
            onDeleteDeck(value) {
                this.deleteDeckModal = !this.deleteDeckModal;
                this.successMessage = value;
                this.successModal = !this.successModal;
                // Router.push({name: 'Collection'});
            },
            handleSuccessCreate(value) {
                this.successMessage = value;
                this.successModal = !this.successModal;
            }
        }
    }
</script>