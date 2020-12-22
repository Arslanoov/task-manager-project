<template>
	<div class="custom-schedules">
		<b-nav-item class="nav-item__header">
			<div class="nav-item__name">
				Custom lists
			</div>
		</b-nav-item>

		<b-alert variant="danger" v-if="error" show>{{ error }}</b-alert>

		<b-nav-item
			v-for="schedule in customSchedules"
			:to="{ name: 'todo.custom', params: { id: schedule.id }}"
			:key="schedule.id"
		>
			<div class="nav-item__name">
				<i class="fa fa-list-ol"> </i>
				{{ schedule.name }}
			</div>
			<a @click.prevent="removeCustomSchedule(schedule.id)">
				<i class="fa fa-trash"> </i>
			</a>
		</b-nav-item>

		<form class="form-inline schedule-create-form" @submit.prevent="onAdd">
			<input
				type="text"
				class="schedule-create-form__input"
				placeholder="Create schedule"
				@input="e => setName(e.target.value)"
				:value="name"
				required>
			<button type="submit" class="add-schedule-button">
				<i class="fa fa-plus"> </i>
			</button>
		</form>
	</div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex"

import { STORE_ALERT_PREFIX } from "@/store/modules/alert";
import { TODO_STORE_PREFIX } from "@/store/modules/todo";
import { SIDEBAR_STORE_PREFIX } from "@/store/modules/todo/sidebar";

import alertStatusList from "@/enums/alertStatusList";

import {
	SET_ALERT,
	SET_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_NAME
} from "@/store/mutations";

import {
	ADD_SIDEBAR_CUSTOM_SCHEDULE,
	FETCH_SIDEBAR_CUSTOM_SCHEDULES_LIST,
	REMOVE_SIDEBAR_CUSTOM_SCHEDULE
} from "@/store/actions";

export default {
	name: "CustomSchedulesList",
	mounted() {
		this.fetchCustomSchedules()
	},
	computed: mapGetters({
		customSchedules: "todo/sidebar/customSchedules",
		error: "todo/sidebar/customScheduleCreateFormError",
		name: "todo/sidebar/customScheduleCreateFormName"
	}),
	methods: {
		...mapActions({
			fetchCustomSchedules: TODO_STORE_PREFIX + SIDEBAR_STORE_PREFIX + FETCH_SIDEBAR_CUSTOM_SCHEDULES_LIST,
			addCustomSchedule: TODO_STORE_PREFIX + SIDEBAR_STORE_PREFIX + ADD_SIDEBAR_CUSTOM_SCHEDULE,
			removeCustomSchedule: TODO_STORE_PREFIX + SIDEBAR_STORE_PREFIX + REMOVE_SIDEBAR_CUSTOM_SCHEDULE
		}),
		...mapMutations({
			setName: TODO_STORE_PREFIX + SIDEBAR_STORE_PREFIX + SET_SIDEBAR_CUSTOM_SCHEDULE_CREATE_FORM_NAME,
			setAlertMessage: STORE_ALERT_PREFIX + SET_ALERT
		}),
		onAdd() {
			this.addCustomSchedule()
				.then(() => this.setAlertMessage({
					type: alertStatusList.SUCCESS,
					message: "Успешно создан кастомный todo"
				}))
				.catch(error => this.setAlertMessage({
					type: alertStatusList.ERROR,
					message: error.response.data.error
				}))
		}
	}
}
</script>

<style lang="scss">
.schedule-create-form {
	display: flex;
	justify-content: space-between;

	&__input {
		height: 35px;
		font-weight: 300;
		font-size: 14px;
		border: 0;
		background: $sidebar-background;
	}

	&__input:focus {
		outline: none;
		border: 0;
	}
}

.nav-item {
	&__header {
		margin-top: 30px;

		.nav-item__name {
			font-weight: 500;
		}
	}
}

.add-schedule-button {
	background: transparent;
	padding: 0;
	border: 0;
	outline: 0;

	&:hover {
		cursor: pointer;
	}
}
</style>
