<template>
  <v-layout align-center justify-center>
    <v-flex sm10 md8 lg6>
      <v-sheet elevation="10">
        <v-toolbar color="primary" dark flat>
            <v-toolbar-title>
              <v-icon>mdi-face</v-icon>
              Профиль пользователя
            </v-toolbar-title>
        </v-toolbar>
        <v-row justify="center" class="mt-5 pb-5">
          <v-simple-table>
            <template v-slot:default>
              <tbody>
              <tr>
                <td>Email: </td>
                <td>
                  <span>{{ user.email }}
                    <v-icon v-if="isConfirmed()" color="green" class="mb-1">mdi-account-check-outline </v-icon>
                    <v-btn v-else @click="confirmEmail" :loading="isConfirmLoading()" class="ml-2" small outlined>Подтвердить</v-btn>
                  </span>
                  <span v-if="isSuccessConfirmed() || isErrorConfirmed()">Проверьте ваш почтовый ящик</span>
                  <v-btn @click="updateEmail" small depressed outlined class="ml-2">Изменить</v-btn>
                </td>
              </tr>
              <tr>
                <td>Пароль:</td>
                <td>
                  <span> ******* </span>
                  <v-btn @click="updatePassword" small depressed outlined class="ml-2" >Изменить</v-btn>
                </td>
              </tr>
              <tr>
                <td>Статус: </td>
                <td>{{ getStatus() }}</td>
              </tr>
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
import {AxiosError} from "axios";

@Component({ components: {Modal} })
export default class ProfilePage extends Vue{
    user: User = UserModule.user;
    confirmEmailModal: boolean = false;
    confirmEmailErrorModal: boolean = false;
    confirmModalErrorMessage: string = 'Ой что-то пошло не так';
    confirmLoading = false;
    confirmRequestStatus: string = 'NOT_REQUESTED';

    isConfirmed(): boolean { return UserModule.user.isConfirmed() }
    isConfirmLoading() { return this.confirmLoading }
    isSuccessConfirmed(): boolean { return this.confirmRequestStatus === 'REQUESTED_SUCCESS'}
    isErrorConfirmed(): boolean { return this.confirmRequestStatus === 'REQUESTED_ERROR'}
    getStatus() { return UserModule.user.getFormattedStatus()}

    updatePassword() {
      console.log('updatePassword');
    }

    updateEmail() {
      console.log('updateEmail');
    }

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
      next();
    }
}
</script>