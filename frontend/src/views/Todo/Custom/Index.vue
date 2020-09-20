<template>
    <CustomSchedule v-bind:id="id" />
</template>

<script>
    import CustomSchedule from "../../../components/Todo/Custom/CustomSchedule";

    export default {
        data() {
            return {
                'id': this.$route.params.id
            };
        },
        components: {
            CustomSchedule
        }
    }
</script>