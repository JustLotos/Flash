import {RouteConfig} from "vue-router";
import BaseLayout from "../App/Layouts/BaseLayout.vue";
import MainPage from "./Pages/MainPage.vue";
import DeckDetailPage from "./Pages/DeckDetailPage.vue";

export const FlashRoutes: Array<RouteConfig> = [
    {
        path: '/collection/', name: 'Collection', component: MainPage,
        meta: { label: 'Коллекция', icon: 'mdi-flash', menu: true, auth: true, layout: BaseLayout, sortRate: 1},
    },
    {
        path: '/collection/:id', name: 'DeckDetail', component: DeckDetailPage,
        meta: { label: 'DeckDetail', icon: 'mdi-flash', menu: false, auth: true, layout: BaseLayout, sortRate: 1},
    },
    {
        path: '/card/:id', name: 'CardDetail', component: DeckDetailPage,
        meta: { label: 'CardDetail', icon: 'mdi-flash', menu: false, auth: true, layout: BaseLayout, sortRate: 1},
    }
];