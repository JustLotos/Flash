<template>
  <v-form ref="changeEmailForm" @submit="submit()">
    <v-row v-if="!showNotification">
      <control-email class="p0 m0" v-model="email" :error="errors.email" @input="dataErrors.email = ''"/>
      <v-btn v-if="email" fab x-small icon outlined class="mt-5 ml-1" color="green" @click="submit()">
        <v-icon small color="green">mdi-check</v-icon>
      </v-btn>
      <v-btn fab x-small icon outlined class="mt-5 ml-1" color="red" @click="handleClose()">
        <v-icon small color="red">mdi-close</v-icon>
      </v-btn>
    </v-row>

    <span v-else>
      Проверьте ваш почтовый ящик
      <v-btn x-small depressed outlined fab icon @click="submit()" class="ml-2">
        <v-icon>mdi-email-sync</v-icon>
      </v-btn>
    </span>
  </v-form>
</template>

<script lang="ts">

import {Component, Prop, Vue} from 'vue-property-decorator';
import ControlEmail from "../../../App/Components/FormElements/ControlEmail.vue";
import {UserModule} from "../../UserModule";

@Component({components: { ControlEmail}})
export default class EmailChangeForm extends Vue {
  email: string = '';
  dataErrors: {email: string } = { email: ''};
  notification: boolean = false;

  get errors() { return this.dataErrors }
  set errors(value) { this.dataErrors = value; }

  get showNotification(): boolean {
    return this.notification;
  }

  submit() {
    if(this.$refs.changeEmailForm.validate()) {
      let payloads = { email: this.email };

      UserModule.changeEmail(payloads)
          .then((data) => {
            this.$emit('changedEmail', data)
            this.notification = true;
          })
          .catch((errors) => {
            if(errors.response.data.errors) {
              this.errors = errors.response.data.errors;
              if(errors.response.data.errors.domain?.token) {
                this.errors = {email: errors.response.data.errors.domain?.token};
              }
            }
          });

      this.$emit('submit', { email: this.email });
    }
  }

  handleClose() { this.$emit('close') }
}
</script>