import ApiService from "@/common/api.service";
import JwtService from "@/common/jwt.service";
import {
  LOGIN,
  LOGOUT,
  REGISTER,
  CHECK_AUTH,
  UPDATE_USER
} from "./actions.type";
import { SET_AUTH, PURGE_AUTH, SET_ERROR } from "./mutations.type";

const state = {
  errors: null,
  user: {},
  isAuthenticated: !!JwtService.getToken(),
  isFirstTime: true
};

const getters = {
  currentUser(state) {
    return state.user;
  },
  isAuthenticated(state) {
    return state.isAuthenticated;
  },
  isFirstTime(state) {
    return state.isFirstTime;
  }
};

const actions = {
  [LOGIN](context, credentials) {
    return new Promise(resolve => {
      ApiService.post("users/login", credentials)
        .then(({ data }) => {
          if (data.status == "Success") {
            context.commit(SET_AUTH, data.data);
            resolve(data);
          } else {
            context.commit(SET_ERROR, data.error);
            reject(response);
          }
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.error);
        });
    });
  },
  [LOGOUT](context) {
    context.commit(PURGE_AUTH);
  },
  [REGISTER](context, credentials) {
    return new Promise((resolve, reject) => {
      ApiService.post("users", credentials)
        .then(({ data }) => {
          if (data.status == "Success") {
            localStorage.setItem("clientId", data.data.clientId);
            context.commit(SET_AUTH, data.data);
            resolve(data);
          } else {
            context.commit(SET_ERROR, data.error);
            reject(response);
          }
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.error);
          reject(response);
        });
    });
  },
  [CHECK_AUTH](context) {
    if (JwtService.getToken() && localStorage.getItem("clientId") !== null) {
      ApiService.post("user", {
        clientId: localStorage.getItem("clientId"),
        token: JwtService.getToken()
      })
        .then(({ data }) => {
          if (data.status == "Success") {
            context.commit(SET_AUTH, data.data);
          } else {
            context.commit(SET_ERROR, response.error);
            this.$router.push("login");
          }
        })
        .catch(({ response }) => {
          context.commit(SET_ERROR, response.error);
        });
    } else {
      context.commit(PURGE_AUTH);
    }
  },
  [UPDATE_USER](context, payload) {
    const { currentAmount, incomeAmount, incomeDate,defaultScreen } = payload;
    const user = {
      clientId: localStorage.getItem("clientId"),
      token: JwtService.getToken(),
      currentAmount,
      incomeAmount,
      incomeDate,
      defaultScreen
    };

    return ApiService.post("users/update", user).then(({ data }) => {
      context.commit(SET_AUTH, data.data);
      return data;
    });
  }
};

const mutations = {
  [SET_ERROR](state, error) {
    state.errors = error;
  },
  [SET_AUTH](state, user) {
    state.isAuthenticated = true;
    state.user = user;
    state.errors = {};
    state.isFirstTime = user.isFirstTime;
    JwtService.saveToken(state.user.token);
  },
  [PURGE_AUTH](state) {
    state.isAuthenticated = false;
    state.user = {};
    state.errors = {};
    JwtService.destroyToken();
  }
};

export default {
  state,
  actions,
  mutations,
  getters
};
