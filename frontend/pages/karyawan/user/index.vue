<template>
    <div class="content-wrapper">
        <Header title="Profil User"/>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                            </div>
                            <form @submit.prevent="updateUser" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label for="name">Nama</label>
                                            <input v-model="form.name" type="text" name="name"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Name">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label for="sex">Jenis Kelamin</label>
                                            <select v-model="form.sex" name="sex" class="form-control" :class="{ 'is-invalid': form.errors.has('sex') }">
                                                <option disabled value="">Sex</option>
                                                <option value="M">Male</option>
                                                <option value="F">Female</option>
                                            </select>
                                            <has-error :form="form" field="sex"></has-error>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label for="email">Email</label>
                                            <input v-model="form.email" type="email" name="email"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Email">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="phone">Phone</label>
                                            <input v-model="form.phone" type="text" name="phone" maxlength="15"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }" placeholder="Phone">
                                            <has-error :form="form" field="phone"></has-error>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="avatar">Photo</label>
                                            <div class="custom-file">
                                                <input @change="selectFile" type="file" name="avatar" id="avatar" class="custom-file-input" :class="{ 'is-invalid': form.errors.has('avatar') }" accept="image/jpg,image/jpeg,image/png">
                                                <label class="custom-file-label" for="avatar">Choose photo ...</label>
                                                <has-error :form="form" field="avatar"></has-error>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label for="username">Username</label>
                                            <input v-model="form.username" type="text" name="username"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('username') }" placeholder="Username">
                                            <has-error :form="form" field="username"></has-error>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label for="password">Password</label>
                                            <input v-model="form.password" type="password" name="password"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Password">
                                            <has-error :form="form" field="password"></has-error>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label for="password_confirmation">Password Confirmation</label>
                                            <input v-model="form.password_confirmation" type="password" name="password_confirmation" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Retry password">
                                            <has-error :form="form" field="password_confirmation"></has-error>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button :disabled="form.busy" type="submit" class="btn btn-info">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>    
                        </div>
                    </div>
                </div>    
            </div>
        </div>

    </div>
</template>


<script>
    import { objectToFormData } from 'object-to-formdata'
    import { Form, HasError, AlertError } from 'vform'
    import { Header } from '../../../components/layouts/admin'
    export default {
        name: 'user_profil',
        middleware: 'isKaryawan',
        layout: 'admin',
        head: {
            bodyAttrs: {
                class: 'hold-transition sidebar-mini'
            }
        },

        components: { Header, Form, HasError, AlertError },

        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    sex: '',
                    email: '',
                    phone: '',
                    avatar: '',
                    username: '',
                    password: '',
                    password_confirmation: '',
                    _method: 'PATCH'
                })
            }
        },

        created() {
            this.getUser()
        },

        methods: {
            getUser() {
                this.$axios.get(`${process.env.apiUrl}/karyawan/user`)
                    .then(res => this.showUser(res.data))
                    .catch(err => console.log(err))
            },

            showUser(user) {
                this.form.id = user.id
                this.form.name = user.name
                if (user.sex) {
                    this.form.sex = user.sex
                }
                this.form.email = user.email
                this.form.phone = user.phone
                this.form.username = user.username
            },

            selectFile(e) {
                const file = e.target.files[0]
                this.form.avatar = file
            },

            updateUser() {
                this.form.post(`${process.env.apiUrl}/karyawan/user/${this.form.id}`, {
                    headers: {
                        Authorization: this.$auth.$storage._state['_token.local'],
                    },
                    transformRequest: [function (data, headers) {
                        return objectToFormData(data)
                    }]
                })
                    .then(res => {
                        this.getUser()
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated successfully'
                        })
                    })
                    .catch(err => console.log(err))
            },
        }
    }
</script>