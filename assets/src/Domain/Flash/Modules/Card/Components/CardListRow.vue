<template>
    <v-hover open-delay="0.3s" v-slot:default="{ hover }">
        <v-btn
            block
            depressed
            x-large
            light
            class="mb-1 pa0"
            :elevation="hover ? 24 : 0"
            :class="{ 'on-hover': hover }"
            :to="getLink()"
        >
          {{getCardTitle}}
        </v-btn>
    </v-hover>
</template>

<script>
    export default {
        name: "CardListRow",
        props: {
            card: {
              required: true,
              default: {}
            }
        },
        computed: {
            getCard: function () { return this.card; },
            getCardTitle: function () {
                return this.getCard.id.slice(0 , 25);
            }
        },
        methods: {
            getLink: function () {
                return { name: 'CardDetail', params: {id: this.getCard.id} };
            }
        }
    }
</script>