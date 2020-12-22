import axios from "axios";

export default class TasksService {
  getMainScheduleTasksCount() {
    return axios.get("/api/todo/main/tasks/count");
  }

  getDailyScheduleTasksCount() {
    return axios.get("/api/todo/daily/today/tasks/count");
  }
}
