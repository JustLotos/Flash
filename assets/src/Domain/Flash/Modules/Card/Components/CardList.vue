<template>
    <list-objects
        :items="getCards"
        :items-id="getCardsId"
        pagination="true"
        :elements-on-page="7"
    >
        <template v-slot:item="{item}">
            <card-list-row v-if="item" :card="item"></card-list-row>
        </template>
    </list-objects>
</template>

<script>
    import CardListRow from "./CardListRow";
    import ListObjects from "../../../../App/Components/List/ListObjects.vue";
    export default {
        components: {ListObjects, CardListRow},
        props: {
            cardsId: {
                required: true,
                default: []
            },
            cards: {
                required: true,
                default: {}
            }
        },
        computed: {
            getCards: function () {
                return this.cards || {};
            },
            getCardsId: function () {
                return this.cardsId || [];
            }
        },
    }
</script>