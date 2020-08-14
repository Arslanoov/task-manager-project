<template>
    <div class="steps-list">
        <h5>Steps</h5>

        <b-form @submit.prevent="createStep(editTask)" class="form-inline steps-list__create-form">
            <input type="hidden" name="task_id" v-model="editTask.id">

            <b-form-input type="text" class="col-8 col-md-9" placeholder="Add step" v-model="createStepForm.name" required> </b-form-input>
            <b-button type="submit" class="steps-list__create-form_button col-4 col-md-3" variant="primary">Add</b-button>
        </b-form>

        <b-list-group v-if="steps.length > 0" class="steps-list__list">
            <b-list-group-item v-for="(step, index) in steps" :key="step.id" class="steps-list__list-item">
                <div class="steps-list__step-info">
                    <b-form-checkbox
                            value="Complete"
                            unchecked-value="Not Complete"
                            v-model="step.status"
                            @input="changeStepStatus(step)"
                            inline
                    >
                    </b-form-checkbox>
                    {{ step.name }}
                </div>
                <div class="steps-list__list-manage">
                    <a type="submit" @click="upStep(editTask, step)" class="steps-list__list-manage_link">
                        <i class="fa fa-arrow-up"> </i>
                    </a>
                    <a type="submit" @click="downStep(editTask, step)" class="steps-list__list-manage_link">
                        <i class="fa fa-arrow-down"> </i>
                    </a>
                    <a type="submit" @click="removeStep(step, index)" class="steps-list__list-manage_link">
                        <i class="fa fa-trash"> </i>
                    </a>
                </div>
            </b-list-group-item>
        </b-list-group>
        <div v-else class="steps-not-found">
            This task doesn't have any steps
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "StepsList",
        props: {
            editTask: Object,
            getList: Function,
            getTaskSteps: Function,
            steps: Array
        },
        data() {
            return {
                error: null,
                statusStepForm: {
                    'id': null,
                    'status': null
                },
                createStepForm: {
                    'task_id': null,
                    'name': null
                },
                upStepForm: {
                    'id': null
                },
                downStepForm: {
                    'id': null
                },
                removeStepForm: {
                    'id': null
                }
            }
        },
        methods: {
            changeStepStatus(step) {
                this.error = null;
                this.statusStepForm.id = step.id;
                this.statusStepForm.status = step.status;

                axios.patch('/api/todo/task/step/change-status', this.statusStepForm)
                    .then(() => {
                        this.getList();
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            createStep(task) {
                this.createStepForm.task_id = task.id;

                axios.post('/api/todo/task/step/create', this.createStepForm)
                    .then((response) => {
                        this.steps.push({
                            'id': response.data.id,
                            'task_id': task.id,
                            'name': this.createStepForm.name,
                            'status': 'Not Complete'
                        });

                        this.createStepForm.name = null;

                        this.getList();
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            upStep(task, step) {
                this.error = null;
                this.upStepForm.id = step.id;

                axios.patch('/api/todo/task/step/up', this.upStepForm)
                    .then(() => {
                        this.getTaskSteps(task);
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            downStep(task, step) {
                this.error = null;
                this.downStepForm.id = step.id;

                axios.patch('/api/todo/task/step/down', this.downStepForm)
                    .then(() => {
                        this.getTaskSteps(task);
                    })
                    .catch(error => {
                        this.error = error.response.data.error;
                        console.log(error.message);
                    });
            },
            removeStep(step, index) {
                this.error = null;
                this.removeStepForm.id = step.id;

                axios.delete('/api/todo/task/step/remove', {
                    data: this.removeStepForm
                })
                    .then(() => {
                        this.steps.splice(index, 1);
                        this.getList();
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
    .steps-list {
        margin-top: 15px;
        margin-bottom: 20px;

        &__list {
            &-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 10px;
                height: 40px;
                border-radius: 0;
            }

            &-manage {
                &_link {
                    margin-right: 5px;
                }
            }
        }

        &__step {
            &-info {
                display: flex;
                align-items: center;
            }
        }
    }

    .steps-not-found {
        margin-top: 10px;
    }
</style>