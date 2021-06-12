<template>
    <v-flex xs10 class="justify-center" style="max-width: 600px">
        <v-form ref="deckDeleteForm">
            <v-card>
                <v-flex xs10 offset-xs1 class="text-center">
                    <v-card-title primary-title class="justify-center">
                        <v-alert
                                class="mt-5"
                                v-if="deleteError"
                                type="error"
                                transition="fade-transition"
                        >{{deleteError}}</v-alert>
                        Введите имя колоды: {{deck.name}}
                    </v-card-title>
                    <v-card-subtitle>Для подтверждения удаления</v-card-subtitle>
                    <v-card-text>
                        <v-text-field
                            v-model="name.value"
                            :rules="name.rules"
                            label="Название"
                            required
                            validate-on-blur
                            class="centered-input"
                        ></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center">
                        <v-btn color="primary" @click="onDeckDelete" :loading="isLoading">Удалить</v-btn>
                    </v-card-actions>
                </v-flex>
            </v-card>
        </v-form>
    </v-flex>
</template>

<script>
    import DeckForm from "../DeckForm";
    import {DeckModule} from "../../DeckModule";
    import {Deck} from "../../Deck";
    export default {
        name: "DeckDelete",
        components: {DeckForm},
        props: {
            deck: {
                required: true,
                default: new Deck()
            },
        },
        computed: {
            deleteError: function () {
                if (this.errors) {
                    return 'Произошла ошибка во время удаление повторите позже';
                }
                return false;
            }
        },
        data: function () {
            return {
                name: {
                    rules: [
                        v => v.toLowerCase() === this.deck.name.toLowerCase().trim()
                            || 'Введенное имя не совпадает с именем колоды',
                    ],
                    value: ''
                },
                isLoading: false
            }
        },
        methods: {
            async onDeckDelete() {
                if(this.$refs.deckDeleteForm.validate()) {
                    await DeckModule.delete(this.deck).then(()=>{
                        this.$emit('deleted', 'Колода успешно удалена!');
                    }).catch((error)=>{
                        console.log(error);
                    });
                }
            }
        }
    }
</script>