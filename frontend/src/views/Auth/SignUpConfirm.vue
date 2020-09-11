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

                    <b-form @submit="signUpConfirm">
                        <p>
                            {{ form.token }}
                        </p>

                        <b-button type="submit" variant="primary">Auth and Join!</b-button>
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
                error: null,
                errors: [],
                form: {
                    token: null
                }
            }
        },
        mounted() {
            this.form.token = this.getToken();
        },
        methods: {
            signUpConfirm(event) {
                event.preventDefault();

                this.error = null;
                this.errors = [];

                axios.post('/api/auth/sign-up/confirm', this.form)
                    .then(() => {
                        this.$router.push({ name: 'auth.login' });
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
            },
            getToken() {
                let token = this.$route.params.token ?? '';
                if (!token) {
                    this.$router.push({'name': '404'});
                }

                return token;
            }
        }
    }
</script>
