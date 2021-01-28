<template>
  <div>
    <md-steppers :md-active-step.sync="active" md-vertical md-linear>
      <md-step
        id="first"
        md-label="Welcome"
        md-description="Optional"
        :md-editable="false"
        :md-done.sync="first"
      >
        <p>
          Welcome! the app is going to calculate how much money you can spend
          each day based on your income and expenses.
        </p>
        <p>
          In addition you can get reports about how you spend your money by
          different categories
        </p>
        <md-button
          class="md-raised md-primary"
          @click="setDone('first', 'second')"
          >Continue</md-button
        >
      </md-step>

      <md-step
        id="second"
        md-label="Settings"
        :md-error="secondStepError"
        :md-editable="false"
        :md-done.sync="second"
      >
        <md-field>
          <label>Current amount of money in the account</label>
          <md-input type="number" v-model="currentAmount"></md-input>
        </md-field>
        <p>Do you have a regular monthly income? (Annuity / salary)</p>
        <md-field>
          <label>Amount of regular income</label>
          <md-input type="number" v-model="incomeAmount"></md-input>
        </md-field>
        <md-datepicker v-model="incomeDate">
          <label>Choose the next date when money will come in</label>
        </md-datepicker>
        <md-button
          class="md-raised md-primary"
          @click="setDone('second', 'third')"
          >Continue</md-button
        >
        <md-button
          class="md-raised md-primary"
          @click="setDone('second', 'third')"
          >I don't have regular income</md-button
        >
        <md-button class="md-raised md-primary" @click="setError()"
          >Set error!</md-button
        >
      </md-step>

      <md-step
        id="third"
        md-label="Set your deafult screen"
        :md-editable="false"
        :md-done.sync="third"
      >
        <md-field>
          <label for="deafultScreen"
            >Set the deafult screen of the application</label
          >
          <md-select
            v-model="defaultScreen"
            name="deafultScreen"
            id="deafultScreen"
          >
            <md-option value="TODAY_STATUS">Today status and records</md-option>
            <md-option value="ADD_NEW_RECORD">Add new record</md-option>
          </md-select>
        </md-field>
        <md-button class="md-raised md-primary" @click="setDone('third')"
          >Finish</md-button
        >
      </md-step>
    </md-steppers>
  </div>
</template>
<script>
import moment from "moment";
import { UPDATE_USER } from "@/store/actions.type";

export default {
  name: "StepperNonEditable",
  data: () => ({
    selectedDate: null,
    active: "first",
    first: false,
    second: false,
    third: false,
    secondStepError: null,
    incomeAmount: 0,
    currentAmount: 0,
    incomeDate: "2021-01-01",
    defaultScreen: "TODAY_STATUS"
  }),
  methods: {
    setDone(id, index) {
      this[id] = true;

      this.secondStepError = null;

      if (index) {
        this.active = index;
      }

      if (id == "third") {
        let incomeDate = moment(String(this.incomeDate)).format("YYYY-MM-DD");
        const userData = {
          incomeDate,
          incomeAmount: this.incomeAmount,
          currentAmount: this.currentAmount,
          defaultScreen: this.defaultScreen
        };
        this.$store.dispatch(UPDATE_USER, userData).then(() => {
          window.location.reload();
        });
      }
    },
    setError() {
      this.secondStepError = "This is an error!";
    }
  }
};
</script>

<style lang="scss" scoped>
.md-steppers {
}
</style>
