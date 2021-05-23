import Axios from "../../Plugins/Axios";
import {ApiRouter} from "../App/ApiRouter";
import LoginRequest from "./Entity/API/Login/LoginRequest";
import LoginResponse from "./Entity/API/Login/LoginResponse";
import RegisterByEmailRequest from "./Entity/API/Register/ByEmail/RegisterByEmailRequest";
import RegisterByEmailResponse from "./Entity/API/Register/ByEmail/RegisterByEmailResponse";
import RefreshTokenResponse from "./Entity/API/RefreshToken/RefreshTokenResponse";
import RefreshTokenRequest from "./Entity/API/RefreshToken/RefreshTokenRequest";
import ResetByEmailRequest from "./Entity/API/Reset/ByEmail/ResetByEmailRequest";
import ResetByEmailConfirm from "./Entity/API/Reset/ByEmail/ResetByEmailConfirm";
import User from "./Entity/User";
import {AxiosResponse} from "axios";

export default {
    async login(payloads: LoginRequest): AxiosResponse<LoginResponse> {
        return Axios.post( ApiRouter.getRouteByName('login').path, payloads);
    },
    async registerByEmail(payloads: RegisterByEmailRequest): AxiosResponse<RegisterByEmailResponse> {
        return Axios.post( ApiRouter.getRouteByName('registerByEmail').path, payloads);
    },
    async refreshToken(payloads: RefreshTokenRequest): AxiosResponse<RefreshTokenResponse> {
        return Axios.post( ApiRouter.getRouteByName('refreshToken').path, payloads);
    },
    async resetByEmailRequest(payloads: ResetByEmailRequest): AxiosResponse<any> {
        return Axios.post( ApiRouter.getRouteByName('resetByEmail').path, payloads);
    },
    async resetByEmailConfirm(payloads: ResetByEmailConfirm): AxiosResponse<any> {
        return Axios.post( ApiRouter.getRouteByName('resetByEmailConfirm').path, payloads);
    },
    async confirmEmail(payloads: {email: string}): AxiosResponse<any> {
        return Axios.post( ApiRouter.getRouteByName('resendCodeRegisterByEmail').path, payloads);
    },
    async getCurrentUserInfo(): AxiosResponse<User> {
        return Axios.get( ApiRouter.getRouteByName('getCurrentUser').path);
    },
    async changeEmail(payloads): AxiosResponse<any> {
        return Axios.post( ApiRouter.getRouteByName('changeEmail').path, payloads);
    },
    async changeEmailConfirm(payloads): AxiosResponse<any> {
        let url = ApiRouter
            .getRouteByName('changeEmailConfirm').path
            .replace('{token}', payloads.token);
        return Axios.get(url, payloads);
    },
    async changePassword(payloads): AxiosResponse<any> {
        return Axios.post( ApiRouter.getRouteByName('changePassword').path, payloads);
    },
};
