<template>
    <v-card>
        <card-form v-if="card" :card="card" :event-name="'update'" @update="update" :errors="updateErrors">
            <template v-slot:title>Редактрирование карточки</template>
            <template v-slot:submit>Сохранить</template>
        </card-form>
    </v-card>
</template>

<script>
    import CardForm from "./CardForm";
    import {cardDefault} from "../../../plugins/helpers";
    import {mapGetters} from "vuex";
    export default {
        name: "CardUpdate",
        components: {CardForm},
        props: {
            card: {
                type: Object,
                required: true
            }
        },
        computed: {
            updateErrors: function () {
                if (this.errors) {
                    return this.errors;
                }
                return cardDefault();
            },
            ...mapGetters('CardStore',{
                errors: 'errorsUpdate',
            })
        },
        methods: {
            async update(card) {
                console.log(card);
                await this.$store.dispatch("CardStore/update", card)
                    .then(()=>{
                        this.$emit('card-update', 'Карточка успешно сохранена!');
                    })
                    .catch((errors)=>{console.log(errors);});
            }
        }
    }
</script>

<style scoped>
</style>