<template>
<div class="center"  v-if="isLoading">
<md-progress-spinner class="md-accent" :md-diameter="100" md-mode="indeterminate"></md-progress-spinner>
</div>
<div v-else>
  <div v-if="isFirstTime">
    <FirstTime />
  </div>
  <div class="home-page" v-else>
    <div
      class="md-layout md-gutter md-alignment-center"
      style="margin-top:15px"
    >
      <div
        class="md-layout-item md-medium-size-33 md-small-size-50 md-xsmall-size-100"
      >
        <md-card class="md-primary status-card" md-with-hover>
          <md-card-header>
            <md-card-header-text>
              <div class="md-title">Current amount of money</div>
              <div class="status-sub">{{ currentUser.currentAmount }}</div>
            </md-card-header-text>
          </md-card-header>
        </md-card>
      </div>
      <div
        class="md-layout-item md-medium-size-33 md-small-size-50 md-xsmall-size-100"
      >
        <md-card class="md-primary status-card" md-with-hover>
          <md-card-header>
            <md-card-header-text>
              <div class="md-title">You can spend today</div>
              <div class="status-sub">{{ canSpendToday }} ({{daysTillIncome}} days till income)</div>
            </md-card-header-text>
          </md-card-header>
        </md-card>
      </div>
      <CategoryExpenses/>
    </div>
  </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import {
  FETCH_USER_DATA
} from "@/store/actions.type";
import FirstTime from "@/views/FirstTime";
import CategoryExpenses from "@/components/CategoryExpenses";

export default {
  name: "home",
  created() {
    try {
      this.$store.dispatch(FETCH_USER_DATA);
    } catch (err) {
      this.$router.push({ name: "login" });
    }
  },
  mounted() {
    console.log(this.isFirstTime);
  },
  components: {
    FirstTime,
    CategoryExpenses
  },
  computed: {
    ...mapGetters([
      "isLoading",
      "isFirstTime",
      "isAuthenticated",
      "currentUser",
      "canSpendToday",
      "daysTillIncome"
    ])
  }
};
</script>

<style lang="scss" scoped>
.md-card {
  width: 85%;
  margin-right: auto;
  margin-left: auto;
}

.status-card {
  text-align: center;
}

.status-sub {
  color: white;
  font-size: 18px;
  font-weight: bold;
}

.records {
  margin-top: 20px;
  margin-bottom: 70px;
}

.center{
  text-align:center;
  margin-top:50px;
}
</style>
