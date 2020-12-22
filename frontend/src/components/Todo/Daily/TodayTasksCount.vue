<template>
	<div class="today-tasks-count">
			{{ tasksCount }}
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"

import { TODO_STORE_PREFIX } from "@/store/modules/todo";
import { SIDEBAR_STORE_PREFIX } from "@/store/modules/todo/sidebar";

import { FETCH_SIDEBAR_DAILY_SCHEDULE_TASKS_COUNT } from "@/store/actions";

export default {
	name: "TodayTasksCount",
	mounted() {
		this.fetchTasksCount();
	},
	computed: mapGetters({
		tasksCount: "todo/sidebar/dailyScheduleTasksCount"
	}),
	methods: mapActions({
		fetchTasksCount: TODO_STORE_PREFIX + SIDEBAR_STORE_PREFIX + FETCH_SIDEBAR_DAILY_SCHEDULE_TASKS_COUNT
	})
}
</script>

<style lang="scss" scoped></style>
