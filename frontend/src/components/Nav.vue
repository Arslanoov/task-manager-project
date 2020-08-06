<template>
    <div id="nav">
        <router-link to="/">Home</router-link> |
        <router-link to="/about">About</router-link> |
        <template v-if="$store.getters.isLoggedIn">
            <li class="nav-item"><a class="nav-link" @click="logout" href="">Log Out</a></li>
        </template>
        <template v-else>
            <li class="nav-item"><router-link class="nav-link" :to="{name: 'auth.login'}">Log In</router-link></li>
            <li class="nav-item"><router-link class="nav-link" :to="{name: 'auth.signup'}">Sign Up</router-link></li>
        </template>
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
                        this.$router.push({name: 'login'});
                    })
            }
        }
    }
</script>

<style scoped lang="scss">
    .api-name {
        font-weight: 700;
    }
</style>
