<template>
  <div class="auth-page">
    <div class="container page">
      <md-card class="card">
        <md-ripple>
          <md-card-header>
            <div class="md-title">Sign up</div>
          </md-card-header>

          <md-card-content>
            <p class="text-xs-center">
              <router-link :to="{ name: 'login' }">
                Have an account?
              </router-link>
            </p>

            <form @submit.prevent="onSubmit">
              <md-field>
                <label>Email</label>
                <md-input v-model="email" type="text"></md-input>
              </md-field>
              <md-field>
                <label>Password</label>
                <md-input v-model="password" type="password"></md-input>
              </md-field>
              <md-button class="md-raised md-primary" @click="onSubmit">
                Sign up
              </md-button>

              <div v-if="errors" class="error-messages">
                {{ errors }}
              </div>
            </form>
          </md-card-content>
        </md-ripple>
      </md-card>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import { REGISTER } from "@/store/actions.type";

export default {
  name: "RwvRegister",
  data() {
    return {
      email: "",
      password: ""
    };
  },
  computed: {
    ...mapState({
      errors: state => state.auth.errors
    })
  },
  methods: {
    onSubmit() {
      this.$store
        .dispatch(REGISTER, {
          email: this.email,
          password: this.password
        })
        .then(() => this.$router.push({ name: "home" }));
    }
  }
};
</script>

<style scoped>
.card {
  width: 80%;
  margin-right: auto;
  margin-left: auto;
  text-align: center;
}

.content {
  margin-top: 15px;
  text-align: center;
}
</style>
