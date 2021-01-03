<template>
	<b-alert
		dismissible
		:variant="alertStatusList[type]"
		v-if="error" show
		class="alert"
		@dismissed="closeAlert"
	>
		{{ error }}
	</b-alert>
</template>

<script>
import alertStatusList from "@/enums/alertStatusList"

import { STORE_ALERT_PREFIX } from "@/store/modules/alert";
import { CLEAR_ALERT } from "@/store/mutations";

import { mapGetters, mapMutations } from "vuex"

export default {
	name: "Alert",
	data: () => {
		return {
			alertStatusList
		}
	},
	computed: mapGetters({
		error: "alert/message",
		type: "alert/type"
	}),
	methods: mapMutations({
		closeAlert: STORE_ALERT_PREFIX + CLEAR_ALERT
	})
}
</script>

<style lang="scss" scoped>
.alert {
	position: fixed;
	overflow: hidden;

	bottom: 20px;
	right: 20px;

	z-index: 10;

	border-radius: 5px;
	text-align: center;
}
</style>
