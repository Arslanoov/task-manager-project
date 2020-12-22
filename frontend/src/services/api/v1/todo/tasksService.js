import axios from "axios"

import { API_PREFIX } from "@/services/api/v1/const"

export default class TasksService {
  getMainScheduleTasksCount() {
    return axios.get(`${API_PREFIX}/todo/main/tasks/count`)
  }

  getDailyScheduleTasksCount() {
    return axios.get(`${API_PREFIX}/todo/daily/today/tasks/count`)
  }

  getCustomSchedules() {
    return axios.get(`${API_PREFIX}/todo/custom/list`)
  }
}
