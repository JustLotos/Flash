<template>
  <v-form ref="changeEmailForm" @submit="submit">
    <v-row>
      <control-email class="p0 m0" v-model="email" :error="errors.email" @input="dataErrors.email = ''"/>
      <v-btn v-if="email" fab x-small icon outlined class="mt-5 ml-1" color="green" @click="submit()">
        <v-icon small color="green">mdi-check</v-icon>
      </v-btn>
      <v-btn fab x-small icon outlined class="mt-5 ml-1" color="red" @click="handleClose()">
        <v-icon small color="red">mdi-close</v-icon>
      </v-btn>
    </v-row>
  </v-form>
</template>

<script lang="ts">

import {Component, Prop, Vue} from 'vue-property-decorator';
import ControlEmail from "../../../App/Components/FormElements/ControlEmail.vue";
import {UserModule} from "../../UserModule";

@Component({components: { ControlEmail}})
export default class EmailChangeForm extends Vue {
  email: string = 'test@test.test';
  dataErrors: {email: string } = { email: ''};

  get errors() { debugger; return this.dataErrors }
  set errors(value) { this.dataErrors = value; }

  submit() {
    if(this.$refs.changeEmailForm.validate()) {
      let payloads = { email: this.email };

      UserModule.changeEmail(payloads)
          .then((data) => { this.$emit('changedEmail', data) })
          .catch((errors) => {
            if(errors.response.data.errors) {
              this.errors = errors.response.data.errors;
              if(errors.response.data.errors.domain?.token) {
                this.errors = {email: errors.response.data.errors.domain?.token};
              }
              console.log(this.errors);
            }
          });

      this.$emit('submit', { email: this.email });
    }
  }

  handleClose() { this.$emit('close') }
}
</script>