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
    import {DeckModule} from "../../DeckModule";
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
            deck: () => DeckModule.deckById(this.id),
            updateErrors: () => this.errors ? this.errors : {},
        },
        methods: {
            async update (deck) {
                await DeckModule.update(deck).then(()=>{
                    this.$emit('deck-updated', 'Колода успешно сохранена!');
                }).catch((errors)=>{console.log(errors);})
            }
        },
    }
</script>