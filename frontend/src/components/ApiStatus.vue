<template>
    <p v-if="version">
        Using API {{ version }}
    </p>
    <p v-else>
        Loading...
    </p>
</template>

<script>
    import axios from "axios";

    export default {
        name: 'ApiStatus',
        data() {
            return {
                version: null
            };
        },
        mounted() {
            axios.get('/api').then(response => {
                this.version = response.data.version
            });
        }
    }
</script>

<style scoped lang="scss">
    .api-name {
        font-weight: 700;
    }
</style>
