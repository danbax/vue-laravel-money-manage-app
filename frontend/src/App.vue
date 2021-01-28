<template>
  <div id="app">
    <div v-if="showBottomNavigation">
      <RwvHeader />
      <router-view></router-view>
      <RwvFooter />
    </div>
    <div v-else>
      <RwvHeader />
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import RwvHeader from "@/components/TheHeader";
import RwvFooter from "@/components/TheFooter";
import Vue from "vue";
import VueMaterial from "vue-material";
import "vue-material/dist/vue-material.min.css";
import "vue-material/dist/theme/default.css";
import { mapGetters } from "vuex";

Vue.use(VueMaterial);

export default {
  name: "App",
  computed: {
    ...mapGetters(["isFirstTime", "isAuthenticated"]),
    showBottomNavigation() {
      return !this.isFirstTime && this.isAuthenticated;
    }
  },
  components: {
    RwvHeader,
    RwvFooter
  }
};
</script>

<style></style>
