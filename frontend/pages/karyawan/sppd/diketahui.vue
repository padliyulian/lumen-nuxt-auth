<template>
    <div class="content-wrapper">
        <Header title="Menunggu Persetujuan HRD"/>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <select @change="changeLength" v-model="length" class="custom-select">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-8">
                                            <input @keyup="searchData" class="form-control" type="text" v-model="search" placeholder="Type keyword here ...">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table class="table table-responsive-md table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th @click.prevent="sortBy('tujuan')" style="cursor:pointer;" scope="col">
                                                            <i class="fas fa-sort"></i>
                                                            Tujuan
                                                        </th>
                                                        <th @click.prevent="sortBy('pekerjaan')" style="cursor:pointer;" scope="col">
                                                            <i class="fas fa-sort"></i>
                                                            Pekerjaan
                                                        </th>
                                                        <th @click.prevent="sortBy('created_at')" style="cursor:pointer;" scope="col">
                                                            <i class="fas fa-sort"></i>
                                                            Tgl SPPD
                                                        </th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="sppd in data.data" :key="sppd.id">
                                                        <td>{{sppd.tujuan}}</td>
                                                        <td>{{sppd.pekerjaan}}</td>
                                                        <td>{{$moment(sppd.created_at).format('D/MM/YYYY')}}</td>
                                                        <td>
                                                            <a @click.prevent="showViewModal(sppd)" href="#" title="View" data-target="#modal_show" data-toggle="modal">
                                                                <span class="text-primary">
                                                                    <i class="far fa-eye" aria-hidden="true"></i>
                                                                </span>
                                                            </a>
                                                            <a v-if="isDiketahui(sppd.karyawan).length" @click.prevent="approveSppd(sppd.id)" href="#" title="Approve">
                                                                <span class="text-success">
                                                                    <i class="far fa-check-circle" aria-hidden="true"></i>
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
        <view-modal v-if="viewMode" :row="selectedRow" :hideViewModal="hideViewModal"/>
    </div>
</template>


<script>
    import { Form } from 'vform'
    import { Header } from '../../../components/layouts/admin'
    import { ViewModal } from '../../../components/karyawan/sppd'
    export default {
        name: 'SPPD_diketahui',
        middleware: 'isKaryawan',
        layout: 'admin',
        head: {
            bodyAttrs: {
                class: 'hold-transition sidebar-mini'
            }
        },
        components: { Header, ViewModal, Form },

        data() {
            return {
                length: 10,
                column: 'created_at',
                dir: 'desc',
                search: '',
                data: {},

                viewMode: false,
                selectedRow: {},

                form: new Form({
                    _method: 'PATCH'
                })
            }
        },

        created() {
            this.getSppd(`${process.env.apiUrl}/karyawan/sppd/diketahui?page=1&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
        },

        methods: {
            isDiketahui(karyawans) {
                let diketahui = karyawans.filter(karyawan => {
                    if (karyawan.id === this.$auth.user.karyawan.id) {
                        if (karyawan.pivot.status === 'diketahui') {
                            return karyawan
                        }
                    }
                })

                return diketahui
            },

            approveSppd(id) {
                this.form.post(`${process.env.apiUrl}/karyawan/sppd/diketahui/${id}`, {
                    headers: {
                        Authorization: this.$auth.$storage._state['_token.local'],
                    }
                })
                    .then(() => {
                        this.getData()
                        Toast.fire({
                            icon: 'success',
                            title: 'Approve successfully'
                        })
                    })
                    .catch(err => console.log(err))
            },

            getData() {
                this.getSppd(`${process.env.apiUrl}/karyawan/sppd/diketahui?page=1&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            getSppd(url) {
                this.$axios.get(url)
                    .then(res => this.data = res.data)
                    .catch(err => console.log(err))
            },

            showViewModal(row) {
                this.viewMode = true
                this.selectedRow = row
            },

            hideViewModal() {
                this.viewMode = false,
                this.selectedRow = {}
            },

            searchData() {
                this.getSppd(`${process.env.apiUrl}/karyawan/sppd/diketahui?page=1&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            changeLength() {
                this.getSppd(`${process.env.apiUrl}/karyawan/sppd/diketahui?page=1&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            firstPage() {
                this.getSppd(`${process.env.apiUrl}/karyawan/sppd/diketahui?page=1&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            lastPage() {
                this.getSppd(`${process.env.apiUrl}/karyawan/sppd/diketahui?page=${this.data.last_page}&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            prevPage() {
                this.getSppd(`${this.data.prev_page_url}&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            nextPage() {
                this.getSppd(`${this.data.next_page_url}&length=${this.length}&column=${this.column}&dir=${this.dir}&search=${this.search}`)
            },

            sortBy(key) {
                if (this.dir === 'desc') {
                    this.dir = 'asc'
                } else {
                    this.dir = 'desc'
                }
            
                this.getSppd(`${this.data.first_page_url}&length=${this.length}&column=${key}&dir=${this.dir}&search=${this.search}`)
            }
        }
    }
</script>