<template>
    <div>
        <div v-if="schedule" class="schedule-list">
            <a type="button" @click="previousDay">
                <i class="fa fa-chevron-left schedule-list__day-navigate"> </i>
            </a>

            <h3 class="schedule-list__header">
                <template v-if="this.schedule.date.string">
                    {{ this.schedule.date.string }}
                    tasks
                </template>
                <template v-else>
                    Today
                </template>
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
            let date = new Date();
            this.todayDate.day = parseInt(String(date.getDate()).padStart(2, '0'));
            this.todayDate.month = date.getUTCMonth();
            this.todayDate.year = date.getUTCFullYear();
            this.getList(true, true);
        },
        data() {
            return {
                error: null,
                todayDate: {
                    day: null,
                    month: null,
                    year: null
                },
                schedule: null,
                createForm: {
                    'name': null,
                    'schedule_id': null,
                    'level': null
                }
            }
        },
        methods: {
            getList(checkVisibility = true, today = false) {
                let link = '';
                if (today) {
                    link = '/api/todo/daily/today';
                } else {
                    link = '/api/todo/daily/get-by-date/' + this.schedule.date.day + '/' + this.schedule.date.month + '/' + this.schedule.date.year;
                }

                axios.get(link)
                    .then((response) => {
                        this.schedule = response.data;
                        this.$refs.schedule.init(checkVisibility);
                        if (today) {
                            this.schedule.date.string = null;
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
            previousDay() {
                if (this.isToday(parseInt(this.schedule.date.day) - 1, this.schedule.date.month, this.schedule.date.year)) {
                    this.getList(true, true);
                } else {
                    axios.get('/api/todo/daily/previous/' + this.schedule.id)
                        .then((response) => {
                            this.schedule = response.data;
                            this.$refs.schedule.init(true);
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
            },
            nextDay() {
                if (this.isToday(parseInt(this.schedule.date.day) + 1, this.schedule.date.month, this.schedule.date.year)) {
                    this.getList(true, true);
                } else {
                    axios.get('/api/todo/daily/next/' + this.schedule.id)
                        .then((response) => {
                            this.schedule = response.data;
                            this.$refs.schedule.init(true);
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
            },
            isToday(day, month, year) {
                if (
                    parseInt(this.todayDate.day) === parseInt(day) &&
                    parseInt(this.todayDate.month) === parseInt(month) &&
                    parseInt(this.todayDate.year) === parseInt(year)
                ) {
                    return true;
                }

                return false;
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