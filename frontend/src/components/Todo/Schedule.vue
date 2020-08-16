<template>
    <div>
        <div class="schedule-list__hide-completed-form">
            <div class="schedule-list__hide-completed-form_group">
                <span class="schedule-list__hide-completed-form_group-label">Hide completed</span>
                <b-form-checkbox
                        value="hidden"
                        unchecked-value="visible"
                        v-model="sort.completedTasksVisibility"
                        @input="changeCompletedTasksVisibility"
                        checked="checked"
                        class="schedule-list__hide-completed-form_group-checkbox"
                        inline
                >
                </b-form-checkbox>
            </div>
        </div>

        <div class="schedule-list__sort-form row">
            <div class="col-sm-12">
                <b-form-select
                        v-model="sort.selected"
                        :options="sort.options"
                        :aria-selected="sort.selected"
                        @change="sortList"
                > </b-form-select>
            </div>
        </div>

        <TasksList
                v-bind:schedule="schedule"
                v-bind:getList="getList"
                v-bind:sortList="sortList"
                v-bind:createForm="createForm"
                v-bind:changeCompletedTasksVisibility="changeCompletedTasksVisibility"
        />
    </div>
</template>

<script>
    import TasksList from "./TasksList";

    export default {
        name: "Schedule",
        components: {
            TasksList
        },
        props: {
            schedule: Object,
            getList: Function
        },
        mounted() {
            if (localStorage.sortMethod) {
                this.sort.selected = localStorage.sortMethod;
            }
        },
        data() {
            return {
                createForm: {
                    'name': null,
                    'schedule_id': null,
                    'level': null
                },
                sort: {
                    selected: 'latest',
                    options: {
                        'latest': 'Latest',
                        'oldest': 'Oldest',
                        'important': 'Important',
                        'less_important': 'Less important',
                        'completed_first': 'Completed first',
                        'uncompleted_first': 'Uncompleted first'
                    },
                    levels: {
                        'Not Important': 0,
                        'Important': 1,
                        'Very Important': 2
                    },
                    statuses: {
                        'Not Complete': 0,
                        'Complete': 1
                    },
                    completedTasksVisibility: 'hidden'
                }
            }
        },
        methods: {
            init(checkVisibility, today = false) {
                this.schedule = this.$parent.schedule;
                this.sortList();
                if (checkVisibility) {
                    if (localStorage.completedTasksVisibility) {
                        this.sort.completedTasksVisibility = localStorage.completedTasksVisibility;
                    }
                    this.changeCompletedTasksVisibility(today);
                }
            },
            sortList() {
                 localStorage.sortMethod = this.sort.selected;

                if (this.sort.selected === 'latest') {
                    this.sortListByLatest();
                }

                if (this.sort.selected === 'oldest') {
                    this.sortListByOldest();
                }

                if (this.sort.selected === 'important') {
                    this.sortListByImportant();
                }

                if (this.sort.selected === 'less_important') {
                    this.sortListByLessImportant();
                }

                if (this.sort.selected === 'completed_first') {
                    this.sortListByCompleted();
                }

                if (this.sort.selected === 'uncompleted_first') {
                    this.sortListByUncompleted();
                }
            },
            sortListByLatest() {
                this.schedule.tasks.sort((a, b) => ('' + b.id).localeCompare(a.id));
            },
            sortListByOldest() {
                this.schedule.tasks.sort((a, b) => ('' + a.id).localeCompare(b.id));
            },
            sortListByImportant() {
                this.schedule.tasks.sort((a, b) => this.sort.levels[b.importantLevel] - this.sort.levels[a.importantLevel]);
            },
            sortListByLessImportant() {
                this.schedule.tasks.sort((a, b) => this.sort.levels[a.importantLevel] - this.sort.levels[b.importantLevel]);
            },
            sortListByCompleted() {
                this.schedule.tasks.sort((a, b) => this.sort.statuses[b.status] - this.sort.statuses[a.status]);
            },
            sortListByUncompleted() {
                this.schedule.tasks.sort((a, b) => this.sort.statuses[a.status] - this.sort.statuses[b.status]);
            },
            changeCompletedTasksVisibility(today = false) {
                localStorage.completedTasksVisibility = this.sort.completedTasksVisibility;

                if (this.sort.completedTasksVisibility === 'hidden') {
                    this.removeCompleted();
                }

                if (this.sort.completedTasksVisibility === 'visible') {
                    this.returnCompleted(today);
                }
            },
            removeCompleted() {
                let tasksCount = this.schedule.tasks.length;

                for (let i = 0; i < tasksCount; i++) {
                    if (this.schedule.tasks[i] && this.schedule.tasks[i].status === 'Complete') {
                        this.schedule.tasks.splice(i, 1);
                        i--;
                    }
                }
            },
            returnCompleted(today = false) {
                this.getList(false, today);
            }
        }
    }
</script>

<style lang="scss">
    .schedule-list {
        &__header {
            font-size: $schedule-header-size;
        }

        &__hide-completed-form {
            margin-bottom: 15px;

            &_group {
                display: flex;
                justify-content: center;
                align-items: center;

                &-label {
                    margin-right: 5px;
                }

                &-checkbox {
                    margin-right: -1rem;
                }
            }
        }
    }
</style>