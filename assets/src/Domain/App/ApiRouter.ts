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
        let result = this.$links.filter((link: Link) => link.name === name).pop();
        if (!result) throw `Url with name ${name} not found`;

        Object.entries(params).forEach(([key, value]) => {
            if(result) result.path = result.path.replace('{' + key + '}', value);
        });

        return result;
    }

    public getRouteByPath(path: string): RouteConfig {
        let result = this.$links.filter((link: Link) => link.path === path).pop();
        if (!result) throw `Url with path ${path} not found`;
        return result;
    }

    public isProtectedRoute(linkToCheck: RouteConfig): boolean {
        if(!linkToCheck.name) linkToCheck = this.getRouteByPath(linkToCheck.path);
        return this.$linkAuthNotRequired.some((link) => linkToCheck.name === link.name);
    }
}

export const ApiRouter = new APIRouter();