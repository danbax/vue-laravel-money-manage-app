<template>
  <md-card class="card">
    <md-ripple>
      <md-card-header>
        <div class="md-title">Sign in</div>
      </md-card-header>

      <md-card-content>
        <p class="text-xs-center">
          <router-link :to="{ name: 'register' }">
            Need an account?
          </router-link>
        </p>

        <form @submit.prevent="onSubmit(email, password)">
          <md-field>
            <label>Email</label>
            <md-input v-model="email" type="text"></md-input>
          </md-field>
          <md-field>
            <label>Password</label>
            <md-input v-model="password" type="password"></md-input>
          </md-field>
          <md-button
            class="md-raised md-primary"
            @click="onSubmit(email, password)"
          >
            Sign iN
          </md-button>

          <div v-if="errors" class="error-messages">
            {{ errors }}
          </div>
        </form>
      </md-card-content>
    </md-ripple>
  </md-card>
</template>

<script>
import { mapState } from "vuex";
import { LOGIN } from "@/store/actions.type";

export default {
  name: "RwvLogin",
  data() {
    return {
      email: null,
      password: null
    };
  },
  methods: {
    onSubmit(email, password) {
      this.$store
        .dispatch(LOGIN, { email, password })
        .then(() => this.$router.push({ name: "home" }));
    }
  },
  computed: {
    ...mapState({
      errors: state => state.auth.errors
    })
  }
};
</script>

<style scoped>
.card {
  width: 80%;
  margin-right: auto;
  margin-left: auto;
  margin-top: 20px;
  text-align: center;
}

.content {
  margin-top: 15px;
  text-align: center;
}
</style>
