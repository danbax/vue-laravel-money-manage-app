<template>
  <md-card class="card last-records">
          <md-ripple>
            <md-card-header>
              <div class="md-title">Last records</div>
            </md-card-header>
            <md-card-content>
              <md-table  v-if="getLastRecords.length!=0">
                <md-table-row>
                  <md-table-head md-numeric>Date</md-table-head>
                  <md-table-head>Category</md-table-head>
                  <md-table-head>Amount</md-table-head>
                </md-table-row>

                <md-table-row v-for="record in getLastRecords" :key="record.id">
                  <md-table-cell>{{ record.date }}</md-table-cell>
                  <md-table-cell>{{ record.category }}</md-table-cell>
                  <md-table-cell md-numeric>{{ record.amount }}</md-table-cell>
                </md-table-row>
              </md-table>
              <p v-else>There is no any records yet</p>
            </md-card-content>
          </md-ripple>
        </md-card>
</template>

<script>
import moment from "moment";
import { mapGetters } from "vuex";
import { FETCH_LAST_RECORDS } from "@/store/actions.type";

let today = new Date();
today = moment(String(today)).format("YYYY-MM-DD");

export default {
  name: "LastRecords",
  data: () => ({
  }),
  created() {
    this.$store.dispatch(FETCH_LAST_RECORDS, {}).then(() => {});
  },
  computed: {
    ...mapGetters(["getLastRecords"])
  }
};
</script>

<style scoped>
.card {
  width: 80%;
  margin-right: auto;
  margin-left: auto;
}

.last-records {
  margin-bottom: 100px;
}

.content {
  margin-top: 15px;
  text-align: center;
}
</style>
