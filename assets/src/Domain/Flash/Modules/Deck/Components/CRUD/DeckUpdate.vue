<template>
    <v-card>
        <v-card-title class="justify-center">Редактирование колоды</v-card-title>
        <deck-form
                :deck="deck"
                :event-name="'update'"
                :errors="updateErrors"
                @update="update"
        >
            <template v-slot:submit>Сохранить</template>
        </deck-form>
    </v-card>
</template>

<script>
    import DeckForm from "../DeckForm";
    import {mapGetters} from 'vuex';
    import {DeckModule} from "../../DeckModule";
    import {Deck} from "../../Deck";
    export default {
        name: "DeckUpdate",
        components: {DeckForm},
        props: {
            deck: {
                required: true,
                default: new Deck()
            }
        },
        data: function() {
            return {
                errors: {}
            }
        },
        computed: {
            getDeck: function () {
                return this.deck || {};
            },
            updateErrors:function() {
                return this.errors || {}
            },
        },
        methods: {
            async update (deck) {
                await DeckModule.update(deck).then(()=>{
                    this.$emit('updated', 'Колода успешно сохранена!');
                }).catch((errors)=>{console.log(errors);})
            }
        },
    }
</script>