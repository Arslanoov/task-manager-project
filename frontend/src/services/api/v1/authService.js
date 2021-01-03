import axios from "axios";
import qs from "qs";

import { API_PREFIX } from "@/services/api/v1/const";

export default class AuthService {
  auth(username, password) {
    return axios.post(`${API_PREFIX}/oauth/auth`, qs.stringify({
        grant_type: 'password',
        username: username,
        password: password,
        client_id: 'app',
        client_secret: '',
        access_type: 'offline'
      }))
  }

  async signUp(login, email, password) {
    return axios.post(`${API_PREFIX}auth/sign-up/request`, {
      login,
      email,
      password
    });
  }

  async confirmSignUp(token) {
    return axios.post(`${API_PREFIX}/auth/sign-up/confirm`, {
      token
    });
  }

  async refresh(token) {
    await axios.post(`${API_PREFIX}/oauth/auth`, {
        grant_type: 'refresh_token',
        refresh_token: token,
        client_id: 'app',
        client_secret: ''
      })
      .then(response => response.data)
  }
}
