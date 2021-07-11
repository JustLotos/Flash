import {RouteConfig} from "vue-router";
import BaseLayout from "../App/Layouts/BaseLayout.vue";
import SaleSuccessPage from "../Sale/Pages/SaleSuccessPage.vue";
import SaleFailPage from "../Sale/Pages/SaleFailPage.vue";
import SaleWaitPage from "../Sale/Pages/SaleWaitPage.vue";
import SaleInteractionPage from "../Sale/Pages/SaleInteractionPage.vue";
import SaleCheckPage from "../Sale/Pages/SaleCheckPage.vue";

export const SaleRoutes: Array<RouteConfig> = [
    {
        path: '/sale/success', name: 'saleSuccess', component: SaleSuccessPage,
        meta: { label: 'Успешная оплата', icon: 'mdi-face', menu: false, auth: true, layout: BaseLayout},
    },
    {
        path: '/sale/fail', name: 'saleFail', component: SaleFailPage,
        meta: { label: 'Неудачная оплата', icon: 'mdi-face', menu: false, auth: true, layout: BaseLayout},
    },
    {
        path: '/sale/wait', name: 'saleWait', component: SaleWaitPage,
        meta: { label: 'Подожидите пока выполняется оплата', icon: 'mdi-face', menu: false, auth: true, layout: BaseLayout},
    },
    {
        path: '/sale/interaction', name: 'saleInteraction', component: SaleInteractionPage,
        meta: { label: 'Страница взаимодействия', icon: 'mdi-face', menu: false, auth: true, layout: BaseLayout},
    },
    {
        path: '/sale/check', name: 'saleCheck', component: SaleCheckPage,
        meta: { label: 'Чеки', icon: 'mdi-face', menu: false, auth: true, layout: BaseLayout},
    },
];