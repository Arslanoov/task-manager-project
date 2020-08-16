<template>
    <div>
        <h3>Upload background photo</h3>

        <Alert v-bind:error="error"/>

        <b-form @submit="uploadBackgroundPhoto" class="upload-photo-form">
            <b-form-group label="File" label-for="uploadFile">
                <b-form-file
                        id="uploadFile"
                        v-model="form.file"
                        aria-describedby="uploadFileError"
                        :state="errors.file ? false : null"
                        required>
                </b-form-file>
                <b-form-invalid-feedback id="uploadFileError">{{ errors.name }}</b-form-invalid-feedback>
            </b-form-group>
            <b-button type="submit" variant="primary">Upload</b-button>
        </b-form>

        <div v-if="progress">
            <b-progress :value="progress" :max="100" show-progress animated> </b-progress>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Alert from "../../components/Alert";

    export default {
        name: "Profile",
        components: {
            Alert
        },
        data() {
            return {
                error: null,
                errors: [],
                user: null,
                progress: 0,
                form: {
                    file: null
                }
            }
        },
        mounted() {
            axios.get('/api/profile')
                .then((response) => {
                    this.user = response.data.user;
                })
                .catch(error => {
                    this.error = error.response.data.error;
                    console.log(error.message);
                });
        },
        methods: {
            uploadBackgroundPhoto(event) {
                event.preventDefault();

                const component = this;
                let data = new FormData();
                data.append('file', this.form.file);

                axios
                    .post('/api/profile/upload/photo', data, {
                        onUploadProgress: function (event) {
                            component.progress = Math.round((event.loaded * 100) / event.total);
                        }
                    })
                    .then(() => {
                        this.$router.push({name: 'home'});
                    })
                    .catch(error => {
                        if (error.response) {
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

<style lang="scss">
    .upload-photo-form {
        margin-bottom: 20px;
    }
</style>