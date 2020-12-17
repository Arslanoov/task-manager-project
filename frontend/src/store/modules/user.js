import axios from "axios"

import AuthService from "@/services/api/v1/authService"

import {
  SET_USER,
  REMOVE_USER,
  SET_AUTH_FORM_USERNAME,
  SET_AUTH_FORM_PASSWORD,
  CLEAR_AUTH_FORM,
  SET_AUTH_FORM_ERROR,
  CLEAR_AUTH_FORM_ERROR
} from "@/store/mutations"
import {
  LOGIN,
  LOGOUT,
  REFRESH
} from "@/store/actions"

export const STORE_USER_PREFIX = "user/"

const service = new AuthService()

export default {
  namespaced: true,
  state: {
    user: JSON.parse(localStorage.getItem("user")),
    refreshToken: null,
    authForm: {
      email: null,
      password: null,
      error: null
    }
  },
  mutations: {
    [SET_USER]: (state, payload) => state.user = payload,
    [REMOVE_USER]: state => state.user = null,
    [SET_AUTH_FORM_USERNAME]: (state, payload) => state.authForm.email = payload,
    [SET_AUTH_FORM_PASSWORD]: (state, payload) => state.authForm.password = payload,
    [CLEAR_AUTH_FORM]: state => {
      state.authForm.email = null
      state.authForm.password = null
      state.authForm.error = null
    },
    [SET_AUTH_FORM_ERROR]: (state, payload) => state.authForm.error = payload,
    [CLEAR_AUTH_FORM_ERROR]: state => state.authForm.error = null
  },
  actions: {
    [LOGIN]: async ({ commit, getters }) => {
      return new Promise((resolve, reject) => {
        commit(REMOVE_USER)

        service.auth(getters.authFormUsername, getters.authFormPassword)
          .then(response => {
            const user = response.data
            localStorage.setItem("user", JSON.stringify(user))
            axios.defaults.headers.common["Authorization"] = getters.bearerToken
            commit(SET_USER, user)
            resolve(user)
          })
          .catch(error => {
            console.log(error)
            commit(REMOVE_USER)
            commit(SET_AUTH_FORM_ERROR, error.response.data.error)
            localStorage.removeItem("user")
            reject(error)
          })
      })
    },
    [LOGOUT]: ({ commit }) => {
      commit(REMOVE_USER)
      localStorage.removeItem("user")
      delete axios.defaults.headers.common["Authorization"]
    },
    [REFRESH]: async ({ commit, getters }) => {
      if (getters.user) {
        delete axios.defaults.headers.common["Authorization"]
        const user = await service.refresh(getters.refreshToken)
        localStorage.setItem("user", JSON.stringify(user))
        axios.defaults.headers.common["Authorization"] = getters.bearerToken
        commit(SET_USER, user)
      }
    }
  },
  getters: {
    isLoggedIn: state => !!state.user,
    user: state => state.user,
    refreshToken: state => state.refreshToken,
    bearerToken: state => state.user ? "Bearer " + state.user.access_token : "",
    authFormUsername: state => state.authForm.email,
    authFormPassword: state => state.authForm.password,
    authFormError: state => state.authForm.error
  }
}
