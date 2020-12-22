import axios from "axios"

import { API_PREFIX } from "@/services/api/v1/const";

export default class CustomSchedulesService {
  addCustomSchedule(name) {
    return axios.post(`${API_PREFIX}/todo/custom/create`, {
      name
    })
  }

  removeCustomSchedule(id) {
    return axios.delete(`${API_PREFIX}/todo/custom/remove`, {
      data: {
        id
      }
    })
  }
}
