<template>
    <div v-if="schedule">
        <h3>Main TODO list</h3>

        <p>{{ schedule.tasks_count }} tasks</p>

        <b-form-select v-model="sort.selected" :options="sort.options" :aria-selected="this.sort.selected" @change="sortList"> </b-form-select>

        <b-alert variant="danger" v-if="error" show>{{ error }}</b-alert>

        <b-list-group class="tasks">
            <b-list-group-item v-for="(task, index) in schedule.tasks" v-bind:key="task.id" class="task">
                <div v-if="task.status === 'Complete'">
                    <b-form-input type="checkbox"> </b-form-input>
                </div>

                <div class="task-name">
                    {{ task.name }}
                </div>

                <b-button type="submit" variant="danger" @click="remove(index, task)">Remove</b-button>

                <span class="task-important-level">
                    <span v-if="task.importantLevel === 'Not Important'">!</span>
                    <span v-else-if="task.importantLevel === 'Important'">!!</span>
                    <span v-else-if="task.importantLevel === 'Very Important'">!!!</span>
                </span>
            </b-list-group-item>
        </b-list-group>

        <b-form @submit="create" class="taskAddForm">
            <input type="hidden" name="schedule_id" v-model="createForm.schedule_id">

            <b-form-group>
                <b-form-input class="text-center mx-auto col-sm-6" type="text" placeholder="Add task" v-model="createForm.name" required> </b-form-input>
            </b-form-group>
        </b-form>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "List",
        data() {
            return {
                schedule: null,
                error: null,
                createForm: {
                    'name': null,
                    'schedule_id': null
                },
                removeForm: {
                    'task_id': null
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
        methods: {
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
            }
        }
    }
</script>

<style lang="scss">
    .task {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .task-name {
        margin-right: 30px;
    }

    .task-important-level {
        font-size: 30px;
        font-weight: bold;
    }

    .taskAddForm {
        margin-top: 20px;
    }
</style>