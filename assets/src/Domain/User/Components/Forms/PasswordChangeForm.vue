<template>
  <v-card>
    <v-form ref="changePasswordForm" @submit="submit()">
      <v-row justify="center" align="center" style="position: relative">
        <v-btn fab x-small icon outlined color="red" @click="handleClose()" class="close-positioned"
               style="position: absolute">
          <v-icon small color="red">mdi-close</v-icon>
        </v-btn>

        <v-col cols="10" sm="6" md="8" class="text-center pb-0 pt-5" >
          <span> Изменение пароля </span>
        </v-col>

        <v-col cols="10" sm="6" md="8" class="text-center pb-0 pt-0">
          <control-password
              class="p0 m0"
              v-model="oldPassword"
              :error="errors.password"
              @input="dataErrors.password = ''"
              :label="'Текущий пароль'"
          />
        </v-col>
        <v-col cols="10" sm="6" md="8" class="text-center pb-0 pt-0">
          <control-password
              class="p0 m0"
              v-model="newPassword"
              :error="errors.password"
              @input="dataErrors.password = ''"
              :label="'Новый пароль'"
          />
        </v-col>
        <v-col cols="10" sm="6" md="8" class="text-center pb-0 pt-0">
          <control-confirm
              class="p0 m0"
              v-model="plainPassword"
              :error="errors.plainPassword"
              @input="dataErrors.password = ''"
              :field="newPassword"
              :label="'Повторите новый пароль'"
          />
        </v-col>
        <v-col cols="10" sm="6" md="8" class="text-center pb-2 pt-0">
          <v-btn small outlined color="green" @click="submit()">
            <v-icon small color="green">mdi-check</v-icon> Изменить
          </v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-card>
</template>

<script lang="ts">

import {Component, Prop, Vue} from 'vue-property-decorator';
import ControlEmail from "../../../App/Components/FormElements/ControlEmail.vue";
import {UserModule} from "../../UserModule";
import ControlPassword from "../../../App/Components/FormElements/ControlPassword.vue";
import ControlConfirm from "../../../App/Components/FormElements/ControlConfirm.vue";

@Component({components: {ControlConfirm, ControlPassword, ControlEmail}})
export default class EmailChangeForm extends Vue {
  oldPassword: string = '';
  newPassword: string = '';
  plainPassword: string = '';
  dataErrors: {password: string } = { password: ''};
  notification: boolean = false;

  get errors() { return this.dataErrors }
  set errors(value) { this.dataErrors = value; }

  get showNotification(): boolean {
    return this.notification;
  }

  submit() {
    if(this.$refs.changePasswordForm.validate()) {

      // UserModule.changeEmail(payloads)
      //     .then((data) => {
      //       this.$emit('changedPassword', data)
      //       this.notification = true;
      //     })
      //     .catch((errors) => {
      //       if(errors.response.data.errors) {
      //         this.errors = errors.response.data.errors;
      //         if(errors.response.data.errors.domain?.token) {
      //           // this.errors = {email: errors.response.data.errors.domain?.token};
      //         }
      //       }
      //     });

      this.$emit('submit',  {
        oldPassword: this.oldPassword,
        newPassword: this.newPassword,
        plainPassword: this.plainPassword
      });
    }
  }

  handleClose() { this.$emit('close') }
}
</script>

<style>
.close-positioned {
  position: absolute;
  right: 0;
  top: 0;
  margin-top: 15px;
  margin-right: 30px;
}
</style>