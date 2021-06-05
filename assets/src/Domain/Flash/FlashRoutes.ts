import {RouteConfig} from "vue-router";
import BaseLayout from "../App/Layouts/BaseLayout.vue";
import MainPage from "./Pages/MainPage.vue";

export const FlashRoutes: Array<RouteConfig> = [
    {
        path: '/collection', name: 'Main', component: MainPage,
        meta: { label: 'Collection', icon: 'mdi-flash', menu: true, auth: true, layout: BaseLayout, sortRate: 1},
    },
    {
        path: '/collection/id', name: 'DeckDetail', component: MainPage,
        meta: { label: 'Collection', icon: 'mdi-flash', menu: true, auth: true, layout: BaseLayout, sortRate: 1},
    }
];