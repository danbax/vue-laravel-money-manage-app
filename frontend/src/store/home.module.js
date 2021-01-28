import ApiService from "@/common/api.service";
import JwtService from "@/common/jwt.service";
import { FETCH_USER_DATA, SET_ERROR } from "./actions.type";
import { FETCH_START, FETCH_END } from "./mutations.type";

const state = {
  isLoading: true,
  currentAmount: 0,
  incomeAmount: 0,
  incomeDate: "01/01/2021"
};

const getters = {
  isLoading(state) {
    return state.isLoading;
  },
  currentAmount(state) {
    return state.currentAmount;
  },
  incomeAmount(state) {
    return state.incomeAmount;
  },
  incomeDate(state) {
    return state.incomeDate;
  },
  canSpendToday(state) {
    return Math.floor(
      state.currentAmount / findNextPaymentDate(state.incomeDate)
    );
  },
  daysTillIncome(state) {
    return findNextPaymentDate(state.incomeDate);
  }
};

const actions = {
  [FETCH_USER_DATA](context) {
    context.commit(FETCH_START);
    if (JwtService.getToken() && localStorage.getItem("clientId") !== null) {
      ApiService.post("user", {
        clientId: localStorage.getItem("clientId"),
        token: JwtService.getToken()
      })
        .then(({ data }) => {
          if (data.status == "Success") {
            context.commit(FETCH_END, data.data);
          } else {
            context.commit(SET_ERROR, response.error);
          }
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.error);
        });
    } else {
      context.commit(SET_ERROR, response.error);
    }
  }
};

/* eslint no-param-reassign: ["error", { "props": false }] */
const mutations = {
  [FETCH_START](state) {
    state.isLoading = true;
  },
  [FETCH_END](state, user) {
    state.currentAmount = user.currentAmount;
    state.incomeAmount = user.incomeAmount;
    state.incomeDate = user.incomeDate;
    state.isLoading = false;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};

const findNextPaymentDate = date => {
  const now = new Date(Date.now());
  let incomeDate = new Date(date);

  var day = incomeDate.getDate();
  var month = now.getMonth();
  if (now.getDay() > day) {
    month++;
  }
  var year = now.getFullYear();
  if (month > 12) {
    month = 1;
    year++;
  }
  incomeDate = new Date(year, month, day);

  const diffTime = Math.abs(incomeDate - now);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return diffDays;
};
