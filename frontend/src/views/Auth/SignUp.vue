<template>
	<div class="row">
		<div class="col-md-6 mx-auto">
			<div class="card">
				<div class="card-header">
					Sign Up
				</div>
				<div class="card-body">
					<b-alert variant="danger" v-if="error" show>{{ error }}</b-alert>

					<b-form @submit.prevent="signUp">
						<b-form-group label="Login" label-for="signUpLogin">
							<b-form-input id="signUpLogin" class="text-center" type="text" @input="setLogin" :value="login" required> </b-form-input>
						</b-form-group>

						<b-form-group label="Email" label-for="signUpEmail">
							<b-form-input id="signUpEmail" class="text-center" type="email" @input="setEmail" :value="email" required> </b-form-input>
						</b-form-group>

						<b-form-group label="Password" label-for="signUpPassword">
							<b-form-input id="signUpPassword" class="text-center" type="password" @input="setPassword" :value="password" required> </b-form-input>
						</b-form-group>

						<b-button type="submit" variant="primary">Join</b-button>
					</b-form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import {
	SET_SIGN_UP_FORM_LOGIN,
	SET_SIGN_UP_FORM_EMAIL,
	SET_SIGN_UP_FORM_PASSWORD
} from "@/store/mutations"

import { SIGN_UP } from "@/store/actions"

import { STORE_USER_PREFIX } from "@/store/modules/user"

import { mapMutations, mapActions, mapGetters } from "vuex"

export default {
	data() {
		return {
			// TODO: CHANGE
			errors: []
		}
	},
	computed: mapGetters({
		login: "user/signUpFormLogin",
		email: "user/signUpFormEmail",
		password: "user/signUpFormPassword",
		error: "user/signUpFormError"
	}),
	methods: {
		...mapMutations({
			setLogin: STORE_USER_PREFIX + SET_SIGN_UP_FORM_LOGIN,
			setEmail: STORE_USER_PREFIX + SET_SIGN_UP_FORM_EMAIL,
			setPassword: STORE_USER_PREFIX + SET_SIGN_UP_FORM_PASSWORD
		}),
		...mapActions({
			signUp: STORE_USER_PREFIX + SIGN_UP
		}),
		signUp() {
			this.errors = []

			this.signUp()
				.then(() => this.$router.push({name: 'home'}))
				.catch(response => {
					if (response.status === 404) {
						this.$router.push({name: '404'})
					}
				})
		}
	}
}
</script>

<style lang="scss" scoped></style>
