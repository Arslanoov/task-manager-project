<template>
    <div>
        <b-navbar toggleable="lg" type="light" variant="light">
            <b-navbar-brand :to="{name: 'home'}">
                <img src="../assets/logo.png" alt="App" width="30px" />
            </b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav class="ml-auto na">
                    <b-nav-item :to="{name: 'home'}">Home</b-nav-item>
                    <b-nav-item :to="{name: 'about'}">About</b-nav-item>

                    <template v-if="$store.getters.isLoggedIn">
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

    #nav {
        color: #0b2e13;
    }
</style>
