<template>
    <div>
        <b-navbar id="navbar" text-variant="light">
            <b-navbar-brand>
                <b-button class="sidebar-toggler" v-b-toggle.sidebar-variant>
                    <i class="fa fa-bars"> </i>
                </b-button>
                <span class="project-name">
                    To Do
                </span>
            </b-navbar-brand>

            <b-collapse id="nav-collapse" text is-nav>
                <b-navbar-nav class="ml-auto na">
                    <template v-if="$store.getters['user/isLoggedIn']">
                        <b-nav-item @click="logout" href="">Log Out</b-nav-item>
                    </template>
                    <template v-else>
                        <b-nav-item :to="{name: 'auth.login'}">Log in</b-nav-item>
                        <b-nav-item :to="{name: 'auth.signup'}">Sign Up</b-nav-item>
                    </template>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
    export default {
        name: 'Nav',
        methods: {
            logout(event) {
                event.preventDefault();
                this.$store.dispatch('logout')
                    .then(() => {
                        this.$router.push({name: 'auth.login'});
                    })
            }
        }
    }
</script>

<style scoped lang="scss">
    .api-name {
        font-weight: 700;
    }

    #navbar {
        height: $nav-height;
        background-color: $project-color;
    }

    #navbar {
        .project-name, a {
            color: white;
        }
    }

    .sidebar-toggler {
        background-color: $project-color;
        border: 0;
    }

    .sidebar-toggler:hover {
        background-color: $project-color;
        border: 0;
    }

    .btn-secondary:focus {
        background: $project-color !important;
        border: 0;
    }

    .btn-secondary:active {
        background: $project-color !important;
        border: 0;
    }
</style>
