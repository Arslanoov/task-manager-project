<template>
    <div>
        <b-nav-item class="nav-item__header">
            <div class="nav-item__name">
                Custom lists
            </div>
        </b-nav-item>

        <b-alert variant="danger" v-if="error" show>{{ error }}</b-alert>

        <b-nav-item
                v-for="(schedule, index) in schedules"
                :to="{name: 'todo.custom', params: { id: schedule.id }}"
                :key="schedule.id"
        >
            <div class="nav-item__name">
                <i class="fa fa-list-ol"> </i>
                {{ schedule.name }}
            </div>
            <a type="submit" @click.prevent="remove(index, schedule)">
                <i class="fa fa-trash"> </i>
            </a>
        </b-nav-item>

        <div class="form-inline schedule-create-form">
            <input type="text" class="schedule-create-form__input" placeholder="Create schedule" v-model="createForm.name" min="1" max="32" required>
            <a type="submit" @click="create">
                <i class="fa fa-plus"> </i>
            </a>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "CustomSchedulesList",
        data() {
            return {
                error: null,
                schedules: [],
                createForm: {
                    name: null
                },
                removeForm: {
                    id: null
                }
            }
        },
        mounted() {
            this.getSchedules();
        },
        methods: {
            getSchedules() {
                axios.get('/api/todo/custom/list')
                    .then((response) => {
                        this.schedules = response.data.schedules;
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            create() {
                this.error = null;

                axios.post('/api/todo/custom/create', this.createForm)
                    .then((response) => {
                        this.schedules.push({
                            'id': response.data.id,
                            'name': this.createForm.name,
                            'tasksCount': 0
                        });
                        this.createForm.name = null;
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            remove(index, schedule) {
                this.error = null;
                this.removeForm.id = schedule.id;

                axios.delete('/api/todo/custom/remove', {
                    data: this.removeForm
                })
                    .then(() => {
                        this.schedules.splice(index, 1);
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
    .schedule-create-form {
        display: flex;
        justify-content: space-between;

        &__input {
            height: 35px;
            font-weight: 300;
            font-size: 14px;
            border: 0;
            background: $sidebar-background;
        }

        &__input:focus {
            outline: none;
            border: 0;
        }
    }

    .nav-item {
        &__header {
            margin-top: 30px;

            .nav-item__name {
                font-weight: 500;
            }
        }
    }
</style>