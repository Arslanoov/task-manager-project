<template>
    <div v-if="count">
        {{ count }}
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "TasksCount",
        data() {
            return {
                count: null
            };
        },
        mounted() {
            axios.get('/api/todo/main/tasks/count').then(response => {
                this.count = response.data.count;
            });
        }
    }
</script>

<style scoped>

</style>