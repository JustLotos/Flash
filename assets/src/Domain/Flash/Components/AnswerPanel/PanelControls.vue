<template>
    <v-card-actions>
        <v-btn
            v-if="state === INIT"
            @click="changeState(ANSWER)"
        >Показать</v-btn>

        <rating-buttons
            v-else-if="state === ANSWER"
            @answer="changeState(NEXT)"
            :params="params"
        />

        <v-btn
            v-if="next === NEXT"
            @click="changeState(INIT)"
        >Следующая</v-btn>
    </v-card-actions>
</template>

<script>
    import RatingButtons from "./RatingButtons";
    export default {
        name: "PanelControls",
      components: {RatingButtons},
      data: function() {
            return {
                INIT: 'INIT',
                ANSWER: 'ANSWER',
                NEXT: 'NEXT',
                state: this.$data.CLOSE
            }
        },
        methods: {
            changeState(value) {
                this.$data.state = value;
            }
        },
        watch: {
            state(value, prev) {
                if(value === this.$data.INIT) {

                }
            }
        }
    }
</script>