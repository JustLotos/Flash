<template>
  <v-toolbar-items class="hidden-sm-and-down">
    <v-list class="d-flex" color="primary pt-0 pb-0">
      <router-link
        v-for="link of menu"
        :key="link.name"
        color="secondary"
        :to="{ name: link.name}"
        class="navbar-link d-flex justify-center align-center pr-3 pl-3"
        active-class="navbar-link--active"
        exact
      >
        <span v-if="!(link.children && link.children.length)">
          <v-icon class="mr-3">{{ link.meta.icon }}</v-icon>{{ link.meta.label }}
        </span>

        <v-menu v-else offset-y open-on-hover>
          <template v-slot:activator="{ on, attrs }">
            <span v-bind="attrs" v-on="on">
              <v-icon class="mr-3">{{ link.meta.icon }}</v-icon>{{ link.meta.label }}
            </span>
          </template>
          <v-list style="margin-top: 20px" color="primary">
            <v-list-item v-for="(second, index) in link.children" :key="index">
              <router-link
                  class="navbar-link d-flex justify-center align-center pr-3 pl-3"
                  active-class="navbar-link--active"
                  :to="{ name: second.name}"
              >
                <v-icon class="mr-3">{{ second.meta.icon }}</v-icon>{{ second.meta.label }}
              </router-link>
            </v-list-item>
          </v-list>
        </v-menu>
      </router-link>
    </v-list>
  </v-toolbar-items>
</template>

<script lang="ts">
import { Component, Vue} from "vue-property-decorator";
import { RouteConfig } from "vue-router";
import { AppModule } from "../../AppModule";
import Router from "../../Router";

@Component
export default class Navbar extends Vue {
    get menu(): Array<RouteConfig> { return AppModule.getApp.menu.getNavMenu; }
}
</script>
<style>
  .navbar-link{
    color: white !important;
    text-decoration: none;
    font-size: 16px;
    vertical-align: middle;
  }
  .navbar-link--active {
    background-color: #479AEB;
  }
</style>