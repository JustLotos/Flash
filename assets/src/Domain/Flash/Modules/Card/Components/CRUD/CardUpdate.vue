<template>
    <v-card class="m-5" style="min-width: 600px">
        <card-form :card="card" :errors="getErrors" @submitted="update">
            <template v-slot:title>Редактрирование карточки</template>
            <template v-slot:submit>Сохранить</template>
        </card-form>
    </v-card>
</template>

<script lang="ts">
    import CardForm from "../CardForm";
    import Card from "../../Card";
    import {CardModule} from "../../CardModule";
    export default {
        name: "CardUpdate",
        components: {CardForm},
        props: {
            card: {
                type: Card,
                required: true,
                default: () => new Card()
            }
        },
        computed: {
            getErrors: function () { return {} },
        },
        methods: {
            async update(card: Card) {
                await CardModule.update(card).catch(() => {})
                this.$emit('updated', 'Карточка успешно Обновлена!');
            }
        }
    }
</script>