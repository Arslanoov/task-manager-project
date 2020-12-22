import {
  SET_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT,
  SET_SIDEBAR_DAILY_SCHEDULE_TASKS_COUNT,
  SET_SIDEBAR_CUSTOM_SCHEDULES_LIST,
  CREATE_SIDEBAR_CUSTOM_SCHEDULE,
  SET_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_NAME,
  SET_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_ERROR,
  CLEAR_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_NAME,
  CLEAR_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_ERROR,
  DELETE_SIDEBAR_CUSTOM_SCHEDULE
} from "@/store/mutations"

import {
  FETCH_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT,
  FETCH_SIDEBAR_DAILY_SCHEDULE_TASKS_COUNT,
  FETCH_SIDEBAR_CUSTOM_SCHEDULES_LIST,
  ADD_SIDEBAR_CUSTOM_SCHEDULE,
  REMOVE_SIDEBAR_CUSTOM_SCHEDULE
} from "@/store/actions"

import SidebarService from "@/services/api/v1/todo/sidebarService"
import CustomSchedulesService from "@/services/api/v1/todo/customSchedulesService"

const sidebarService = new SidebarService()
const customSchedulesService = new CustomSchedulesService()

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
      list: [],
      createForm: {
        name: null,
        error: null
      }
    }
  },
  mutations: {
    [SET_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT]: (state, payload) => (state.mainSchedule.tasksCount = payload),
    [SET_SIDEBAR_DAILY_SCHEDULE_TASKS_COUNT]: (state, payload) => (state.dailySchedule.tasksCount = payload),
    [SET_SIDEBAR_CUSTOM_SCHEDULES_LIST]: (state, payload) => (state.customSchedule.list = payload),
    [CREATE_SIDEBAR_CUSTOM_SCHEDULE]: (state, payload) => (state.customSchedule.list.push(payload)),
    [SET_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_NAME]: (state, payload) =>
      (state.customSchedule.createForm.name = payload),
    [SET_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_ERROR]: (state, payload) =>
      (state.customSchedule.createForm.error = payload),
    [CLEAR_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_NAME]: state => (state.customSchedule.createForm.name = null),
    [CLEAR_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_ERROR]: state => (state.customSchedule.createForm.error = null),
    [DELETE_SIDEBAR_CUSTOM_SCHEDULE]: (state, payload) =>
      (state.customSchedule.list = state.customSchedule.list.filter(item => item.id !== payload))
  },
  actions: {
    [FETCH_SIDEBAR_MAIN_SCHEDULE_TASKS_COUNT]: ({ commit }) => {
      return new Promise((resolve, reject) => {
        sidebarService.getMainScheduleTasksCount()
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
        sidebarService.getDailyScheduleTasksCount()
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
        sidebarService.getCustomSchedules()
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
    },
    [ADD_SIDEBAR_CUSTOM_SCHEDULE]: ({ commit, getters }) => {
      return new Promise((resolve, reject) => {
        commit(CLEAR_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_ERROR)

        customSchedulesService.addCustomSchedule(getters.customScheduleCreateFormName)
          .then((response) => {
            const schedule = {
              id: response.data.id,
              name: getters.customScheduleCreateFormName,
              tasksCount: 0
            }
            commit(CREATE_SIDEBAR_CUSTOM_SCHEDULE, schedule)
            commit(CLEAR_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_NAME)
            resolve(schedule)
          })
          .catch(error => {
            console.log(error)
            reject(error)
          })
      })
    },
    [REMOVE_SIDEBAR_CUSTOM_SCHEDULE]: ({ commit }, payload) => {
      return new Promise((resolve, reject) => {
        customSchedulesService.removeCustomSchedule(payload)
          .then(() => commit(DELETE_SIDEBAR_CUSTOM_SCHEDULE, payload))
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
    customSchedules: state => state.customSchedule.list,
    customScheduleCreateFormName: state => state.customSchedule.createForm.name,
    customScheduleCreateFormError: state => state.customSchedule.createForm.error
  }
}
