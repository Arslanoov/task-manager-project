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

                <template v-if="$store.getters.isLoggedIn">
                    <b-nav-item :to="{name: 'todo.main'}">
                        <div class="nav-item__name">
                            <i class="fa fa-edit"> </i>
                            Tasks
                        </div>
                        <TasksCount/>
                    </b-nav-item>
                </template>
            </b-navbar-nav>
        </b-sidebar>
    </div>
</template>

<script>
    import TasksCount from "./Todo/Main/TasksCount";

    export default {
        name: "Sidebar",
        components: {
            TasksCount
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
</style>