<template>
  <md-card class="card records">
        <md-ripple>
          <md-card-header>
            <div class="md-title">Category expenses</div>
          </md-card-header>
          <md-card-content>
            <md-table  v-if="categorySumRecords.length!=0">
              <md-table-row>
                <md-table-head>Category</md-table-head>
                <md-table-head>Sum</md-table-head>
              </md-table-row>

              <md-table-row
                v-for="record in categorySumRecords"
                :key="record.id"
              >
                <md-table-cell>{{ record.category }}</md-table-cell>
                <md-table-cell>{{ record.sum }}</md-table-cell>
              </md-table-row>
            </md-table>
            <p v-else>There is no any records yet</p>
          </md-card-content>
        </md-ripple>
      </md-card>
</template>

<script>
import { mapGetters } from "vuex";
import {
  FETCH_CATEGORY_SUM_RECORDS
} from "@/store/actions.type";

export default {
  name: "home",
  created() {
    try {
      this.$store.dispatch(FETCH_CATEGORY_SUM_RECORDS);
    } catch (err) {
      console.error(err);
    }
  },
  computed: {
    ...mapGetters([
      "categorySumRecords"
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
</style>
