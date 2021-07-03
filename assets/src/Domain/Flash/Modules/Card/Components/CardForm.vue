<template>
    <v-form ref="cardForm">
        <v-row justify="center">
            <v-col cols="12" sm="10">
                <v-card-title class="justify-center align-center">
                    <span class="pr-3"><slot name="title"/></span>
                </v-card-title>
            </v-col>
        </v-row>
        <v-flex>
            <v-row justify="center" class="ma-0 pa-0">
                <v-col cols="12" sm="11" class="ma-0 pa-0 mb-2">
                    <control-name v-model="getCard.getLabel()" />
                </v-col>
                <v-col cols="12" sm="11" class="ma-0 pa-0 mb-2">
                  <control-editor v-model="getCard.getFrontData"/>
                </v-col>
                <v-col cols="12" sm="11" class="ma-0 pa-0 mb-2">
                  <control-editor v-model="getCard.getBackData"/>
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
    import ControlEditor from "../../../../App/Components/FormElements/ControlEditor.vue";
    export default {
        name: "CardForm",
        components: {ControlEditor, ControlName, ControlText},
        props: {
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
                  // console.log(this.getCard.getRecords());
                    this.$emit('submitted', this.getCard);
                }
            }
        }
    }
</script>