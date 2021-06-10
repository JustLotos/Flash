<template>
    <v-flex v-if="getItemsId.length > 0 && getItems">
        <v-list class="pa-0 ma-0 transparent">
            <v-list-item v-for="id in page" :key="id" class="pa-0 mt-3 transparent">
                <slot v-if="getItems[id]" name="item" :item="getItems[id]"></slot>
            </v-list-item>
        </v-list>
        <slot name="pagination">
            <v-pagination
                v-if="!!countPages"
                v-model="currentPage"
                :length="countPages"
                :total-visible="countButtonsPagination"
                prev-icon="mdi-menu-left"
                next-icon="mdi-menu-right"
                style="padding-top: 15px"
            ></v-pagination>
        </slot>
    </v-flex>
    <v-flex v-else>
        <v-row justify="center">
            <v-col cols="12"><slot name="empty">Элементов нет</slot></v-col>
        </v-row>
    </v-flex>
</template>
<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";
@Component
export default class ListObjects extends Vue{
    @Prop({required: true }) items;
    @Prop({required: true }) itemsId: Array<number>;
    @Prop({default: 10}) elementsOnPage: any;
    @Prop({default: 7}) countButtonsPagination: any;
    currentPage: number = 1;


    get getItems() { return this.items }
    get getItemsId(): Array<number> {return this.itemsId || []}

    get page () {
        if(this.itemsId && this.itemsId.length > 0) {
            return this.itemsId.slice((this.currentPage - 1) * this.elementsOnPage, this.currentPage * this.elementsOnPage);
        }
    }

    get countPages () {
        if(this.itemsId && this.itemsId.length > this.elementsOnPage) {
            return Math.ceil(this.itemsId.length/this.elementsOnPage);
        }
        return 0;
    }

}
</script>