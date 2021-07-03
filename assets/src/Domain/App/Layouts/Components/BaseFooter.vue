<template>
    <v-footer app padless class="hidden">
        <v-row class="flex primary">
            <v-col class="text-center">
                <v-btn
                    class="mx-4"
                    icon text
                    color="white"
                    v-for="item in footerMenu"
                    :key="item.path"
                    :to="item.path"
                >
                    <v-icon size="24px">{{ item.meta.icon }}</v-icon>
                </v-btn>
                <label for="selectLocale"></label>
                <select id="selectLocale" style="color: white" v-model="selectLocale" @change="setLocale">
                    <option style="color: black"
                            v-for="(lang, i) in localeList"
                            :key="`Lang${i}`"
                            :value="lang.value"
                    >{{ lang.label }}</option>
                </select>
            </v-col>

            <modal v-model="modal"><v-alert type="error">ой что-то пошло не так</v-alert></modal>
        </v-row>
    </v-footer>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { AppModule } from "../../AppModule";
import { RouteConfig } from "vue-router";
import Modal from "../../Components/Modal/Modal.vue";
import App from "../../../../App.vue";
@Component({
  components: {Modal}
})
export default class BaseFooter extends Vue {
    footerMenu: Array<RouteConfig> = AppModule.getApp.menu.getFooterMenu;
    localeList: Array<Object> = AppModule.getApp.locale.getLocaleList;
    selectLocale: string = this.$root.$i18n.locale;
    get modal():boolean {return AppModule.getApp.commonModal}
    set modal(value: boolean) { AppModule.getApp.commonModal = value }

    public setLocale() {
        this.$root.$i18n.locale = this.selectLocale;
        AppModule.getApp.locale.language = this.selectLocale;
        this.$router.go(0);
    }
}
</script>