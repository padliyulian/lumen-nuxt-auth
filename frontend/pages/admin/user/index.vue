<template>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <Header title="User"/>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">User</h5>
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <button @click="addMode=true" data-target="#modal_add" data-toggle="modal" class="btn btn-info btn-block">Add User</button>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <select @change="changeLength" v-model="length" class="custom-select">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-7">
                                            <input @keyup="searchData" class="form-control" type="text" v-model="search" placeholder="Type keyword here ...">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table class="table table-responsive-md table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th @click.prevent="sortBy('name')" style="cursor:pointer;" scope="col">
                                                            <i class="fas fa-sort"></i>
                                                            Name
                                                        </th>
                                                        <th @click.prevent="sortBy('email')" style="cursor:pointer;" scope="col">
                                                            <i class="fas fa-sort"></i>
                                                            Email
                                                        </th>
                                                        <th scope="col">
                                                            Role
                                                        </th>
                                                        <th @click.prevent="sortBy('created_at')" style="cursor:pointer;" scope="col">
                                                            <i class="fas fa-sort"></i>
                                                            Created
                                                        </th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="user in data.data" :key="user.id">
                                                        <td>{{user.name}}</td>
                                                        <td>{{user.email}}</td>
                                                        <td v-if="user.roles.length">{{user.roles[0].name}}</td>
                                                        <td>{{$moment(user.created_at).format('D/MM/YYYY')}}</td>
                                                        <td>
                                                            <a @click.prevent="showEditModal(user)" href="#" title="Edit" data-target="#modal_edit" data-toggle="modal">
                                                                <span class="text-warning">
                                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                                </span>
                                                            </a>
                                                            <a @click.prevent="deleteRow(user.id)" href="#" title="Delete">
                                                                <span class="text-danger">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 d-flex flex-row justify-content-between">
                                            <div>
                                                <span class="text-secondary">
                                                    Showing {{data.from}} to {{data.to}} of {{ data.total }} Entries
                                                </span>
                                            </div>
                                            <div>
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination">
                                                        <li class="page-item" :class="{disabled: data.current_page === 1}">
                                                            <a @click.prevent="firstPage" class="page-link" href="#" aria-label="First" :aria-disabled="data.current_page === 1">
                                                                <span aria-hidden="true">&laquo;</span>
                                                            </a>
                                                        </li>

                                                        <li class="page-item" :class="{disabled: data.prev_page_url === null}">
                                                            <a @click.prevent="prevPage" class="page-link" href="#" aria-label="Previous" :aria-disabled="data.prev_page_url === null">
                                                                <span aria-hidden="true">Prev</span>
                                                            </a>
                                                        </li>
                                                        <li class="page-item" :class="{disabled: data.next_page_url === null}">
                                                            <a @click.prevent="nextPage" class="page-link" href="#" aria-label="Next" :aria-disabled="data.next_page_url === null">
                                                                <span aria-hidden="true">Next</span>
                                                            </a>
                                                        </li>


                                                        <li class="page-item" :class="{disabled: data.current_page === data.last_page}">
                                                            <a @click.prevent="lastPage" class="page-link" href="#" aria-label="Last" :aria-disabled="data.current_page === data.last_page">
                                                                <span aria-hidden="true">&raquo;</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        <add-modal v-if="addMode" :getData="getData" :hideAddModal="hideAddModal"/>
        <edit-modal v-if="editMode" :row="selectedRow" :getData="getData" :hideEditModal="hideEditModal"/>
    </div>
</template>


<script>
    import { Header } from '../../../components/layouts/admin'
    import { AddModal, EditModal } from '../../../components/admin/user'
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
            Header,
            AddModal,
            EditModal,
        },

        data() {
            return {
                length: 10,
                column: 'created_at',
                dir: 'desc',
                search: '',
                data: {},

                addMode: false,
                editMode: false,
                selectedRow: {},
            }
        },

        created() {
            this.getUser(`${process.env.apiUrl}/user?page=1&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
        },

        // mounted() {
        //     if (this.$auth.user.roles[0].id !== 1 || this.$auth.user.roles[0].id !== 2) {
        //         this.$router.push('/admin')
        //     }
        // },

        methods: {
            getUser(url) {
                this.$axios.get(url)
                    .then(res => this.data = res.data)
                    .catch(err => console.log(err))
            },

            deleteRow(id) {
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        this.$axios.delete(`${process.env.apiUrl}/user/${id}`, {
                            headers: {
                                Authorization: this.$auth.$storage._state['_token.local'],
                            }
                        })
                            .then(res => {
                                this.getData()
                            })
                            .catch(err => console.log(err))

                        swalWithBootstrapButtons.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                })
            },

            showEditModal(row) {
                this.editMode = true
                this.selectedRow = row
            },

            hideEditModal() {
                this.editMode = false,
                this.selectedRow = {}
            },

            hideAddModal() {
                this.addMode = false
            },

            getData() {
                this.getUser(`${process.env.apiUrl}/user?page=1&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            searchData() {
                this.getUser(`${process.env.apiUrl}/user?page=1&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            changeLength() {
                this.getUser(`${process.env.apiUrl}/user?page=1&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            firstPage() {
                this.getUser(`${process.env.apiUrl}/user?page=1&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            lastPage() {
                this.getUser(`${process.env.apiUrl}/user?page=${this.data.last_page}&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            prevPage() {
                this.getUser(`${this.data.prev_page_url}&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            nextPage() {
                this.getUser(`${this.data.next_page_url}&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            sortBy(key) {
                if (this.dir === 'desc') {
                    this.dir = 'asc'
                } else {
                    this.dir = 'desc'
                }
            
                this.getUser(`${this.data.first_page_url}&length=${this.length}&column=${key}&dir=${this.dir}&search=${this.search}`)
            }
        }
    }
</script>