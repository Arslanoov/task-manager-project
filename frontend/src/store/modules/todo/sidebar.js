import {
  SET_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT
} from "@/store/mutations"

import { FETCH_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT } from "@/store/actions"

import TasksService from "@/services/api/v1/todo/tasksService"

const service = new TasksService()

export const SIDEBAR_STORE_PREFIX = "sidebar/"

export default {
  namespaced: true,
  state: {
    mainSchedule: {
      tasksCount: 0
    }
  },
  mutations: {
    [SET_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT]: (state, payload) => (state.mainSchedule.tasksCount = payload)
  },
  actions: {
    [FETCH_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT]: ({ commit }) => {
      return new Promise((resolve, reject) => {
        service.getMainScheduleTasksCount()
          .then(response => {
            const count = response.data.count
            commit(SET_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT, count)
            resolve(count)
          })
          .catch(error => {
            console.log(error)
            reject(error)
          })
      })
    }
  },
  getters: {
    mainScheduleTasksCount: state => state.mainSchedule.tasksCount
  }
}
