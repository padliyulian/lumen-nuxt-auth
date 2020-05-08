<template>
    <div class="modal fade" id="modal_add" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="close" @click.prevent="resetUser" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="addUser" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <input v-model="form.name" type="text" name="name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Name">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group col-lg-4">
                                <select v-model="form.sex" name="sex" class="form-control" :class="{ 'is-invalid': form.errors.has('sex') }">
                                    <option disabled value="">Sex</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                                <has-error :form="form" field="sex"></has-error>
                            </div>
                            <div class="form-group col-lg-4">
                                <input v-model="form.email" type="email" name="email"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Email">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group col-lg-4">
                                <input v-model="form.phone" type="text" name="phone" maxlength="15"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }" placeholder="Phone">
                                <has-error :form="form" field="phone"></has-error>
                            </div>
                            <div class="form-group col-lg-4">
                                <div class="custom-file">
                                    <input @change="selectFile" type="file" name="avatar" id="avatar" class="custom-file-input" :class="{ 'is-invalid': form.errors.has('avatar') }" accept="image/jpg,image/jpeg,image/png">
                                    <label class="custom-file-label" for="avatar">Choose photo ...</label>
                                    <has-error :form="form" field="avatar"></has-error>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <input v-model="form.username" type="text" name="username"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('username') }" placeholder="Username">
                                <has-error :form="form" field="username"></has-error>
                            </div>
                            <div class="form-group col-lg-4">
                                <input v-model="form.password" type="password" name="password"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Password">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                            <div class="form-group col-lg-4">
                                <input v-model="form.password_confirmation" type="password" name="password_confirmation" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Retry password">
                                <has-error :form="form" field="password_confirmation"></has-error>
                            </div>
                            <div class="form-group col-lg-4">
                                <select v-model="form.role" name="role" class="form-control" :class="{ 'is-invalid': form.errors.has('role') }">
                                    <option disabled value="">Role</option>
                                    <template v-for="role in roles">
                                        <option :key="role.id" :value="role.name">{{ role.name }}</option>
                                    </template>
                                </select>
                                <has-error :form="form" field="role"></has-error>
                            </div>
                        </div>
                    </div>    
                    <div class="modal-footer">
                        <button @click.prevent="resetUser" type="button" class="btn btn-secondary">Close</button>
                        <button :disabled="form.busy" type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


<script>
    import { objectToFormData } from 'object-to-formdata'
    import { Form, HasError, AlertError } from 'vform'
    export default {
        props: {
            getData: {
                type:Function,
                default: () => ({}),
            },
            hideAddModal: {
                type: Function,
                default: () => ({}),
            },
        },

        components: {
            Form,
            HasError,
            AlertError,
        },

        data() {
            return {
                roles: [],
                form: new Form({
                    name: '',
                    sex: '',
                    email: '',
                    phone: '',
                    avatar: '',
                    username: '',
                    password: '',
                    password_confirmation: '',
                    role: ''
                })
            }
        },

        created() {
            this.getRole()
        },

        methods: {
            selectFile(e) {
                const file = e.target.files[0]
                this.form.avatar = file
            },

            getRole() {
                this.$axios.get(`${process.env.apiUrl}/role-list`)
                    .then(res => this.roles = res.data)
                    .catch(err => console.log(err))
            },

            addUser() {
                this.form.post(`${process.env.apiUrl}/user`, {
                    headers: {
                        Authorization: this.$auth.$storage._state['_token.local'],
                    },
                    transformRequest: [function (data, headers) {
                        return objectToFormData(data)
                    }]
                })
                    .then(res => {
                        this.getData()
                        this.resetUser()
                        Toast.fire({
                            icon: 'success',
                            title: 'Added successfully'
                        })
                        console.log('ok')
                    })
                    .catch(err => console.log(err))
            },

            resetUser() {
                this.form.clear()
                this.form.reset()
                this.hideAddModal()
                $('input[name="avatar"]').val('')
                $('#modal_add').modal('hide')
            }
        }
    }
</script>