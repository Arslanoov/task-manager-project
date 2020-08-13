<template>
    <div v-if="schedule">
        <h3>Tasks</h3>

        <p>{{ schedule.tasks_count }} tasks</p>

        <b-form-select v-model="sort.selected" :options="sort.options" :aria-selected="sort.selected" @change="sortList"> </b-form-select>

        <b-alert variant="danger" v-if="error" show>{{ error }}</b-alert>

        <div class="row">
            <div class="mx-auto col-sm-12">
                <b-form @submit="create" class="taskAddForm form-inline">
                    <input type="hidden" name="schedule_id" v-model="createForm.schedule_id">

                    <b-form-input type="text" class="col-6 col-md-8" placeholder="Add task" v-model="createForm.name" required> </b-form-input>
                    <b-form-select class="col-4 col-md-2" v-model="createForm.level" :options="levels" :aria-selected="createForm.level" @change="sortList"> </b-form-select>
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
                    <template v-if="task.stepsCount !== 0">
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

        <b-sidebar id="sidebar-second-variant" text-variant="dark" class="shadow-none" right>
            <div class="container">
                <b-alert variant="danger" v-if="error" show>{{ error }}</b-alert>
                <b-alert variant="success" v-if="message" show>{{ message }}</b-alert>

                <b-form @submit.prevent="edit()">
                    <input type="hidden" name="schedule_id" v-model="editTask.schedule_id">

                    <b-form-group>
                        <b-form-input type="text" v-model="editTask.name" required> </b-form-input>
                    </b-form-group>

                    <b-form-group>
                        <b-form-select v-model="editTask.level" :options="levels" :aria-selected="editTask.level"> </b-form-select>
                    </b-form-group>

                    <b-form-group>
                        <b-form-textarea type="text" placeholder="Description..." v-model="editTask.description" required> </b-form-textarea>
                    </b-form-group>

                    <b-button type="submit" variant="primary">Edit</b-button>
                </b-form>

                <div class="task-steps">
                    <h5>Steps</h5>

                    <b-form @submit.prevent="createStep(editTask)" class="form-inline">
                        <input type="hidden" name="task_id" v-model="editTask.id">

                        <b-form-input type="text" class="col-8 col-md-9" placeholder="Add step" v-model="createStepForm.name" required> </b-form-input>
                        <b-button type="submit" class="task-add-button col-4 col-md-3" variant="primary">Add</b-button>
                    </b-form>

                    <b-list-group v-if="steps.length > 0">
                        <b-list-group-item v-for="(step, index) in steps" :key="step.id" class="step">
                            <div class="step-name">
                                <b-form-checkbox
                                        value="Complete"
                                        unchecked-value="Not Complete"
                                        v-model="step.status"
                                        @input="changeStepStatus(step)"
                                        inline
                                >
                                </b-form-checkbox>
                                {{ step.name }}
                            </div>
                            <div class="step-manage">
                                <a type="submit" @click="upStep(editTask, step)">
                                    <i class="fa fa-arrow-up"> </i>
                                </a>
                                <a type="submit" @click="downStep(editTask, step)">
                                    <i class="fa fa-arrow-down"> </i>
                                </a>
                                <a type="submit" @click="removeStep(step, index)">
                                    <i class="fa fa-trash"> </i>
                                </a>
                            </div>
                        </b-list-group-item>
                    </b-list-group>
                    <div v-else class="steps-not-found">
                        This task doesn't have any steps
                    </div>
                </div>
            </div>
        </b-sidebar>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "List",
        data() {
            return {
                taskSidebarClosed: true,
                message: null,
                schedule: null,
                error: null,
                createForm: {
                    'name': null,
                    'schedule_id': null,
                    'level': null
                },
                statusForm: {
                    'task_id': null,
                    'status': null
                },
                removeForm: {
                    'task_id': null
                },
                editTask: {
                    'id': null,
                    'name': null,
                    'description': null,
                    'level': null,
                    'status': null,
                    'steps': null
                },
                steps: [],
                statusStepForm: {
                    'id': null,
                    'status': null
                },
                createStepForm: {
                    'task_id': null,
                    'name': null
                },
                upStepForm: {
                    'id': null
                },
                downStepForm: {
                    'id': null
                },
                removeStepForm: {
                    'id': null
                },
                levels: {
                    'Not Important': 'Not Important',
                    'Important': 'Important',
                    'Very Important': 'Very Important'
                },
                sort: {
                    selected: 'latest',
                    options: {
                        'latest': 'Latest',
                        'oldest': 'Oldest',
                        'important': 'Important',
                        'less_important': 'Less important'
                    },
                    levels: {
                        'Not Important': 0,
                        'Important': 1,
                        'Very Important': 2
                    }
                }
            }
        },
        mounted() {
            this.getList();
        },
        methods: {
            getList() {
                axios.get('/api/todo/main', this.createForm)
                    .then((response) => {
                        this.schedule = response.data;
                        this.createForm.schedule_id = response.data.id;
                        this.sortList();
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
            sortList() {
                if (this.sort.selected === 'latest') {
                    this.sortListByLatest();
                }

                if (this.sort.selected === 'oldest') {
                    this.sortListByOldest();
                }

                if (this.sort.selected === 'important') {
                    this.sortListByImportant();
                }

                if (this.sort.selected === 'less_important') {
                    this.sortListByLessImportant();
                }
            },
            sortListByLatest() {
                this.schedule.tasks.sort((a, b) => ('' + b.id).localeCompare(a.id));
            },
            sortListByOldest() {
                this.schedule.tasks.sort((a, b) => ('' + a.id).localeCompare(b.id));
            },
            sortListByImportant() {
                this.schedule.tasks.sort((a, b) => this.sort.levels[b.importantLevel] - this.sort.levels[a.importantLevel]);
            },
            sortListByLessImportant() {
                this.schedule.tasks.sort((a, b) => this.sort.levels[a.importantLevel] - this.sort.levels[b.importantLevel]);
            },
            create(event) {
                event.preventDefault();
                this.error = null;

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
            edit() {
                this.message = null;
                this.error = null;

                axios.patch('/api/todo/task/edit', this.editTask)
                    .then(() => {
                        this.getList();
                        this.message = 'Task successfully updated';
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
            createStep(task) {
                this.createStepForm.task_id = task.id;

                axios.post('/api/todo/task/step/create', this.createStepForm)
                    .then((response) => {
                        this.steps.push({
                            'id': response.data.id,
                            'task_id': task.id,
                            'name': this.createStepForm.name,
                            'status': 'Not Complete'
                        });

                        this.createStepForm.name = null;

                        this.getList();
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            upStep(task, step) {
                this.error = null;
                this.upStepForm.id = step.id;

                axios.patch('/api/todo/task/step/up', this.upStepForm)
                    .then(() => {
                        this.getTaskSteps(task);
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            downStep(task, step) {
                this.error = null;
                this.downStepForm.id = step.id;

                axios.patch('/api/todo/task/step/down', this.downStepForm)
                    .then(() => {
                        this.getTaskSteps(task);
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            removeStep(step, index) {
                this.error = null;
                this.removeStepForm.id = step.id;

                axios.delete('/api/todo/task/step/remove', {
                    data: this.removeStepForm
                })
                    .then(() => {
                        this.steps.splice(index, 1);
                        this.getList();
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            }
        }
    }
</script>

<style lang="scss">
    .task, .step {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        height: 40px;
        border-radius: 0;
    }

    .task-steps {
        margin-top: 15px;
        margin-bottom: 20px;
    }

    .task-name {
        margin-right: 30px;
        display: flex;
        align-items: center;
    }

    .step-name {
        display: flex;
        align-items: center;
    }

    .task-steps__step-name {
        margin-top: 10px;
    }

    .task-important-level {
        font-size: 30px;
        font-weight: bold;
    }

    .taskAddForm {
        margin-top: 20px;
    }

    .not-important-task {
        color: $not-important-color;
    }

    .important-task {
        color: $important-color;
    }

    .very-important-task {
        color: $very-important-color;
    }

    .task-level {
        margin-right: 5px;
    }

    .steps-not-found {
        margin-top: 10px;
    }

    .task-manage a {
        margin-left: 5px;
    }

    .step-manage a {
        margin-right: 10px;
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

    #sidebar-second-variant {
        padding: 0 20px;
        width: 100%;
        height: $sidebar-height;
    }
</style>