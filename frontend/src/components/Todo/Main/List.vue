<template>
    <div v-if="schedule">
        <h3>Main TODO list</h3>

        <p>{{ schedule.tasks_count }} tasks</p>

        <b-list-group class="tasks">
            <b-list-group-item v-for="task in schedule.tasks" v-bind:key="task.id" class="task">
                <div v-if="task.status === 'Complete'">
                    <b-form-input type="checkbox"> </b-form-input>
                </div>
                <div class="task-name">
                    {{ task.name }}
                </div>
                <span class="task-important-level">
                    <span v-if="task.importantLevel === 'Not important'">!</span>
                    <span v-else-if="task.importantLevel === 'Important'">!!</span>
                    <span v-else-if="task.importantLevel === 'Very important'">!!!</span>
                </span>
            </b-list-group-item>
        </b-list-group>

        <b-form @submit="create" class="taskAddForm">
            <input type="hidden" name="schedule_id" v-model="form.schedule_id">

            <b-form-group>
                <b-form-input class="text-center mx-auto col-sm-6" type="text" placeholder="Add task" v-model="form.name" required> </b-form-input>
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
                form: {
                    'name': null,
                    'schedule_id': null
                }
            }
        },
        mounted() {
            axios.get('/api/todo/main', this.form)
                .then((response) => {
                    this.schedule = response.data;
                    this.form.schedule_id = response.data.id;
                })
                .catch(error => {
                    if (error.response) {
                        if (error.response.data.error) {
                            this.error = error.response.data.error;
                        } else if (error.response.data.errors) {
                            this.errors = error.response.data.errors;
                        }
                    } else {
                        console.log(error.message);
                    }
                });
        },
        methods: {
            create(event) {
                event.preventDefault();

                this.error = null;
                this.errors = [];

                axios.post('/api/todo/main/task/create', this.form)
                    .then((response) => {
                        this.schedule.tasks.unshift({
                            id: response.data.id,
                            name: response.data.name,
                            description: response.data.description,
                            importantLevel: response.data.level,
                            status: response.data.status
                        });

                        this.form = {
                            'name': null,
                            'schedule_id': null
                        };
                    })
                    .catch(error => {
                        if (error.response) {
                            if (error.response.data.error) {
                                this.error = error.response.data.error;
                            } else if (error.response.data.errors) {
                                this.errors = error.response.data.errors;
                            }
                        } else {
                            console.log(error.message);
                        }
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