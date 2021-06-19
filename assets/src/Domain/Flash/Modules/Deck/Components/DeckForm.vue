<template>
    <v-form ref="form">
        <v-row justify="center" no-gutters style="width: 600px">
            <v-col v-if="commonError" cols="12" sm="8">
                <v-alert
                    class="mt-5"
                    type="error"
                    transition="fade-transition"
                >{{commonError}}</v-alert>
            </v-col>
            <v-col cols="12" sm="8">
                <control-name
                        v-model="getDeck.name"
                        :error-message="getErrors.name"
                ></control-name>
            </v-col>
            <v-flex v-if="detailSettings.show">
                <v-row justify="center">
                    <v-col cols="12" sm="8">
                        <control-text
                            v-model="getDeck.description"
                            :error-message="getErrors.description">
                        </control-text>
                    </v-col>
                    <v-col cols="12" sm="8">
                        <control-slider
                            v-model:slider="getDeck.limit_repeat"
                            :error-message="getErrors.limit_repeat"
                            :hint="'Количество карточек доступных для повторения в день'"
                        >
                            <template v-slot:label="{value}">
                                <span>Повторение ({{value}})</span>
                            </template>
                        </control-slider>
                    </v-col>
                    <v-col cols="12" sm="8">
                        <control-slider
                            v-model:slider="getDeck.limit_learning"
                            :error-message="getErrors.limit_learning"
                            :hint="'Количество карточек доступных для повторения в день'"
                            :label="'Изучение'"
                        >
                            <template v-slot:label="{value}">
                                <span>Повторение ({{value}})</span>
                            </template>
                        </control-slider>
                    </v-col>
                    <v-col cols="12" sm="8">
                        <control-slider
                            v-model:slider="getDeck.difficulty_index"
                            :error-message="getErrors.difficulty_index"
                            :hint="'Этот коэффициент влияет вобщем на частоту повторения'"
                            :label="'Коэффициент сложности'"
                        >
                            <template v-slot:label="{value}">
                                <span>Коэффициент сложности ({{value}}%)</span>
                            </template>
                        </control-slider>
                    </v-col>
                    <v-col cols="12" sm="8">
                        <control-slider
                            v-model:slider="getDeck.base_index"
                            :error-message="getErrors.base_index"
                            :hint="'Из данного коэффициента рассчитываются периоды повторения'"
                            :label="'Базовый коэффициент'"
                        >
                            <template v-slot:label="{value}">
                                <span>Базовый коэффициент ({{value}})</span>
                            </template>
                        </control-slider>
                    </v-col>
                    <v-col cols="12" sm="9">
                        <control-slider
                            v-model:slider="getDeck.modifier_index"
                            :error-message="getErrors.modifier_index"
                            :hint="'Из данного коэффициента рассчитывается то насколько быстро будет первое повторение'"
                            :label="'Дополнительный коэффициент'"
                        >
                            <template v-slot:label="{value}">
                                <span>Дополнительный коэффициент ({{value}})</span>
                            </template>
                        </control-slider>
                    </v-col>
                </v-row>
            </v-flex>
        </v-row>
        <v-row :justify-sm="'center'" class="mb-2">
            <v-col sm="auto">
                <v-btn
                    elevation="0"
                    @click="toggleDetailSettings"
                    class="mr-2"
                >{{detailSettings.message}}</v-btn>
                <v-btn color="primary" @click="onSubmitForm" :loading="isLoading">
                    <slot name="submit"></slot>
                </v-btn>
            </v-col>
        </v-row>
    </v-form>
</template>

<script>
    import ControlName from "../../../../App/Components/FormElements/ControlName.vue";
    import ControlText from "../../../../App/Components/FormElements/ControlText.vue";
    import ControlSlider from "../../../../App/Components/FormElements/ControlSlider.vue";
    import {Deck} from "../Deck";
    export default {
        name: "DeckForm",
        components: {ControlSlider, ControlText, ControlName},
        props: {
            eventName: {
                type: String,
                required: true
            },
            deck: {
                required: true,
                default: new Deck()
            },
            errors: {
                type: Object,
                default: {}
            },
            commonError: {
                default: false
            },
            isLoading: {
                default: false
            }
        },
        computed: {
            getDeck: function () {
                return this.deck || {};
            },
            getErrors: function () {
                return this.errors || {};
            }
        },
        data: function () {
            return {
                valid: false,
                detailSettings: {
                    show: false,
                    message: 'Подробнее'
                }
            }
        },
        methods: {
            toggleDetailSettings() {
                this.detailSettings.show = !this.detailSettings.show;
                if(this.detailSettings.show) {
                    this.detailSettings.message = 'Скрыть';
                } else {
                    this.detailSettings.message = 'Подробнее';
                }
            },

            onSubmitForm() {
                if (this.$refs.form.validate()) {
                    this.$emit( this.eventName, this.deck);
                }
            }
        }
    }
</script>

<style scoped>
    .centered-input >>> input {
        text-align: center
    }
</style>