import ApiService from "@/common/api.service";
import JwtService from "@/common/jwt.service";
import {
  ADD_RECORD,
  FETCH_LAST_RECORDS,
  FETCH_CATEGORY_SUM_RECORDS
} from "./actions.type";
import {
  RECORD_ADDED,
  SET_ERROR,
  SET_LAST_RECORDS,
  SET_CATEGORY_SUM_RECORDS
} from "./mutations.type";

const state = {
  lastRecords: [],
  categorySumRecords: [],
  error: null
};

const getters = {
  getLastRecords(state) {
    return state.lastRecords;
  },
  categorySumRecords(state) {
    return state.categorySumRecords;
  },
  error(state) {
    return state.error;
  }
};

const actions = {
  [ADD_RECORD](context, params) {
    if (JwtService.getToken() && localStorage.getItem("clientId") !== null) {
      ApiService.post("records/create", {
        ...params,
        clientId: localStorage.getItem("clientId"),
        token: JwtService.getToken()
      })
        .then(({ data }) => {
          if (data.status == "Success") {
            context.commit(RECORD_ADDED, data.data);
          } else {
            context.commit(SET_ERROR, data.error);
          }
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, "The server can not complete the request");
        });
    } else {
      context.commit(SET_ERROR, "NOT LOGGED IN");
    }
  },

  [FETCH_LAST_RECORDS](context, params) {
    if (JwtService.getToken() && localStorage.getItem("clientId") !== null) {
      ApiService.post("records/getLastRecords", {
        ...params,
        clientId: localStorage.getItem("clientId"),
        token: JwtService.getToken()
      })
        .then(({ data }) => {
          if (data.status == "Success") {
            context.commit(SET_LAST_RECORDS, data.data);
          } else {
            context.commit(SET_ERROR, data.error);
          }
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, "The server can not complete the request");
        });
    } else {
      context.commit(SET_ERROR, "NOT LOGGED IN");
    }
  },

  [FETCH_CATEGORY_SUM_RECORDS](context) {
    if (JwtService.getToken() && localStorage.getItem("clientId") !== null) {
      ApiService.post("records/getCategoriesSum", {
        clientId: localStorage.getItem("clientId"),
        token: JwtService.getToken()
      })
        .then(({ data }) => {
          if (data.status == "Success") {
            context.commit(SET_CATEGORY_SUM_RECORDS, data.data);
          } else {
            context.commit(SET_ERROR, data.error);
          }
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, "The server can not complete the request");
        });
    } else {
      context.commit(SET_ERROR, "NOT LOGGED IN");
    }
  }
};

/* eslint no-param-reassign: ["error", { "props": false }] */
const mutations = {
  [RECORD_ADDED](state, record) {
    state.error = null;
    state.lastRecords.unshift(record);
    return true;
  },
  [SET_LAST_RECORDS](state, records) {
    state.error = null;
    state.lastRecords = records;
    return true;
  },
  [SET_CATEGORY_SUM_RECORDS](state, records) {
    state.error = null;
    state.categorySumRecords = records;
    return true;
  },
  [SET_ERROR](state, error) {
    state.error = error;
    return false;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};
