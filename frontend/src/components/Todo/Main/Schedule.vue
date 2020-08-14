<template>
    <div v-if="schedule">
        <h3>Tasks</h3>

        <p>{{ schedule.tasksCount }} tasks</p>

        <div class="row">
            <div class="col-sm-12">
                <b-form-select
                        v-model="sort.selected"
                        :options="sort.options"
                        :aria-selected="sort.selected"
                        @change="sortList"
                > </b-form-select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="hide-checkbox">
                    <span class="hide-checkbox__label">Hide completed</span>
                    <b-form-checkbox
                            value="hidden"
                            unchecked-value="visible"
                            v-model="sort.completedTasksVisibility"
                            @input="changeCompletedTasksVisibility"
                            checked="checked"
                            inline
                    >
                    </b-form-checkbox>
                </div>
            </div>
        </div>

        <b-alert variant="danger" v-if="error" show>{{ error }}</b-alert>

        <TasksList
                v-bind:schedule="schedule"
                v-bind:getList="getList"
                v-bind:sortList="sortList"
                v-bind:createForm="createForm"
        />
    </div>
</template>

<script>
    import axios from "axios";
    import TasksList from "./TasksList";

    export default {
        name: "Schedule",
        components: {
            TasksList
        },
        mounted() {
            this.getList(true);
        },
        data() {
            return {
                schedule: null,
                error: null,
                createForm: {
                    'name': null,
                    'schedule_id': null,
                    'level': null
                },
                sort: {
                    selected: 'latest',
                    options: {
                        'latest': 'Latest',
                        'oldest': 'Oldest',
                        'important': 'Important',
                        'less_important': 'Less important',
                        'completed_first': 'Completed first',
                        'uncompleted_first': 'Uncompleted first'
                    },
                    levels: {
                        'Not Important': 0,
                        'Important': 1,
                        'Very Important': 2
                    },
                    statuses: {
                        'Not Complete': 0,
                        'Complete': 1
                    },
                    completedTasksVisibility: 'hidden'
                }
            }
        },
        methods: {
            getList(checkVisibility = false) {
                axios.get('/api/todo/main', this.createForm)
                    .then((response) => {
                        this.schedule = response.data;
                        this.createForm.schedule_id = response.data.id;
                        this.sortList();
                        if (checkVisibility) {
                            this.changeCompletedTasksVisibility();
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            this.error = error.response.data.error;
                            console.log(error.message);
                        } else {
                            alert(error);
                        }
                    });
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

                if (this.sort.selected === 'completed_first') {
                    this.sortListByCompleted();
                }

                if (this.sort.selected === 'uncompleted_first') {
                    this.sortListByUncompleted();
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
            sortListByCompleted() {
                this.schedule.tasks.sort((a, b) => this.sort.statuses[b.status] - this.sort.statuses[a.status]);
            },
            sortListByUncompleted() {
                this.schedule.tasks.sort((a, b) => this.sort.statuses[a.status] - this.sort.statuses[b.status]);
            },
            changeCompletedTasksVisibility() {
                if (this.sort.completedTasksVisibility === 'hidden') {
                    this.removeCompleted();
                }

                if (this.sort.completedTasksVisibility === 'visible') {
                    this.returnCompleted();
                }
            },
            removeCompleted() {
                let tasksCount = this.schedule.tasks.length;

                for (let i = 0; i < tasksCount; i++) {
                    if (this.schedule.tasks[i] && this.schedule.tasks[i].status === 'Complete') {
                        this.schedule.tasks.splice(i, 1);
                        i--;
                    }
                }
            },
            returnCompleted() {
                this.getList();
            }
        }
    }
</script>

<style lang="scss">
    .hide-checkbox {
        margin-top: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hide-checkbox__label {
        margin-right: 5px;
    }



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

    .step-manage a {
        margin-right: 10px;
    }

    #sidebar-second-variant {
        padding: 0 20px;
        width: 100%;
        height: $sidebar-height;
    }
</style>