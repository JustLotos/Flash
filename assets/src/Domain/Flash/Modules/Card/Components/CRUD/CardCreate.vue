<template>
    <v-card>
        <card-form @submitted="create" :errors="errors">
            <template v-slot:title>Добавление новой карточки</template>
            <template v-slot:submit>Добавить</template>
        </card-form>
    </v-card>
</template>

<script lang="ts">
    import CardForm from "../CardForm";
    import {CardModule} from "../../CardModule";
    import Card from "../../Card";
    import {Deck} from "../../../Deck/Deck";
    import CardByDeckDTO from "../../../../DTO/CardByDeckDTO";
    export default {
        name: "CardCreate",
        components: {CardForm},
        props: {
            deck: {
                required: true,
                default: () => {}
            }
        },
        data: function () {
            return {
                errors: {}
            }
        },
        computed: {
            getDeck: function (): Deck { return this.deck },
            getErrors: function () {
                return this.errors || {};
            }
        },
        methods: {
            async create(card: Card) {
                await CardModule.add(new CardByDeckDTO(this.getDeck, card));
                this.$emit('created', 'Карточка успешно создана!');
            }
        }
    }
</script>