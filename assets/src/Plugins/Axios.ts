import axios, {AxiosError, AxiosRequestConfig} from "axios";
import { router as Router } from "../Domain/User/Guard";
import { UserModule } from "../Domain/User/UserModule";
import {ApiRouter} from "../Domain/App/ApiRouter";
import Logger from "../Utils/Logger";
import {AppModule} from "../Domain/App/AppModule";

let Axios = axios.create({
    headers: { "Content-type": "application/json" }
});

Axios.interceptors.response.use(
    (response) => {
        return response
    },
    async function (error) {
        debugger
        UserModule.UNSET_LOADING();
        const originalRequest: AxiosRequestConfig = error.config;

        if (error.message === "Network Error") {
            console.log(error);
            // return AppModule.getApp.showCommonModal();
            debugger
        }

        if (
            error.response?.status === 401 &&
            originalRequest.url === ApiRouter.getRouteByName('refreshToken').path
        ) {
            UserModule.LOGOUT();
            return Router.push({name: 'Login'});
        }

        // @ts-ignore
        if (error.response?.status === 401 && !originalRequest._retry) {
            // @ts-ignore
            originalRequest._retry = true;
            // noinspection TypeScriptValidateTypes
            await UserModule.refreshToken();
            originalRequest.headers['Authorization'] = 'Bearer' + UserModule.user.accessToken;
            return Axios(originalRequest);
        }

        Logger.logAPIError(error);
        return Promise.reject(error);
    }
);

Axios.interceptors.request.use(
    config => {
        if (UserModule.isAuthenticated) {
            config.headers['Authorization'] = 'Bearer ' + UserModule.user.accessToken;
        }
        return config;
    },
    error => Promise.reject(error)
);

export default Axios;