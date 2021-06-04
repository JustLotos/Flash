<template>
    <v-card>
        <v-card-title class="justify-center">Добавление колоды</v-card-title>
        <deck-form :deck="deck" :event-name="'create'"  @create="create" :errors="createErrors" :is-loading="false">
            <template v-slot:submit>Добавить</template>
        </deck-form>
    </v-card>
</template>

<script>
    import DeckForm from "../DeckForm";
    import {mapGetters} from "vuex";
    export default {
        name: "DeckCreate",
        components: {DeckForm},
        data: function () {
            return {
                deck: {},
                errors: {}
            }
        },
        computed: {
            createErrors: function () {
                if (this.errors) {
                    return this.errors;
                }
                return {};
            }
        },
        methods: {
            async create(createDeckPayload) {

                console.log(createDeckPayload)
                debugger
                // await this.$store.dispatch("DeckStore/create", createDeckPayload).then(()=>{
                //     this.deck = {};
                //     this.$emit('deck-created', 'Колода успешно создаана!');
                // }).catch((errors)=>{
                //     console.log(errors);
                //     // console.log("Ошибка создание колоды: " + JSON.parse(errors));
                // })

            }
        }
    }
</script>

<style scoped>
    .d-block{
        display: block;
    }
</style>