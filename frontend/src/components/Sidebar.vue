<template>
    <div>
        <b-sidebar id="sidebar-variant" text-variant="light">
            <b-navbar-nav class="ml-auto na">
                <b-nav-item :to="{name: 'home'}">
                    <div class="nav-item__name">
                        <i class="fa fa-home"> </i>
                        Home
                    </div>
                </b-nav-item>

                <template v-if="$store.getters['user/isLoggedIn']">
                    <b-nav-item :to="{name: 'profile'}">
                        <div class="nav-item__name">
                            <i class="fa fa-user"> </i>
                            Profile
                        </div>
                    </b-nav-item>

                    <b-nav-item :to="{name: 'todo.main'}">
                        <div class="nav-item__name">
                            <i class="fa fa-list-ul"> </i>
                            Tasks
                        </div>
                        <TasksCount/>
                    </b-nav-item>

                    <b-nav-item :to="{name: 'todo.daily'}">
                        <div class="nav-item__name">
                            <i class="fa fa-calendar"> </i>
                            Daily tasks
                        </div>
                        <TodayTasksCount/>
                    </b-nav-item>

                    <CustomSchedulesList/>
                </template>
            </b-navbar-nav>
        </b-sidebar>
    </div>
</template>

<script>
    import TasksCount from "./Todo/Main/TasksCount";
    import TodayTasksCount from "./Todo/Daily/TodayTasksCount";
    import CustomSchedulesList from "./Todo/Custom/CustomSchedulesList";

    export default {
        name: "Sidebar",
        components: {
            TasksCount,
            TodayTasksCount,
            CustomSchedulesList
        }
    }
</script>

<style lang="scss">
    #sidebar-variant {
        margin-top: $nav-height !important;
        padding: 0 20px;
        background: $sidebar-color;
        text-shadow: none !important;
        width: $sidebar-width;
        height: $sidebar-height;

        .nav-link {
            display: flex;
            justify-content: space-between;
        }

        a {
            text-decoration: none;
            text-align: left;
            color: $sidebar-font-color;
            font-size: $sidebar-font-size;
            font-weight: $sidebar-font-weight;
        }
    }

    .nav-item__name i {
        margin-right: 5px;
    }
</style>
