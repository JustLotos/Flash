<template>
    <v-form ref="form">
        <v-row justify="center" no-gutters style="width: 560px">
            <v-col v-if="commonError" cols="12" sm="8">
                <v-alert
                    class="mt-5"
                    type="error"
                    transition="fade-transition"
                >{{commonError}}</v-alert>
            </v-col>
            <v-col cols="12" sm="8">
                <control-name
                        v-model="deck.name"
                        :error-message="errors.name"
                ></control-name>
            </v-col>
            <v-flex v-if="detailSettings.show">
                <v-row justify="center">
                    <v-col cols="12" sm="8">
                        <control-text
                            v-model="deck.description"
                            :error-message="errors.description">
                        </control-text>
                    </v-col>
                    <v-col cols="12" sm="8">
                        <control-slider
                            v-model:slider="deck.limit_repeat"
                            :error-message="errors.limit_repeat"
                            :hint="'Количество карточек доступных для повторения в день'"
                        >
                            <template v-slot:label="{value}">
                                <span>Повторение ({{value}})</span>
                            </template>
                        </control-slider>
                    </v-col>
                    <v-col cols="12" sm="8">
                        <control-slider
                            v-model:slider="deck.limit_learning"
                            :error-message="errors.limit_learning"
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
                            v-model:slider="deck.difficulty_index"
                            :error-message="errors.difficulty_index"
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
                            v-model:slider="deck.base_index"
                            :error-message="errors.base_index"
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
                            v-model:slider="deck.modifier_index"
                            :error-message="errors.modifier_index"
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
        <v-row :justify-sm="'center'">
            <v-col sm="auto">
                <v-btn
                    elevation="0"
                    @click="toggleDetailSettings"
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
    import {cloneObject} from "../../../../../Utils/Helpers";
    export default {
        name: "DeckForm",
        components: {ControlSlider, ControlText, ControlText, ControlName},
        props: {
            eventName: {
                type: String,
                required: true
            },
            deck: {},
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
                    this.$emit( this.eventName, this.$refs.form);
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