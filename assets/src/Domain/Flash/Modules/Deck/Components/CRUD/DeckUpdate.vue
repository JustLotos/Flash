<template>
    <v-card>
        <v-card-title class="justify-center">Редактирование колоды</v-card-title>
        <deck-form :deck="deck" :event-name="'update'" @update="update" :errors="updateErrors">
            <template v-slot:submit>Сохранить</template>
        </deck-form>
    </v-card>
</template>

<script>
    import DeckForm from "../DeckForm";
    import {mapGetters} from 'vuex';

    export default {
        name: "DeckUpdate",
        components: {DeckForm},
        props: {
            id: {
                required: true
            },
        },
        data: function() {
            return {
                errors: {}
            }
        },
        computed: {
            deck: function () {
                return {};
                // return this.$store.getters['DeckStore/decks'][this.id];
            },
            updateErrors: function () {
                if (this.errors) {
                    return this.errors;
                }
                return {};
            }
        },
        methods: {
            async update (deck) {
                // deck.id = this.deck.id;
                // await this.$store.dispatch("DeckStore/update", deck).then(()=>{
                //     this.$emit('deck-updated', 'Колода успешно сохранена!');
                // }).catch((errors)=>{console.log(errors);})
            }
        },
    }
</script>

<style scoped>
    .centered-input >>> input {
        text-align: center
    }
</style>