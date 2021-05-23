import {VuexModule, Module, Mutation, getModule, Action} from 'vuex-module-decorators'
import {Store} from "./Store";
import {Application} from "./Entity/Application";
import {RouteConfig} from "vue-router";
import LoginRequest from "../User/Entity/API/Login/LoginRequest";
import LoginResponse from "../User/Entity/API/Login/LoginResponse";
import AuthService from "../User/UserService";

@Module({dynamic: true, store: Store, name: 'AppModule' , namespaced: true})
class VuexApplication extends VuexModule {
    private app: Application = new Application();
    loadingFlag: boolean = false;

    get isLoading(): boolean { return this.loadingFlag }

    @Mutation
    LOADING() { this.loadingFlag = true }
    @Mutation
    UNSET_LOADING() { this.loadingFlag = false }
    @Action({ rawError: true })
    public loading() { this.LOADING() }
    @Action({ rawError: true })
    public unsetLoading() { this.UNSET_LOADING() }

    get getApp(): Application {
        return this.app;
    }
    get isResetValidation() {
        return false;
    }
    get getRedirectToUnAuth() {
        return {name: 'Login'};
    }
    get getRedirectToAuth() {
        return {name: 'Dashboard'};
    }

    @Mutation
    public INIT(routes: Array<RouteConfig>) {
        this.app.menu.setRoutes(routes);
    }
}

export const AppModule = getModule(VuexApplication);