<template>
    <div>
        <div v-if="schedule" class="schedule-list">
            <h3 class="schedule-list__header">Tasks</h3>
        </div>

        <Alert v-bind:error="error"/>

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
    import Alert from "../../Alert";

    export default {
        name: "MainSchedule",
        components: {
            Schedule,
            Alert
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
                },
            }
        },
        methods: {
            getList(checkVisibility = true) {
                axios.get('/api/todo/main')
                    .then((response) => {
                        this.schedule = response.data;
                        this.$refs.schedule.init(checkVisibility);
                    })
                    .catch(error => {
                        if (error.response) {
                            if (error.response.status === 404) {
                                this.$router.push({name: '404'});
                            }
                            this.error = error.response.data.error;
                            console.log(error.message);
                        }
                    });
            }
        }
    }
</script>

<style scoped>

</style>