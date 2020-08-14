<template>
    <b-sidebar id="sidebar-second-variant" class="shadow-none" text-variant="dark" right>
        <div class="container">
            <b-alert variant="danger" v-if="error" show>{{ error }}</b-alert>
            <b-alert variant="success" v-if="message" show>{{ message }}</b-alert>

            <b-form @submit.prevent="edit()" class="task-sidebar__edit-form">
                <input type="hidden" name="schedule_id" v-model="editTask.schedule_id">

                <b-form-group>
                    <b-form-input type="text" v-model="editTask.name" required> </b-form-input>
                </b-form-group>

                <b-form-group>
                    <b-form-select v-model="editTask.level" :options="levels" :aria-selected="editTask.level"> </b-form-select>
                </b-form-group>

                <b-form-group>
                    <b-form-textarea type="text" placeholder="Description..." v-model="editTask.description" required> </b-form-textarea>
                </b-form-group>

                <b-button type="submit" variant="primary">Edit</b-button>
            </b-form>

            <StepsList
                    v-bind:editTask="editTask"
                    v-bind:getList="getList"
                    v-bind:getTaskSteps="getTaskSteps"
                    v-bind:steps="steps"
            />
        </div>
    </b-sidebar>
</template>

<script>
    import StepsList from "./StepsList";
    import axios from "axios";

    export default {
        name: "TaskSidebar",
        props: {
            editTask: Object,
            levels: Object,
            getList: Function,
            getTaskSteps: Function,
            steps: Array
        },
        components: {
            StepsList
        },
        data() {
            return {
                error: null,
                message: null,
                taskSidebarClosed: true
            }
        },
        methods: {
            edit() {
                this.message = null;
                this.error = null;

                axios.patch('/api/todo/task/edit', this.editTask)
                    .then(() => {
                        this.getList();
                        this.message = 'Task successfully updated';
                    })
                    .catch(error => {
                        alert(error);
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            }
        }
    }
</script>

<style lang="scss">
    #sidebar-second-variant {
        padding: 0 20px;
        width: $task-sidebar-width;
    }
</style>