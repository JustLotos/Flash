<template>
  <v-form ref="changeEmailForm" @submit="submit">
    <v-row>
      <control-email v-model="email" :error="emailError"/>
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

@Component({components: { ControlEmail}})
export default class EmailChangeForm extends Vue {
  email: string = '';
  @Prop() error: {email: string};

  get emailError() { return this.error.email }

  handleClose() {
    this.$emit('close');
  }

  submit() {
    debugger

    if(this.$refs.changeEmailForm.validate()) {
      this.$emit('submit', { email: this.email });
    }
  }
}
</script>