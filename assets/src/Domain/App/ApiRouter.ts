import {RouteConfig} from "vue-router";

class APIRouter {
    private $links: Array<RouteConfig>;
    private $linkAuthNotRequired: Array<RouteConfig>;

    constructor() {
        this.$links = [];
        this.$linkAuthNotRequired = [];
        if (localStorage.getItem('routes')) {
            this.$links = <Array<RouteConfig>>JSON.parse(<string>localStorage.getItem('routes'));
            this.$linkAuthNotRequired = this.$links.filter((link:RouteConfig) => !link.meta.auth);
        }
    }

    public getRouteByName(name: string, params = {}): RouteConfig {
        let searchRoute = this.$links.filter((link: any) => link.name === name).pop();
        if (!searchRoute) throw `Url with name ${name} not found`;

        Object.entries(params).forEach(([key, value]) => {
            if(searchRoute) {
                searchRoute = Object.assign({}, searchRoute);
                // @ts-ignore
                searchRoute.path = searchRoute.path.replace('{' + key + '}', value);
            }
        });

        console.log(searchRoute);
        return searchRoute;
    }

    public getRouteByPath(path: string): RouteConfig {
        let result = this.$links.filter((link: any) => link.path === path).pop();
        if (!result) throw `Url with path ${path} not found`;
        return result;
    }

    public isProtectedRoute(linkToCheck: RouteConfig): boolean {
        if(!linkToCheck.name) linkToCheck = this.getRouteByPath(linkToCheck.path);
        return this.$linkAuthNotRequired.some((link) => linkToCheck.name === link.name);
    }
}

export const ApiRouter = new APIRouter();