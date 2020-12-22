import axios from "axios";

export default class TasksService {
  getMainScheduleTasksCount() {
    return axios.get("/api/todo/main/tasks/count");
  }
}
