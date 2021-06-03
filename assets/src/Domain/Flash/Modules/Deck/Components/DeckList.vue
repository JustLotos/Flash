<template>
    <v-container>
        <v-card>
            <v-row justify="center">
                <v-col cols="9">
                    <list-objects :items="decks" :items-id="decksId" :pagination="{perPage: 10, buttonsCount: 7}">
                        <template v-slot:item="deck">
                            <deck-list-item :deck="deck.item"></deck-list-item>
                        </template>
                        <template v-slot:empty>
                            <v-row justify="center">
                                <v-col cols="12" class="text-center">Колоды еще не добавлены</v-col>
                            </v-row>
                        </template>
                        <template v-slot:notFound>
                            <v-row justify="center">
                                <v-col cols="12" class="text-center">Колоды не найдены</v-col>
                            </v-row>
                        </template>
                    </list-objects>
                </v-col>
            </v-row>
            <v-card-text :class="createModalBtnPlacement">
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
            <v-container>
                <v-layout justify-center align-center class="position-relative">
                    <deck-create @deck-created="handleSuccessCreate" />
                    <v-btn absolute top right icon dark @click="createModalToggle">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-layout>
            </v-container>
        </v-dialog>

        <success-modal v-model="successModal">{{successMessage}}</success-modal>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import DeckListItem from "./DeckListItem";
    import ListObjects from "../../../../App/Components/List/ListObjects.vue";
    import DeckCreate from "./CRUD/DeckCreate.vue";
    import SuccessModal from "../../../../App/Components/Modal/SuccessModal.vue";

    export default {
        name: 'DeckList',
        components: {SuccessModal, DeckCreate, ListObjects, DeckListItem},
        data: function() {
            return {
                createModal: false,
                successModal: false,
                successMessage: '',
            }
        },
        computed: {
            ...mapGetters('DeckModule', [
                'decks',
                'decksId'
            ]),
            createModalBtnPlacement: function() {
                let empty = !!this.decksId && !!this.decksId.length;
                return {
                    'on-side': empty,
                    'on-card': !empty
                }
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
        },
        beforeRouteEnter: async function (to , from , next) {
            await store.dispatch('DeckStore/getAll')
                .then(()=>{next()})
                .catch((error)=>{console.log("Ошибка извелчения колоды" + JSON.parse(error));});
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