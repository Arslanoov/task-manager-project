import {
  SET_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT,
  SET_SIDEBAR_DAILY_SCHEDULE_TASKS_COUNT,
  SET_SIDEBAR_CUSTOM_SCHEDULES_LIST
} from "@/store/mutations"

import {
  FETCH_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT,
  FETCH_SIDEBAR_DAILY_SCHEDULE_TASKS_COUNT,
  FETCH_SIDEBAR_CUSTOM_SCHEDULES_LIST
} from "@/store/actions"

import TasksService from "@/services/api/v1/todo/tasksService"

const service = new TasksService()

export const SIDEBAR_STORE_PREFIX = "sidebar/"

export default {
  namespaced: true,
  state: {
    mainSchedule: {
      tasksCount: 0
    },
    dailySchedule: {
      tasksCount: 0
    },
    customSchedule: {
      list: []
    }
  },
  mutations: {
    [SET_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT]: (state, payload) => (state.mainSchedule.tasksCount = payload),
    [SET_SIDEBAR_DAILY_SCHEDULE_TASKS_COUNT]: (state, payload) => (state.dailySchedule.tasksCount = payload),
    [SET_SIDEBAR_CUSTOM_SCHEDULES_LIST]: (state, payload) => (state.customSchedule.list = payload)
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
    },
    [FETCH_SIDEBAR_DAILY_SCHEDULE_TASKS_COUNT]: ({ commit }) => {
      return new Promise((resolve, reject) => {
        service.getDailyScheduleTasksCount()
          .then(response => {
            const count = response.data.count
            commit(SET_SIDEBAR_DAILY_SCHEDULE_TASKS_COUNT, count)
            resolve(count)
          })
          .catch(error => {
            console.log(error)
            reject(error)
          })
      })
    },
    [FETCH_SIDEBAR_CUSTOM_SCHEDULES_LIST]: ({ commit }) => {
      return new Promise((resolve, reject) => {
        service.getCustomSchedules()
          .then(response => {
            const schedules = response.data.schedules
            commit(SET_SIDEBAR_CUSTOM_SCHEDULES_LIST, schedules)
            resolve(schedules)
          })
          .catch(error => {
            console.log(error)
            reject(error)
          })
      })
    }
  },
  getters: {
    mainScheduleTasksCount: state => state.mainSchedule.tasksCount,
    dailyScheduleTasksCount: state => state.dailySchedule.tasksCount,
    customSchedules: state => state.customSchedule.list
  }
}
