import {Device} from "./Types/Device";
import {Locale} from "./Types/Locale";
import {Menu} from "./Types/Menu";
import {RouteConfig} from "vue-router";

export class Application {
    private _device: Device;
    private _locale: Locale;
    private _menu: Menu;
    private _commonModal: boolean;
    private readonly _logo: RouteConfig;

    constructor() {
        this._device = new Device();
        this._locale = new Locale();
        this._menu = new Menu();
        this._commonModal = false;
        this._logo = { path: '/', name: 'logo', meta: { label: 'FlashBack', icon: 'mdi-home'}};
    }

    public showCommonModal(): boolean {
        this.commonModal = true;
        return true;
    }

    get commonModal(): boolean{
        return this._commonModal;
    }

    set commonModal(value: boolean) {
        this._commonModal = value;
    }

    get logo(): RouteConfig {
        return this._logo;
    }

    get locale(): Locale {
        return this._locale;
    }

    set locale(value: Locale) {
        this._locale = value;
        return this;
    }

    get device(): Device {
        return this._device;
    }
    set device(value: Device) {
        this._device = value;
        return this;
    }

    get menu(): Menu {
        return this._menu;
    }

    set menu(value: Menu) {
        this._menu = value;
        return this;
    }
}