import {
  SET_ALERT,
  CLEAR_ALERT
} from "@/store/mutations";

export const STORE_ALERT_PREFIX = "alert/"

export default {
  namespaced: true,
  state: {
    message: null,
    type: null
  },
  mutations: {
    [SET_ALERT]: (state, payload) => {
      state.message = payload.message
      state.type = payload.type
    },
    [CLEAR_ALERT]: state => {
      state.message = null
      state.type = null
    }
  },
  actions: {},
  getters: {
    message: state => state.message,
    type: state => state.type
  }
};
