<template>
  <div class="content">
    <div class="md-layout md-gutter">
      <div class="md-layout-item">
        <md-card class="card">
          <md-ripple>
            <md-card-header>
              <div class="md-title">Add record</div>
            </md-card-header>

            <md-card-content>
              <md-autocomplete v-model="category" :md-options="categories">
                <label>Category</label>
              </md-autocomplete>
              <md-field :class="{'md-invalid': isAmountValid}">
                <label>Amount</label>
                <md-icon v-if="isAmountValid" class="md-accent">warning</md-icon>
                <md-input v-model="amount" type="number"></md-input>
              </md-field>
              <md-datepicker v-model="date">
                <label>Date</label>
              </md-datepicker>
              <md-button class="md-raised md-primary" @click="addRecord"
                >Add</md-button
              >
            </md-card-content>
          </md-ripple>
        </md-card>
      </div>
      <div class="md-layout-item">
        <LastRecords/>
      </div>
    </div>
    <md-snackbar 
      :md-position="center" 
      :md-duration="infinity" 
      :md-active.sync="error" 
      md-persistent
    >
      <span>{{ error }}</span>
      <md-button v-on:click="error = null" class="md-primary" >close</md-button>
    </md-snackbar>

    <md-snackbar 
      :md-position="center" 
      :md-duration="6000" 
      :md-active.sync="isSuccess" 
      md-persistent
    >
      <span>Added successfully!</span>
      <md-button class="md-primary" v-on:click="isSuccess = false">close</md-button>
    </md-snackbar>
  </div>
</template>

<script>
import moment from "moment";
import { mapGetters } from "vuex";
import { ADD_RECORD, FETCH_LAST_RECORDS } from "@/store/actions.type";
import LastRecords from "@/components/LastRecords";

let today = new Date();
today = moment(String(today)).format("YYYY-MM-DD");

export default {
  name: "AddRecord",
  data: () => ({
    categories: ["daniel"],
    date: today,
    amount: 0,
    category: "",
    isSuccess: false,
    isAmountValid: false
  }),
  methods: {
    addRecord: function() {
      this.isAmountValid = false;
      let error = "";
      if(isNaN(this.amount)|| this.amount==="" || this.amount <= 0){
        // not valid number
        error = "Not valid amount";
        this.isAmountValid = true;
      }

      if(!error){
        this.$store
          .dispatch(ADD_RECORD, {
            category: this.category,
            amount: this.amount,
            date: this.date
          })
          .then(() => {
            if (!this.error) {
              this.isSuccess = true;
              this.category = "";
              this.amount = "";
            }
          });
      }
    }
  },
  components: {
    LastRecords
  },
  computed: {
    ...mapGetters(["error"])
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
