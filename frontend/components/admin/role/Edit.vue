<template>
    <div class="modal fade" id="modal_edit" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button type="button" class="close" @click.prevent="resetRole" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="updateRole">
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
                            <span class="mr-3">
                                <input v-model="form.removable" type="radio" :class="{ 'is-invalid': form.errors.has('removable') }" id="no" name="removable" value="0">
                                <label for="no">No</label>
                            </span>
                            <span>
                                <input v-model="form.removable" type="radio" :class="{ 'is-invalid': form.errors.has('removable') }" id="yes" name="removable" value="1">
                                <label for="yes">Yes</label>
                                <has-error :form="form" field="removable"></has-error>
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click.prevent="resetRole" type="button" class="btn btn-secondary">Close</button>
                        <button :disabled="form.busy" type="submit" class="btn btn-primary">Update</button>
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
                type: Function,
                default: () => ({}),
            },
            hideEditModal: {
                type: Function,
                default: () => ({}),
            },
            row: {
                type: Object,
                default: () => ({}),
            }
        },

        components: {
            Form, HasError, AlertError
        },

        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    guard_name: '',
                    removable: '',

                    _method: 'PATCH'
                })
            }
        },

        mounted() {
            this.form.id = this.row.id,
            this.form.name = this.row.name,
            this.form.guard_name = this.row.guard_name,
            this.form.removable = this.row.removable
        },

        methods: {
            updateRole() {
                this.form.post(`${process.env.apiUrl}/role/${this.form.id}`, {
                    headers: {
                        Authorization: this.$auth.$storage._state['_token.local'],
                    }
                })
                    .then(res => {
                        this.getData()
                        this.resetRole()
                        Toast.fire({
                            icon: 'success',
                            title: 'Updated successfully'
                        })
                    })
                    .catch(err => console.log(err))
            },

            resetRole() {
                this.form.clear()
                this.form.reset()
                this.hideEditModal()
                $('#modal_edit').modal('hide')
            }
        }
    }
</script>