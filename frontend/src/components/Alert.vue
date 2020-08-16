<template>
    <b-alert
            dismissible
            variant="danger"
            v-if="!isClosed && error" show
            class="error-alert"
            @dismissed="dismiss"
    >
        {{ error }}
    </b-alert>
</template>

<script>
    export default {
        name: "Alert",
        props: {
            error: String
        },
        data() {
            return {
                isClosed: false
            }
        },
        methods: {
            dismiss() {
                this.isClosed = true;
            }
        },
        watch: {
            error () {
                this.isClosed = false;
            }
        }
    }
</script>

<style lang="scss">
    .error-alert {
        position: fixed;
        bottom: 20px;
        right: 20px;
        max-height: 40%;
        width: 600px;
        max-width: 90%;
        z-index: 100;
        border-radius: 5px;
        text-align: center;
        overflow: hidden;
    }
</style>