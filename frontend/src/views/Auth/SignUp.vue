<template>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    Sign Up
                </div>
                <div class="card-body">
                    <b-alert variant="danger" v-if="error" show>{{ error }}</b-alert>

                    <b-form @submit="signUp">
                        <b-form-group label="Login" label-for="signUpLogin">
                            <b-form-input id="signUpLogin" class="text-center" type="text" v-model="form.login" required> </b-form-input>
                        </b-form-group>

                        <b-form-group label="Email" label-for="signUpEmail">
                            <b-form-input id="signUpEmail" class="text-center" type="email" v-model="form.email" required> </b-form-input>
                        </b-form-group>

                        <b-form-group label="Password" label-for="signUpPassword">
                            <b-form-input id="signUpPassword" class="text-center" type="password" v-model="form.password" required> </b-form-input>
                        </b-form-group>

                        <b-button type="submit" variant="primary">Join</b-button>
                    </b-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                form: {
                    login: '',
                    email: '',
                    password: '',
                },
                error: null,
                errors: []
            }
        },
        methods: {
            signUp(event) {
                event.preventDefault();

                this.error = null;
                this.errors = [];

                axios.post('/api/auth/signup', this.form)
                    .then(() => {
                        this.$store.commit('changeCurrentEmail', this.form.email);
                        this.$router.push({name: 'home'});
                    })
                    .catch(error => {
                        if (error.response) {
                            if (error.response.status === 404) {
                                this.$router.push({name: '404'});
                            }
                            if (error.response.data.error) {
                                this.error = error.response.data.error;
                            } else if (error.response.data.errors) {
                                this.errors = error.response.data.errors;
                            }
                        } else {
                            console.log(error.message);
                        }
                    });
            }
        }
    }
</script>