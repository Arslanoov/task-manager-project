import axios from "axios"

import AuthService from "@/services/api/v1/authService"

import {
  SET_USER,
  REMOVE_USER,
  SET_AUTH_FORM_USERNAME,
  SET_AUTH_FORM_PASSWORD,
  CLEAR_AUTH_FORM,
  SET_AUTH_FORM_ERROR,
  CLEAR_AUTH_FORM_ERROR,
  SET_SIGN_UP_FORM_LOGIN,
  SET_SIGN_UP_FORM_EMAIL,
  SET_SIGN_UP_FORM_PASSWORD,
  SET_SIGN_UP_FORM_ERROR,
  CLEAR_SIGN_UP_FORM_ERROR,
  SET_CONFIRM_SIGN_UP_TOKEN,
  SET_CONFIRM_SIGN_UP_ERROR,
  CLEAR_CONFIRM_SIGN_UP_ERROR
} from "@/store/mutations"

import {
  LOGIN,
  LOGOUT,
  REFRESH,
  SIGN_UP,
  CONFIRM_SIGN_UP
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
    },
    signUpForm: {
      login: null,
      email: null,
      password: null,
      error: null
    },
    confirmSignUpForm: {
      token: null,
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
    [CLEAR_AUTH_FORM_ERROR]: state => state.authForm.error = null,
    [SET_SIGN_UP_FORM_LOGIN]: (state, payload) => (state.signUpForm.login = payload),
    [SET_SIGN_UP_FORM_EMAIL]: (state, payload) => (state.signUpForm.email = payload),
    [SET_SIGN_UP_FORM_PASSWORD]: (state, payload) => (state.signUpForm.password = payload),
    [SET_SIGN_UP_FORM_ERROR]: (state, payload) => (state.signUpForm.error = payload),
    [CLEAR_SIGN_UP_FORM_ERROR]: state => state.signUpForm.error = null,
    [SET_CONFIRM_SIGN_UP_TOKEN]: (state, payload) => (state.confirmSignUpForm.token = payload),
    [SET_CONFIRM_SIGN_UP_ERROR]: (state, payload) => (state.confirmSignUpForm.error = payload),
    [CLEAR_CONFIRM_SIGN_UP_ERROR]: state => state.confirmSignUpForm.error = null
  },
  actions: {
    [LOGIN]: async ({ commit, getters }) => {
      return new Promise((resolve, reject) => {
        commit(REMOVE_USER)

        service.auth(getters.authFormUsername, getters.authFormPassword)
          .then(response => {
            const user = response.data
            localStorage.setItem("user", JSON.stringify(user))
            commit(SET_USER, user)
            axios.defaults.headers.common["Authorization"] = getters.bearerToken
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
    [LOGOUT]: async ({ commit }) => {
      return new Promise((resolve, reject) => {
        try {
          commit(REMOVE_USER)
          localStorage.removeItem("user")
          delete axios.defaults.headers.common["Authorization"]
          resolve({})
        } catch (e) {
          reject(e)
        }
      })
    },
    [REFRESH]: async ({ commit, getters }) => {
      if (getters.user) {
        delete axios.defaults.headers.common["Authorization"]
        const user = await service.refresh(getters.refreshToken)
        localStorage.setItem("user", JSON.stringify(user))
        axios.defaults.headers.common["Authorization"] = getters.bearerToken
        commit(SET_USER, user)
      }
    },
    [SIGN_UP]: async ({ commit, getters }) => {
      return new Promise((resolve, reject) => {
        commit(CLEAR_SIGN_UP_FORM_ERROR)

        service.signUp(getters.signUpFormLogin, getters.signUpFormEmail, getters.signUpFormPassword)
          .then(response => resolve(response.data))
          .catch(error => {
            console.log(error)
            commit(SET_SIGN_UP_FORM_ERROR, error.response.data.error)
            reject(error.response)
          })
      })
    },
    [CONFIRM_SIGN_UP]: async ({ commit, getters }) => {
      return new Promise((resolve, reject) => {
        commit(CLEAR_CONFIRM_SIGN_UP_ERROR)
        service.confirmSignUp(getters.confirmSignUpFormToken)
          .then(response => resolve(response.data))
          .catch(error => {
            console.log(error)
            commit(SET_CONFIRM_SIGN_UP_ERROR, error.response.data.error)
            reject(error.response)
          })
      })
    }
  },
  getters: {
    isLoggedIn: state => !!state.user,
    user: state => state.user,
    refreshToken: state => state.refreshToken,
    bearerToken: state => state.user ? "Bearer " + state.user.access_token : "",
    authFormUsername: state => state.authForm.email,
    authFormPassword: state => state.authForm.password,
    authFormError: state => state.authForm.error,
    signUpFormLogin: state => state.signUpForm.login,
    signUpFormEmail: state => state.signUpForm.email,
    signUpFormPassword: state => state.signUpForm.password,
    signUpFormError: state => state.signUpForm.error,
    confirmSignUpFormToken: state => state.confirmSignUpForm.token,
    confirmSignUpFormError: state => state.confirmSignUpForm.error
  }
}
