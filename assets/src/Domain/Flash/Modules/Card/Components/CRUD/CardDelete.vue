<template>
    <v-flex xs10 class="justify-center">
        <v-card>
            <v-flex xs10 offset-xs1 class="text-center">
                <v-card-title>Вы действительно хотите удалить карточку?</v-card-title>
                <v-card-actions class="justify-center">
                    <v-btn color="primary" @click="onDelete">Удалить</v-btn>
                </v-card-actions>
            </v-flex>
        </v-card>
    </v-flex>
</template>

<script>
    import Card from "../../Card";
    import {CardModule} from "../../CardModule";
    export default {
        name: "CardDelete",
        props: {
            card: {
              type: Card,
              required: true,
              default: () => new Card()
            },
        },
        methods: {
            async onDelete () {
                let deckId = this.card.getDeck();
                await CardModule.delete(this.card);
                this.$emit('deleted', deckId);
            }
        }
    }
</script>