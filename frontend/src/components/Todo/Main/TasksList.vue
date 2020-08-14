<template>
    <div>
        <div class="tasks-list" v-if="schedule.tasks">
            <div class="row tasks-list__create-form">
                <div class="mx-auto col-sm-12">
                    <b-form @submit="create" class="form-inline tasks-list__create-form_form">
                        <input type="hidden" name="schedule_id" v-model="createForm.schedule_id">

                        <b-form-input type="text" class="col-6 col-md-8" placeholder="Add task" v-model="createForm.name" required> </b-form-input>
                        <b-form-select class="col-4 col-md-2" v-model="createForm.level" :options="levels" :aria-selected="createForm.level"> </b-form-select>
                        <b-button type="submit" class="col-2 col-md-2" variant="primary">Add</b-button>
                    </b-form>
                </div>
            </div>

            <b-list-group class="tasks-list__list">
                <b-list-group-item v-for="(task, index) in schedule.tasks" v-bind:key="task.id" class="tasks-list__task">
                    <div class="tasks-list__task-info">
                        <b-form-checkbox
                                value="Complete"
                                unchecked-value="Not Complete"
                                v-model="task.status"
                                @input="changeStatus(task)"
                                inline
                        >
                        </b-form-checkbox>
                        <i class="fa fa-circle task-level" v-bind:class="getTaskImportantClass(task.importantLevel)"> </i>
                        {{ task.name }}
                        <template v-if="task.stepsCount !== 0 && task.stepsCount">
                            ({{ task.finishedSteps }} of {{ task.stepsCount }})
                        </template>
                    </div>

                    <div class="tasks-list__task-manage">
                        <b-button id="task-sidebar-button" v-b-toggle.sidebar-second-variant>
                            <i class="fa fa-ellipsis-h" @click="fillTaskForm(task)"> </i>
                        </b-button>

                        <a type="submit" @click="remove(index, task)" class="tasks-list__task-remove">
                            <i class="fa fa-trash"> </i>
                        </a>
                    </div>
                </b-list-group-item>
            </b-list-group>

            <TaskSidebar
                    v-bind:editTask="editTask"
                    v-bind:levels="levels"
                    v-bind:getList="getList"
                    v-bind:getTaskSteps="getTaskSteps"
                    v-bind:steps="steps"
            />
        </div>
        <div v-else>
            <p>Tasks not found</p>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import TaskSidebar from "./TaskSidebar";

    export default {
        name: "TasksList",
        props: {
            schedule: Object,
            createForm: Object,
            getList: Function,
            sortList: Function,
            changeCompletedTasksVisibility: Function
        },
        components: {
            TaskSidebar
        },
        data() {
            return {
                error: null,
                message: null,
                steps: [],
                statusForm: {
                    'task_id': null,
                    'status': null
                },
                editTask: {
                    'id': null,
                    'name': null,
                    'description': null,
                    'level': null,
                    'status': null,
                    'steps': null
                },
                removeForm: {
                    'task_id': null
                },
                levels: {
                    'Not Important': 'Not Important',
                    'Important': 'Important',
                    'Very Important': 'Very Important'
                }
            }
        },
        methods: {
            getTaskSteps(task) {
                axios.get('/api/todo/task/' + task.id + '/steps')
                    .then((response) => {
                        this.steps = response.data.steps;
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            fillTaskForm(task) {
                this.steps = [];

                this.editTask = {
                    'id': task.id,
                    'name': task.name,
                    'description': task.description,
                    'level': task.importantLevel,
                    'status': task.status
                };

                this.getTaskSteps(task);
            },
            create(event) {
                event.preventDefault();
                this.error = null;
                this.createForm.schedule_id = this.schedule.id;

                axios.post('/api/todo/task/create', this.createForm)
                    .then((response) => {
                        this.schedule.tasks.unshift({
                            id: response.data.id,
                            name: response.data.name,
                            description: response.data.description,
                            importantLevel: response.data.level,
                            status: response.data.status
                        });

                        this.createForm.name = null;

                        this.sortList();
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            changeStatus(task) {
                this.error = null;
                this.statusForm.task_id = task.id;
                this.statusForm.status = task.status;

                axios.patch('/api/todo/task/change-status', this.statusForm)
                    .then(() => {
                        window.setTimeout(this.changeCompletedTasksVisibility, 1000);
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            changeStepStatus(step) {
                this.error = null;
                this.statusStepForm.id = step.id;
                this.statusStepForm.status = step.status;

                axios.patch('/api/todo/task/step/change-status', this.statusStepForm)
                    .then(() => {
                        this.getList();
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            remove(index, task) {
                this.error = null;
                this.removeForm.task_id = task.id;

                axios.delete('/api/todo/task/remove', {
                    data: this.removeForm
                })
                    .then(() => {
                        this.schedule.tasks.splice(index, 1);
                        this.sortList();
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            getTaskImportantClass(level) {
                if (level === 'Not Important') {
                    return 'task-not-important';
                }

                if (level === 'Important') {
                    return 'task-important';
                }

                if (level === 'Very Important') {
                    return 'task-very-important';
                }
            },
        }
    }
</script>

<style lang="scss">
    .tasks-list {
        &__create {
            &-form {
                &_form {
                    margin-top: 20px;
                }
            }
        }

        &__task {
            margin-top: 10px;
            height: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 0;

            &-info {
                margin-right: 30px;
                display: flex;
                align-items: center;
            }

            &-manage {
                display: flex;
                align-items: baseline;
            }

            &-remove {
                margin-left: 20px;
            }
        }
    }

    .task {
        &-important-level {
            font-size: 30px;
            font-weight: bold;
        }

        &-level {
            margin-right: 5px;
        }

        &-not-important {
            color: $not-important-color;
        }

        &-important {
            color: $important-color;
        }

        &-very-important {
            color: $very-important-color;
        }
    }

    #task-sidebar-button {
        background: none;
        color: inherit;
        border: none;
        padding: 0;
        font: inherit;
        cursor: pointer;
        outline: inherit;
    }
</style>