<template>
    <div>
        <template v-if="params.countRepeat < 2">
            <v-item-group>
                <v-btn @click="handle">Плохо</v-btn>
                <v-btn @click="handle">Хорошо</v-btn>
            </v-item-group>
        </template>
        <template v-else-if="params.countRepeat < 5">
            <v-item-group>
                <v-btn @click="handle">Плохо</v-btn>
                <v-btn @click="handle">Нормально</v-btn>
                <v-btn @click="handle">Хорошо</v-btn>
            </v-item-group>
        </template>
        <template v-else>
            <v-item-group>
                <v-btn @click="handle">Плохо</v-btn>
                <v-btn @click="handle">Не очень</v-btn>
                <v-btn @click="handle">Нормально</v-btn>
                <v-btn @click="handle">Хорошо</v-btn>
            </v-item-group>
        </template>
    </div>
</template>

<script>
    export default {
        name: "RatingButtons",
        props: {
            params: {
                type: Object,
                required: true
            }
        },
        methods: {
            handle: function () {
                this.$emit('answer');
            }
        }
    }
</script>