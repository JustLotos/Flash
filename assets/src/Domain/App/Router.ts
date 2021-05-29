import Vue from "vue";
import Router, {RouteConfig} from 'vue-router'
import {AppRoutes} from "./AppRoutes";
import {UserRoutes} from "../User/UserRoutes";
import {FlashRoutes} from "../Flash/FlashRoutes";

class VueRouterEx extends Router {
    matcher: any;
    public routes: RouteConfig[] = [];
    constructor(options: any) {
        super(options);
        const { addRoutes } = this.matcher;
        const { routes } = options;
        debugger
        this.routes = routes;

        this.matcher.addRoutes = (newRoutes: any) => {
            this.routes.push(...newRoutes);
            addRoutes(newRoutes);
        };
    }
}

Vue.use(VueRouterEx);

export const routes: Array<RouteConfig> = [
    ...AppRoutes,
    ...UserRoutes,
    ...FlashRoutes,
    // {
    //     path: '*', redirect: '/',
    //     meta: { menu: false, auth: false }
    // }
];


export default new VueRouterEx({
    mode: 'history',
    base: process.env.APP_HOST,
    routes: routes
});


