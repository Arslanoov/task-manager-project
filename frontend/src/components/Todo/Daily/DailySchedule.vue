<template>
    <div>
        <div v-if="schedule" class="schedule-list">
            <a type="button" @click="previousDay">
                <i class="fa fa-chevron-left schedule-list__day-navigate"> </i>
            </a>

            <h3 class="schedule-list__header">
                {{ this.schedule.date.string }}
                tasks
            </h3>

            <a type="button" @click="nextDay">
                <i class="fa fa-chevron-right schedule-list__day-navigate"> </i>
            </a>
        </div>

        <Schedule
                v-bind:schedule="schedule"
                v-bind:getList="getList"
                ref="schedule"
        />
    </div>
</template>

<script>
    import Schedule from "../Schedule";
    import axios from "axios";

    export default {
        name: "DailySchedule",
        components: {
            Schedule
        },
        mounted() {
            this.getList();
        },
        data() {
            return {
                error: null,
                schedule: null,
                createForm: {
                    'name': null,
                    'schedule_id': null,
                    'level': null
                }
            }
        },
        methods: {
            getList(checkVisibility = true) {
                axios.get('/api/todo/daily/today')
                    .then((response) => {
                        this.schedule = response.data;
                        this.$refs.schedule.init(checkVisibility);
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
            previousDay() {
                axios.get('/api/todo/daily/previous/' + this.schedule.id)
                    .then((response) => {
                        this.schedule = response.data;
                        this.$refs.schedule.init();
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
            nextDay() {
                axios.get('/api/todo/daily/next/' + this.schedule.id)
                    .then((response) => {
                        this.schedule = response.data;
                        this.$refs.schedule.init();
                    })
                    .catch(error => {
                        if (error.response) {
                            this.error = error.response.data.error;
                            console.log(error.message);
                        } else {
                            alert(error);
                        }
                    });
            }
        }
    }
</script>

<style lang="scss">
    .schedule-list {
        display: flex;
        justify-content: space-around;

        &__day-navigate {
            margin: 0 20px;
            font-size: 25px;
        }
    }
</style>