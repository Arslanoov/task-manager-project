import Vue from "vue"
import Vuex from "vuex"
import user from "@/store/modules/user"
import todo from "@/store/modules/todo"

Vue.use(Vuex);

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    user,
    todo
  },
  // TODO: for dev only
  strict: true
})
