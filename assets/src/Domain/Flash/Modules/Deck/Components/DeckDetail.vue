<template>
    <v-row justify="space-around">
        <v-col cols="12" md="8">
            <v-card :elevation="18" class="pa-12">
                <v-row justify="center">
                    <v-col cols="12" sm="8">
                        <v-img v-if="deck.avatar" class="white--text align-end justify-center" height="200px">
                            <v-card-title class="justify-center">{{ deck.name }}</v-card-title>
                        </v-img>
                        <v-toolbar dense short flat>
                            <v-row justify="center">
                                <v-speed-dial v-model="fab" direction="right">
                                    <template v-slot:activator>
                                        <v-btn v-model="fab" elevation="0">
                                            <v-toolbar-title class="text-center">{{ deck.name }}
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
                        <v-card-subtitle class="text-center">{{deck.description}}</v-card-subtitle>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-flex>
                                <v-row>
                                    <v-col cols="12" sm8>
                                        <v-hover open-delay="0.3s" v-slot:default="{hover}">
                                            <v-btn block depressed x-large color="primary" class="mb-2"
                                                :elevation="hover ? 24 : 0"
                                                :class="{'on-hover':hover}"
                                                :to="{name: 'train', params: {id: deck.id}}"
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
                                    <v-col cols="12" sm8>
                                        <v-expansion-panels flat hover class="mt-2">
                                            <v-expansion-panel>
                                                <v-expansion-panel-header
                                                    expand-icon="mdi-menu-down"
                                                    color="light"
                                                >Карточки</v-expansion-panel-header>
                                                <v-expansion-panel-content eager>
                                                    <card-list :cards-id="deck.cards"/>
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
<!--            <card-create @card-created="handleSuccessCreate" :deck="deck"></card-create>-->
        </modal>
        <modal v-model="editDeckModal" type="short">
            <deck-update @deck-updated="handleSuccessEditDeck" :id="deck.id"></deck-update>
        </modal>
        <modal v-model="deleteDeckModal" type="short">
            <deck-delete @deck-deleted="handleSuccessDeleteDeck" :deck="deck"></deck-delete>
        </modal>
        <modal v-model="successModal" type="short">
            <v-alert type="success">{{successMessage}}</v-alert>
        </modal>
    </v-row>
</template>

<script>
    import CardList from "../../components/daemons/Card/CardList";
    import {validate} from "../../plugins/helpers";
    import SuccessModal from "../../components/common/Modals/SuccessModal";
    import Modal from "../../components/common/Modals/Modal";
    import DeckUpdate from "../../components/daemons/Deck/DeckUpdate";
    import DeckDelete from "../../components/daemons/Deck/DeckDelete";

    export default {
        name: "DeckDetail",
        components: {DeckDelete, DeckUpdate, Modal, CardList},
        props: {
            id: { required: true }
        },
        data: function () {
            return {
                deck: {},
                editDeckModal: false,
                deleteDeckModal: false,
                createCardModal: false,
                successModal: false,
                successMessage: '',
                fab: false,
            }
        },
        methods: {
            setDeck(deck) {
                this.deck = deck;
            },
            editDeckModalToggle: function () {
                this.editDeckModal = !this.editDeckModal;
            },
            handleSuccessEditDeck: function(value) {
                this.editDeckModal = !this.editDeckModal;
                this.successMessage = value;
                this.successModal = !this.successModal;
            },
            deleteDeckModalToggle: function () {
                this.deleteDeckModal = !this.deleteDeckModal;
            },
            handleSuccessDeleteDeck: function(value) {
                this.deleteDeckModal = !this.deleteDeckModal;
                this.successMessage = value;
                this.successModal = !this.successModal;
            },
            createModalToggle: function() {
                this.createModal = !this.createModal;
            },
            handleSuccessCreate: function(value) {
                this.successMessage = value;
                this.successModal = !this.successModal;
            },
        },
        beforeRouteEnter: async function (to , from , next) {
            if (validate(to)) {
                // await store.dispatch('DeckStore/getOne', {id: to.params.id})
                //     .then(()=>{
                //         let deck = store.getters['DeckStore/decks'][to.params.id];
                //         next(vm => vm.setDeck(deck));
                //     })
                //     .catch((errors)=>{
                //         router.push({name: '404'});
                //         console.log(errors);
                //     });
            } else {
                router.push({name: '404'});
            }
        }
    }
</script>

<style scoped></style>