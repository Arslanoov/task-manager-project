import Vue from "vue"
import Vuex from "vuex"

import user from "@/store/modules/user"
import alert from "@/store/modules/alert"
import todo from "@/store/modules/todo"

Vue.use(Vuex);

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    user,
    alert,
    todo
  },
  // TODO: for dev only
  strict: true
})
