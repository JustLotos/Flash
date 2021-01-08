<template>
  <v-layout align-center justify-center>
    <v-flex sm10 md8 lg6>
      <v-sheet elevation="10">
        <v-toolbar color="primary" dark flat>
            <v-toolbar-title><v-icon>mdi-face</v-icon>Профиль пользователя</v-toolbar-title>
        </v-toolbar>
        <v-row justify="center" class="mt-5 pb-5">
          <v-simple-table>
            <template v-slot:default>
              <tbody>
              <tr>
                <td>
                  <span v-if="!showEmailU">
                    <v-icon v-if="isConfirmed()" color="green" class="mb-1">mdi-account-check-outline </v-icon>
                    <v-icon v-else color="red" class="mb-1">mdi-account-check-outline </v-icon>
                    Email:
                  </span>
                  <span v-else>Изменение</span>
                </td>
                <td>
                  <span v-if="!showEmailU && !changeEmail">
                    <span>
                      <span>{{ user.email }}</span>
                      <v-btn v-if="!isConfirmed" @click="confirmEmail" :loading="isConfirmLoading" class="ml-2" small outlined>Подтвердить</v-btn>
                       <span v-if="isSuccessConfirmed">Проверьте ваш почтовый ящик</span>
                    </span>
                    <v-btn x-small depressed outlined fab class="ml-2" icon @click="showEmailU = !showEmailU">
                      <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                  </span>

                  <email-change-form v-else @close="showEmailU = !showEmailU" @changedEmail="updateEmail()"/>
                </td>
              </tr>
              <tr>
                <td>Пароль:</td>
                <td class="d-flex justify-space-between pt-3 ">
                  <span> ******* </span>
<!--                  <v-icon color="green" class="mb-1">mdi-account-check-outline </v-icon>-->
                </td>
              </tr>
              <tr><td>Статус: </td><td>{{ getStatus() }}</td></tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-row>
      </v-sheet>
    </v-flex>

    <modal v-model="confirmEmailModal" ><v-alert type="success">"Проверьте почту"</v-alert></modal>
    <modal v-model="confirmEmailErrorModal" ><v-alert type="error">{{ confirmModalErrorMessage }}</v-alert></modal>
  </v-layout>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import User from "../Entity/User";
import {UserModule} from "../UserModule";
import Modal from "../../App/Components/Modal/Modal.vue";
import ControlPassword from "../../App/Components/FormElements/ControlPassword.vue";
import ControlEmail from "../../App/Components/FormElements/ControlEmail.vue";
import EmailChangeForm from "../Components/Forms/EmailChangeForm.vue";
import Router from "../../App/Router";
import {RawLocation} from "vue-router/types/router";

@Component({ components: {EmailChangeForm, ControlEmail, ControlPassword, Modal} })
export default class ProfilePage extends Vue{
    user: User = UserModule.user;
    confirmEmailModal: boolean = false;
    confirmEmailErrorModal: boolean = false;
    confirmModalErrorMessage: string = 'Ой что-то пошло не так';
    confirmLoading = false;
    confirmRequestStatus: string = 'NOT_REQUESTED';

    showEmailU: boolean = false;
    changeEmailR: boolean = false
    get changeEmail() { return this.changeEmailR; }

    isConfirmed(): boolean { return UserModule.user.isConfirmed() }
    get isConfirmLoading(): boolean { return this.confirmLoading }
    get isSuccessConfirmed(): boolean { return this.confirmRequestStatus === 'REQUESTED_SUCCESS'}
    getStatus(): string { return UserModule.user.getFormattedStatus()}

    updatePassword() { console.log('updatePassword') }
    updateEmail() { this.changeEmailR = true; }

    confirmEmail() {
      this.confirmLoading = true;
      UserModule.confirmEmail().then((res) => {
          if(res.success) this.confirmEmailModal = true;
          this.confirmRequestStatus = 'REQUESTED_SUCCESS';
      }).catch(({response}) => {
          const errors = response.data.errors;
          if(errors?.domain?.token) {
            this.confirmModalErrorMessage = errors.domain.token;
          }
          this.confirmEmailErrorModal = true;
          this.confirmRequestStatus = 'REQUESTED_ERROR';
      }).finally(() => {
          this.confirmLoading = false;
      })
    }

    beforeRouteEnter (to, from, next) {
      const registerByEmail = to.query?.registerByEmail;
      if (registerByEmail === "alreadyConfirm" || registerByEmail === "confirm") {
        UserModule.updateCurrentUserInfo();
      }
      const changeEmail = to.query?.changeEmail;
      if(changeEmail && changeEmail === 'Y') {
        UserModule.changeEmailConfirm({ token: to.query?.token })
        .then(() => {
          setTimeout( () => Router.replace(<RawLocation>{'query': null}), 1000);
        }).catch((e) => {
          console.log(e);
        })
      }
      next();
    }


}
</script>