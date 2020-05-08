<template>
    <div class="modal fade" id="modal_add" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Role</h5>
                    <button type="button" class="close" @click.prevent="resetRole" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="addRole">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input v-model="form.name" type="text" name="name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Role name">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="guard_name">Guard Name</label>
                            <input v-model="form.guard_name" type="text" name="guard_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('guard_name') }" placeholder="web">
                            <has-error :form="form" field="guard_name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Removable</label><br>
                            <div class="custom-control custom-radio float-left mr-4 pr-4">
                                <input v-model="form.removable" type="radio" class="form-control custom-control-input" :class="{ 'is-invalid': form.errors.has('removable') }" id="no" name="no" value="0">
                                <label class="custom-control-label" for="no">No</label>
                            </div>
                            <div class="custom-control custom-radio ml-4 pl-4">
                                <input v-model="form.removable" type="radio" class="form-control custom-control-input" :class="{ 'is-invalid': form.errors.has('removable') }" id="yes" name="yes" value="1">
                                <label class="custom-control-label" for="yes">Yes</label>
                                <has-error :form="form" field="removable"></has-error>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click.prevent="resetRole" type="button" class="btn btn-secondary">Close</button>
                        <button :disabled="form.busy" type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


<script>
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
            AlertError
        },

        data() {
            return {
                form: new Form({
                    name: '',
                    guard_name: '',
                    removable: ''
                })
            }
        },

        methods: {
            addRole() {
                this.form.post(`${process.env.apiUrl}/role`, {
                    headers: {
                        Authorization: this.$auth.$storage._state['_token.local'],
                    }
                })
                    .then(res => {
                        this.getData()
                        this.resetRole()
                        Toast.fire({
                            icon: 'success',
                            title: 'Added successfully'
                        })
                        console.log('ok')
                    })
                    .catch(err => console.log(err))
            },

            resetRole() {
                this.form.clear()
                this.form.reset()
                this.hideAddModal()
                $('#modal_add').modal('hide')
            }
        }
    }
</script>