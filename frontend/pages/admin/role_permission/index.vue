<template>
    <div class="content-wrapper">
        <Header title="Role Permissions"/>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline">
                            <!-- <div class="card-header">
                                <h5 class="m-0">User</h5>
                            </div> -->
                            <div class="card-body">
                                <div>
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select v-model="role" name="role" class="form-control" @change="getRolePermission">
                                            <option disabled value="">Please select one</option>
                                            <template v-for="role in roles">
                                                <option :key="role.id" :value="role.id">{{role.name}}</option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="roles-tab" data-toggle="tab" href="#roles" role="tab" aria-controls="roles" aria-selected="true">Roles</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="false">Users</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="role-permissions-tab" data-toggle="tab" href="#role-permissions" role="tab" aria-controls="role-permissions" aria-selected="false">Role Permissions</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade pt-3 active show" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                                                <template v-for="(row, index) in permissions">
                                                    <template v-if="row.name.includes('roles')">
                                                        <input
                                                            type="checkbox" 
                                                            class="minimal-red" 
                                                            :key="index"
                                                            :value="row.name"
                                                            :checked="role_permission.findIndex(el => el.id === row.id) != -1"
                                                            @click="addPermission(row.id)"
                                                        > {{ row.name }} <br :key="'row' + index">
                                                    </template>
                                                </template>
                                            </div>
                                            <div class="tab-pane fade pt-3" id="users" role="tabpanel" aria-labelledby="users-tab">
                                                <template v-for="(row, index) in permissions">
                                                    <template v-if="row.name.includes('users')">
                                                        <input
                                                            type="checkbox" 
                                                            class="minimal-red" 
                                                            :key="index"
                                                            :value="row.name"
                                                            :checked="role_permission.findIndex(x => x.name == row.name) != -1"
                                                            @click="addPermission(row.id)"
                                                        > {{ row.name }} <br :key="'row' + index">
                                                    </template>
                                                </template>
                                            </div>
                                            <div class="tab-pane fade pt-3" id="role-permissions" role="tabpanel" aria-labelledby="role-permissions-tab">
                                                <template v-for="(row, index) in permissions">
                                                    <template v-if="row.name.includes('role permissions')">
                                                        <input
                                                            type="checkbox" 
                                                            class="minimal-red" 
                                                            :key="index"
                                                            :value="row.name"
                                                            :checked="role_permission.findIndex(x => x.name == row.name) != -1"
                                                            @click="addPermission(row.id)"
                                                        > {{ row.name }} <br :key="'row' + index">
                                                    </template>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pull-right">
                                        <template>
                                            <button class="btn btn-primary" @click.prevent="setRolePermission">
                                                <i class="fa fa-send"></i> Update Permission
                                            </button>
                                        </template>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</template>


<script>
    import { Form } from 'vform'
    import { Header } from '../../../components/layouts/admin'
    export default {
        name: 'User',
        middleware: 'isAdmin',
        layout: 'admin',
        head: {
            bodyAttrs: {
                class: 'hold-transition sidebar-mini'
            }
        },
        components: {
            Header, Form
        },

        data() {
            return {
                role: '',

                roles: [],
                permissions: [],
                role_permission: [],

                form: new Form({
                    _method: 'PATCH',
                    new_permission: [],
                })
            }
        },

        created() {
            this.getRole()
            this.getPermission()
        },

        methods: {
            getRole() {
                this.$axios.get(`${process.env.apiUrl}/roles-list`)
                    .then(res => this.roles = res.data)
                    .catch(err => console.log(err))
            },

            getPermission() {
                this.$axios.get(`${process.env.apiUrl}/permissions-list`)
                    .then(res => this.permissions = res.data)
                    .catch(err => console.log(err))
            },

            getRolePermission() {
                this.$axios.get(`${process.env.apiUrl}/role-permissions/${this.role}`)
                    .then(res => {
                        this.role_permission = res.data
                        this.form.new_permission = []
                        this.role_permission.map(el => this.form.new_permission.push(el.id))
                    })
                    .catch(err => console.log(err))
            },

            // checkPermission() {
            //     this.getRolePermission()
            // },
            
            addPermission(id) {
                let index = this.form.new_permission.findIndex(x => x == id)
                if (index == -1) {
                    this.form.new_permission.push(id)
                    // console.log(this.form.new_permission)
                } else {
                    this.form.new_permission.splice(index, 1)
                    // console.log(this.form.new_permission)
                }
            },

            setRolePermission() {
                // console.log(this.form.new_permission)
                // let x = ['1', '2', '3']
                // console.log(x)
                // console.log(typeof(x))
                this.form.post(`${process.env.apiUrl}/role-permissions/${this.role}`, {
                    headers: {
                        Authorization: this.$auth.$storage._state['_token.local'],
                    }
                })
                    .then((res) => {
                        // this.role = ''
                        // this.form.new_permission = []
                        // this.role_permission = []
                        this.getRolePermission()
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