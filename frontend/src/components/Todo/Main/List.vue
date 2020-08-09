<template>
    <div>
        <p>{{ schedule.tasks_count }} tasks</p>
        <ul v-for="task in schedule.tasks" v-bind:key="task">
            <li>
                {{ task.name }}
            </li>
        </ul>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "List",
        data() {
            return {
                schedule: null
            }
        },
        mounted() {
            axios.get('/api/todo/main', this.form)
                .then((response) => {
                    this.schedule = response.data;
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
</script>