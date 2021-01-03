<template>
	<div class="row">
		<div class="col-md-6 mx-auto">
			<div class="card">
				<div class="card-header">
					Confirm Sign Up
				</div>
				<div class="card-body">
					<b-alert variant="danger" v-if="error" show>{{ error }}</b-alert>

					<p>Welcome to Furious TODO!</p>

					<b-form @submit.prevent="onSubmit">
						<p>{{ token }}</p>

						<b-button type="submit" variant="primary">Auth and Join!</b-button>
					</b-form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from "vuex"

import { STORE_USER_PREFIX } from "@/store/modules/user"

import {
	CONFIRM_SIGN_UP
} from "@/store/actions"

import {SET_CONFIRM_SIGN_UP_TOKEN} from "@/store/mutations"

export default {
	name: "SignUpConfirm",
	mounted() {
		this.setToken(this.getToken());
	},
	computed: mapGetters({
		token: "user/confirmSignUpFormToken",
		error: "user/confirmSignUpFormError"
	}),
	methods: {
		...mapActions({
			signUpConfirm: STORE_USER_PREFIX + CONFIRM_SIGN_UP
		}),
		...mapMutations({
			setToken: STORE_USER_PREFIX + SET_CONFIRM_SIGN_UP_TOKEN
		}),
		onSubmit() {
			this.signUpConfirm()
				.then(() => this.$router.push({ name: 'auth.login' }))
				.catch(error => {
					if (error.response && error.response.status === 404) {
						this.$router.push({name: '404'})
					}
				})
		},
		getToken() {
			let token = this.$route.params.token ?? ''
			if (!token) {
					this.$router.push({'name': '404'})
			}

			return token
		}
	}
}
</script>
