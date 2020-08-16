<template>
    <b-alert
            show dismissible
            variant="danger"
            v-if="error"
            class="error-alert"
    >
        {{ error }}
    </b-alert>
</template>

<script>
    export default {
        name: "Alert",
        props: {
            error: String
        }
    }
</script>

<style lang="scss">
    .error-alert {
        position: fixed;
        bottom: 20px;
        right: 20px;
        max-height: 150px;
        max-width: 600px;
        z-index: 100;
        border-radius: 5px;
        overflow: hidden;
    }
</style>