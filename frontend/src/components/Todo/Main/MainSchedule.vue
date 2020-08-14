<template>
    <Schedule
            v-bind:schedule="schedule"
            v-bind:getList="getList"
            ref="schedule"
    />
</template>

<script>
    import Schedule from "../Schedule";
    import axios from "axios";

    export default {
        name: "MainSchedule",
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

<style scoped>

</style>