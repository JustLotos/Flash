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
    export default {
        name: "CardDelete",
        props: {
            id: '',
        },
        methods: {
            async onDelete () {
                //await this.$store.dispatch("DeckStore/delete", this.deck);
                this.$emit('card-deleted', false);
            }
        }
    }
</script>

<style scoped></style>