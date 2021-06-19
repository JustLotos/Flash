<template>
    <v-form ref="cardForm" class="ma-0">
        <v-row justify="center" class="ma-0 pa-0">
            <v-col cols="12" sm="10" class="ma-0 pa-0">
                <v-card-title class="justify-center align-center pa-0">
                    <span class="pr-3"><slot name="title"/></span>
                </v-card-title>
            </v-col>
        </v-row>
        <v-flex>
            <v-row justify="center" class="ma-0 pa-0">
                <v-col cols="12" sm="11" class="ma-0 pa-0 mb-2">
                    <control-text v-model="getCard.getFrontData" />
                </v-col>
                <v-col cols="12" sm="11" class="ma-0 pa-0">
                    <control-text v-model="getCard.getBackData" />
                </v-col>
            </v-row>
        </v-flex>
        <v-row justify="center" class="ma-0 pa-0">
            <v-col sm="auto" class="ma-3 pa-0">
                <v-btn color="primary" @click="onSubmitForm" :loading="isLoading">
                    <slot name="submit"></slot>
                </v-btn>
            </v-col>
        </v-row>
    </v-form>
</template>

<script lang="ts">
    import ControlName from "../../../../App/Components/FormElements/ControlName.vue";
    import ControlText from "../../../../App/Components/FormElements/ControlText.vue";
    import {AppModule} from "../../../../App/AppModule";
    import Card from "../Card";
    export default {
        name: "CardForm",
        components: {ControlName, ControlText},
        props: {
            eventName: {
                type: String,
                required: true,
                default: 'submitted'
            },
            card: {
                type: Card,
                default: () => new Card(),
            },
            errors: {
                type: Object,
                default: () => {}
            },
        },
        data: function () {
            return {
                valid: false
            }
        },
        computed: {
            isLoading: function () {
                return AppModule.isLoading || false;
            },
            getCard: function (): Card {
                return this.card
            }
        },
        methods: {
            onSubmitForm() {
                if (this.$refs.cardForm.validate()) {
                    this.$emit(this.eventName, this.getCard);
                }
            }
        }
    }
</script>