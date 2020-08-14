<template>
    <div>
        <div class="row">
            <div class="mx-auto col-sm-12">
                <b-form @submit="create" class="taskAddForm form-inline">
                    <input type="hidden" name="schedule_id" v-model="createForm.schedule_id">

                    <b-form-input type="text" class="col-6 col-md-8" placeholder="Add task" v-model="createForm.name" required> </b-form-input>
                    <b-form-select class="col-4 col-md-2" v-model="createForm.level" :options="levels" :aria-selected="createForm.level"> </b-form-select>
                    <b-button type="submit" class="task-add-button col-2 col-md-2" variant="primary">Add</b-button>
                </b-form>
            </div>
        </div>

        <b-list-group class="tasks">
            <b-list-group-item v-for="(task, index) in schedule.tasks" v-bind:key="task.id" class="task">
                <div class="task-name">
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

                <div class="task-manage">
                    <b-button v-b-toggle.sidebar-second-variant id="task-sidebar-button">
                        <i class="fa fa-chevron-circle-right" @click="fillTaskForm(task)"> </i>
                    </b-button>

                    <a type="submit" @click="remove(index, task)">
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
</template>

<script>
    import axios from "axios";
    import TaskSidebar from "./TaskSidebar";

    export default {
        name: "TasksList",
        props: {
            schedule: Object,
            getList: Function,
            createForm: Object,
            sortList: Function
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
                    return 'not-important-task';
                }

                if (level === 'Important') {
                    return 'important-task';
                }

                if (level === 'Very Important') {
                    return 'very-important-task';
                }
            },
        }
    }
</script>

<style lang="scss">
    .not-important-task {
        color: $not-important-color;
    }

    .important-task {
        color: $important-color;
    }

    .very-important-task {
        color: $very-important-color;
    }

    .task-manage a {
        margin-left: 5px;
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

    .taskAddForm {
        margin-top: 20px;
    }

    .task-level {
        margin-right: 5px;
    }
</style>