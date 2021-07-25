<template>
  <v-layout align-center justify-center>
    <v-flex sm10 md8 lg6>
      <v-sheet color="lighten">
        <v-card class="mx-auto">
          <v-card-title>
            <v-icon large lef>mdi-success</v-icon>
            <span>Тестовая форма оплаты</span>
          </v-card-title>
          <v-card-actions>
            <v-list-item class="grow">
              <v-list-item-action>

                  <form ref="paymentForm" id="payment" name="payment" method="post" action="https://sci.interkassa.com/#/paysystemList">
                    <input type="hidden" name="ik_co_id" :value="checkoutId" />
                    <input type="hidden" name="ik_pm_no" :value="orderId" />
                    <input type="hidden" name="ik_cur" value="RUB" />
                    <input type="hidden" name="ik_desc" value="Тестовый платеж" />
                    <input type="hidden" name="ik_exp" :value="getCurrentDate" />
                    <input type="hidden" name="ik_ltm" value="2592000" />
                    <input type="hidden" name="ik_loc" value="ru" />
                    <input type="hidden" name="ik_enc" value="utf-8" />
                    <input type="hidden" name="ik_x_email" :value="email" />

                    <label>Сумма платежа: <input name="ik_am" :value="200" /></label>
                    <v-btn type="submit" @click="pay">Оплатить</v-btn>
                  </form>
              </v-list-item-action>
              <v-row align="center" justify="end"></v-row>
            </v-list-item>
          </v-card-actions>
        </v-card>
      </v-sheet>
    </v-flex>
  </v-layout>
</template>

<script lang="ts">
import {makeid} from "../../../Utils/Helpers";
import {UserModule} from "../../User/UserModule";

export default {
  name: "SaleInteractionPage",
  data: function () {
      return {
        checkoutId: "60eadb421981f556366c5ede", // Id кассы
      };
  },
  computed: {
    orderId: function() { return makeid(10) },
    token: function () { return UserModule.user.accessToken; },
    email: function () { return UserModule.user.email; },
    getCurrentDate: function () {
      let d = new Date();
      let month = d.getMonth() + 2;
      return d.getFullYear() + '-' + month  + '-' + d.getDay() ;
    }
  },
  methods: {
    pay: function () {
      this.$refs.paymentForm.submit()
    }
  }
}
</script>