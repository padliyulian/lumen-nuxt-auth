<template>
    <div class="login-box">
            <div class="login-logo">
                <a href="/">Halaman Login</a>
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>
                    <div v-if="loginFailed" class="help-block text-danger text-center mb-3">
                        {{loginFailed}}
                    </div>
                    <form @submit.prevent="login">
                        <div class="input-group mb-3">
                            <input v-model="form.email" type="text" name="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="E-Mail / Username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="input-group mb-3">
                            <input v-model="form.password" type="password" name="password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <has-error :form="form" field="password"></has-error>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="remember" id="remember">
                                    <label class="custom-control-label" for="remember">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                        </div>
                    </form>

                    <div class="social-auth-links text-center mb-3">
                        <p>- OR -</p>
                        <a href="#" class="btn btn-block btn-primary">
                            <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                        </a>
                        <a href="#" class="btn btn-block btn-danger">
                            <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                        </a>
                    </div>
                    <!-- /.social-auth-links -->

                    <p class="mb-1">
                        <a href="forgot-password.html">I forgot my password</a>
                    </p>
                    <p class="mb-0">
                        <a href="#" class="text-center">Register a new membership</a>
                    </p>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
</template>


<script>
    import axios from '@nuxtjs/axios'
    import { Form, HasError, AlertError } from 'vform'
    export default {
        auth: false,
        // middleware: 'userLevel',

        head: {
            bodyAttrs: {
                class: 'hold-transition login-page'
            }
        },

        components: { Form, HasError, AlertError },

        data() {
            return {
                loginFailed: '',
                form: new Form({
                    email: '',
                    password: '',
                }),
            }
        },

        mounted() {
            this.isLogin()
        },

        methods: {
            isLogin(){
                if (this.$auth.loggedIn) {
                    this.goTo()
                }
            },

            goTo() {
                if (this.$auth.user.roles[0].id === 2) {
                    this.$router.push('/admin')
                }
                if (this.$auth.user.roles[0].id === 3) {
                    this.$router.push('/karyawan')
                }
                if (this.$auth.user.roles[0].id === 4) {
                    this.$router.push('/karyawan')
                }
            },

            login() {
                this.form.post(`${process.env.apiUrl}/login`)
                    .then(() => {
                        this.$auth.loginWith('local', { data: this.form})
                            .then(() => {
                                this.resetLogin()
                                this.goTo()
                            })
                    })    
                    .catch(err => {
                        if (err.response.data.status === false) this.loginFailed = 'Email or password wrong !'
                    })
            },

            resetLogin() {
                this.form.clear()
                this.form.reset()
                this.loginFailed = ''
            }
        }
    }
</script>
