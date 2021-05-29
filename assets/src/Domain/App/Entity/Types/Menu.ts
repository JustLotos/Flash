import { RouteConfig } from "vue-router";
import { UserModule } from "../../../User/UserModule";
import {mdiGithub} from "@mdi/js";

export class Menu {
    private _routes: Array<RouteConfig>;

    constructor() {
        this._routes = [];
    }

    public setRoutes(routes: Array<RouteConfig>) {
        this._routes = routes;
    }

    get getNavMenu(): Array<RouteConfig> {
        let routes = this._routes.filter((item: RouteConfig)=>{
            if(!item.meta.sortRate) item.meta.sortRate = 0;

            if(UserModule.isAuthenticated) {
                return item.meta.menu && item.meta.auth;
            }
            return item.meta.menu && !item.meta.auth;
        });

        return routes.sort(Menu.sortMenu);
    }

    get getFooterMenu(): Array<RouteConfig> {
        return [
            {path: 'https://github.com/JustLotos/flash.back', name: 'gitHub', meta: {icon: mdiGithub, label: 'github'}}
        ];
    }

    private static sortMenu (prev: any, next: any) {
        return next.meta?.sortRate - prev.meta?.sortRate || 0
    };
}