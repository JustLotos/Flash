<template>
    <v-dialog v-model="modal" max-width="700px">
        <v-main>
            <v-layout xs6 justify-center align-center style="position: relative">
                <v-flex xs6 class="justify-center align-center">
                    <v-alert type="success"><slot>Операция выполнена успешно!</slot></v-alert>
                    <v-btn absolute top right icon dark @click="modalToggle">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-flex>
            </v-layout>
        </v-main>
    </v-dialog>
</template>

<script>
    const EVENT_NAME = 'modalChange'.toLowerCase();
    export default {
        name: "SuccessModal",
        props: {
            modal: {
                type: Boolean,
                required: true,
            },
        },
        model: {
            prop: 'modal',
            event: EVENT_NAME
        },
        computed: {
            value: {
                get: function() {
                    return this.modal
                },
                set: function(value) {
                    this.$emit(EVENT_NAME, value)
                }
            }
        },
        methods: {
            modalToggle: function () {
                this.value =  !this.value;
            },
        }
    }
</script>
<style scoped></style>