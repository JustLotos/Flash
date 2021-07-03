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

          <span v-if="!getCard.isReadyForLearn()">+</span>
          <span v-else>-</span>

        </v-btn>
    </v-hover>
</template>

<script lang="ts">
    import Card from "../Card";

    export default {
        name: "CardListRow",
        props: {
            card: {
              type: Card,
              required: true,
              default: () => new Card()
            }
        },
        computed: {
            getCard: function () : Card { return this.card; },
            getCardTitle: function () {
                return this.getCard.getLabel().slice(0 , 25);
            },
            getNextTimeRepeat: function () {
              return ''
            }
        },
        methods: {
            getLink: function () {
                return { name: 'CardDetail', params: {id: this.getCard.getId()} };
            }
        }
    }
</script>