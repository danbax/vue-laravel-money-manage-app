<template>
  <div>
    <md-toolbar class="md-primary">
      <h3 class="md-title">Money manage</h3>
    </md-toolbar>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "RwvHeader",
  computed: {
    ...mapGetters(["currentUser", "isAuthenticated"])
  }
};
</script>
