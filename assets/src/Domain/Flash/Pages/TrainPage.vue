<template>
    <v-flex>
        <v-row justify="center" align="center" class="ma-0 pa-0">
            <v-col cols="12" sm="10" md="8" lg="6">
                <v-card color="primary" class="pt-5 pr-5 pl-5">
                    <v-row justify="center"  no-gutters>
                        <v-col cols="12" sm="12" class="mb-5">
                            <v-sheet color="light" min-height="200px">
                                <v-card-text></v-card-text>
                            </v-sheet>
                        </v-col>
                        <v-row justify="center" no-gutters>
                            <v-divider :inset="true"></v-divider>
                        </v-row>
                        <v-col cols="12" sm="12" class="mt-5">
                            <v-sheet color="light" min-height="200px">
                                <v-card-text v-if="!show"></v-card-text>
                            </v-sheet>
                        </v-col>
                    </v-row>

                    <answer-panel/>
                </v-card>
            </v-col>
        </v-row>
    </v-flex>
</template>

<script lang="ts">
import AnswerPanel from "../Components/AnswerPanel/AnswerPanel.vue";
export default {
    name: "TrainPage",
    components: {AnswerPanel},
}
</script>