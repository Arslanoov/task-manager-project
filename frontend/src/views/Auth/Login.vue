
<template>
	<div class="row">
		<div class="col-md-6 mx-auto">
			<div class="card">
				<div class="card-header">
					Login
				</div>
				<div class="card-body">
					<b-alert variant="danger" v-if="authFormError" show>
						{{ authFormError }}
					</b-alert>

					<b-form @submit="onSubmit">
						<b-form-group label="Email" label-for="loginEmail">
							<b-form-input
								id="loginEmail"
								type="email"
								class="text-center"
								:value="authFormUsername"
								@input="setAuthFormEmail"
								required
							> </b-form-input>
						</b-form-group>

						<b-form-group label="Password" label-for="loginPassword">
							<b-form-input
								id="loginPassword"
								type="password"
								class="text-center"
								:value="authFormPassword"
								@input="setAuthFormPassword"
								required
							> </b-form-input>
						</b-form-group>

						<b-button type="submit" variant="primary">Login</b-button>
					</b-form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapMutations, mapActions, mapGetters } from "vuex"

import {
	SET_AUTH_FORM_ERROR,
	CLEAR_AUTH_FORM_ERROR,
	SET_AUTH_FORM_USERNAME,
	SET_AUTH_FORM_PASSWORD
} from "@/store/mutations";
import { LOGIN, LOGOUT } from "@/store/actions";

import { STORE_USER_PREFIX } from "@/store/modules/user";

export default {
	name: "Login",
	computed: mapGetters({
		authFormUsername: "user/authFormUsername",
		authFormPassword: "user/authFormPassword",
		authFormError: "user/authFormError"
	}),
	methods: {
		...mapActions({
			login: STORE_USER_PREFIX + LOGIN,
			logout: STORE_USER_PREFIX + LOGOUT
		}),
		...mapMutations({
			setAuthFormEmail: STORE_USER_PREFIX + SET_AUTH_FORM_USERNAME,
			setAuthFormPassword: STORE_USER_PREFIX + SET_AUTH_FORM_PASSWORD,
			setAuthFormError: STORE_USER_PREFIX + SET_AUTH_FORM_ERROR,
			clearAuthFormError: STORE_USER_PREFIX + CLEAR_AUTH_FORM_ERROR
		}),
		onSubmit(e) {
			e.preventDefault();

			this.login()
				.then(() => {
					console.log("REDIRECT");
					// TODO: change
					//if (response.data.user.status !== "Draft") {
						this.$router.push({ name: "home" });
					/*} else {
						this.logout()
							.then(() => this.$router.push({name: "home"}));
					}*/
				});
		}
	}
}
</script>
