<template>
    <div class="modal fade" id="modal_edit" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit SPPD</h5>
                    <button type="button" class="close" @click.prevent="resetSppd" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="updateSppd">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="tujuan">Tujuan</label>
                                <input v-model="form.tujuan" type="text" name="tujuan"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('tujuan') }">
                                <has-error :form="form" field="tujuan"></has-error>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="mulai">Mulai</label>
                                <input v-model="form.mulai" type="datetime-local" name="mulai"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('mulai') }">
                                <has-error :form="form" field="mulai"></has-error>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="selesai">Selesai</label>
                                <input v-model="form.selesai" type="datetime-local" name="selesai"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('selesai') }">
                                <has-error :form="form" field="selesai"></has-error>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="pekerjaan">Pekerjaan</label>
                                <textarea rows="5" name="pekerjaan" v-model="form.pekerjaan" class="form-control" :class="{ 'is-invalid': form.errors.has('pekerjaan') }" id="pekerjaan"></textarea>
                                <has-error :form="form" field="pekerjaan"></has-error>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="biaya_makan">Uang Makan</label>
                                <input @keyup="sumTotal" v-model="form.biaya_makan" type="number" name="biaya_makan"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('biaya_makan') }">
                                <has-error :form="form" field="biaya_makan"></has-error>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="biaya_saku">Uang Saku</label>
                                <input @keyup="sumTotal" v-model="form.biaya_saku" type="number" name="biaya_saku"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('biaya_saku') }">
                                <has-error :form="form" field="biaya_saku"></has-error>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="biaya_transport">Uang Transport</label>
                                <input @keyup="sumTotal" v-model="form.biaya_transport" type="number" name="biaya_transport"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('biaya_transport') }">
                                <has-error :form="form" field="biaya_transport"></has-error>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="biaya_penginapan">Uang Penginapan</label>
                                <input @keyup="sumTotal" v-model="form.biaya_penginapan" type="number" name="biaya_penginapan"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('biaya_penginapan') }">
                                <has-error :form="form" field="biaya_penginapan"></has-error>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="biaya_total">Total</label>
                                <input v-model="form.biaya_total" type="number" name="biaya_total"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('biaya_total') }" readonly>
                                <has-error :form="form" field="biaya_total"></has-error>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="biaya_sementara">Biaya Sementara</label>
                                <input v-model="form.biaya_sementara" type="number" name="biaya_sementara"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('biaya_sementara') }">
                                <has-error :form="form" field="biaya_sementara"></has-error>
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="pekerja">Pekerja List</label>
                                <select multiple name="pekerja" class="form-control" :class="{ 'is-invalid': form.errors.has('pekerja') }">
                                    <template v-for="pekerja in karyawans">
                                        <option @click.prevent="(ev) => addPekerja(pekerja.id, ev)" :key="pekerja.id" :value="pekerja.id">{{pekerja.nama}}</option>
                                    </template>
                                </select>
                                <has-error :form="form" field="pekerja"></has-error>
                                <p class="mb-1"></p>
                                <template v-if="form.pekerja_id.length">
                                    <template v-for="pekerja in karyawans">                           
                                        <span v-if="form.pekerja_id.includes(pekerja.id)" :key="pekerja.id" @click.prevent="(ev) => delPekerja(pekerja.id, ev)" class="badge badge-pill badge-secondary mr-1">{{pekerja.nama}}<i class="far fa-times-circle ml-1" style="cursor:pointer"></i></span>                    
                                    </template>
                                </template>    
                            </div> 
                            <div class="form-group col-lg-4">
                                <label for="pemberi_tugas_id">Pemberi Tugas</label>
                                <select v-model="form.pemberi_tugas_id" name="pemberi_tugas_id" class="form-control" :class="{ 'is-invalid': form.errors.has('pemberi_tugas_id') }">
                                    <option value="" disabled selected>Pilih Satu</option>
                                    <template v-for="karyawan in karyawans">
                                        <option :key="karyawan.id" :value="karyawan.id">{{karyawan.nama}}</option>
                                    </template>
                                </select>
                                <has-error :form="form" field="pemberi_tugas_id"></has-error>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="diketahui_id">Diketahui</label>
                                <select v-model="form.diketahui_id" name="diketahui_id" class="form-control" :class="{ 'is-invalid': form.errors.has('diketahui_id') }">
                                    <option value="" disabled selected>Pilih Satu</option>
                                    <template v-for="karyawan in karyawans">
                                        <option :key="karyawan.id" :value="karyawan.id">{{karyawan.nama}}</option>
                                    </template>
                                </select>
                                <has-error :form="form" field="diketahui_id"></has-error>
                            </div>

                        </div>
                    </div> 
                    <div class="modal-footer">
                        <button @click.prevent="resetSppd" type="button" class="btn btn-secondary">Close</button>
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
            Form, HasError, AlertError,
        },

        data() {
            return {
                karyawans: [],
                form: new Form({
                    id: '',
                    tujuan: '',
                    pekerjaan: '',
                    mulai: '',
                    selesai: '',
                    biaya_makan: 0,
                    biaya_saku: 0,
                    biaya_transport: 0,
                    biaya_penginapan: 0,
                    biaya_total: 0,
                    biaya_sementara: 0,

                    pekerja: '',
                    pekerja_id: [],
                    pemberi_tugas_id: '',
                    diketahui_id: '',

                    _method: 'PATCH'
                })
            }
        },

        created() {
            this.getKaryawan()
        },

        mounted() {
            this.form.id = this.row.id
            this.form.tujuan = this.row.tujuan
            this.form.pekerjaan = this.row.pekerjaan
            this.form.mulai = this.$moment(this.row.mulai).format('YYYY-MM-DDThh:mm')
            this.form.selesai = this.$moment(this.row.selesai).format('YYYY-MM-DDThh:mm')
            this.form.biaya_makan = this.row.biaya_makan
            this.form.biaya_saku = this.row.biaya_saku
            this.form.biaya_transport = this.row.biaya_transport
            this.form.biaya_penginapan = this.row.biaya_penginapan
            this.form.biaya_total = this.row.biaya_total
            this.form.biaya_sementara = this.row.biaya_sementara

            // pekerja,pemberi tugas, & diketahui
            this.row.karyawan.map(el => {
                if (el.pivot.status == 'pekerja') {
                    this.form.pekerja_id.push(el.id)
                }
                if (el.pivot.status == 'pemberi_tugas') {
                    this.form.pemberi_tugas_id = el.id
                }
                if (el.pivot.status == 'diketahui') {
                    this.form.diketahui_id = el.id
                }
            })
            setTimeout(() => {
                this.form.pekerja_id.map(el => $(`select[name='pekerja'] option[value=${el}]`).attr('disabled', true))
            }, 1000)
        },

        methods: {
            sumTotal() {
                this.form.biaya_total = parseInt(this.form.biaya_makan)+parseInt(this.form.biaya_saku)+parseInt(this.form.biaya_transport)+parseInt(this.form.biaya_penginapan)
            },

            getKaryawan() {
                this.$axios.get(`${process.env.apiUrl}/list/karyawan`)
                    .then(res => this.karyawans = res.data)
                    .catch(err => console.log(err))
            },

            checkIsValid() {
                if (this.form.pekerja_id.length) {
                    this.form.pekerja = 'valid'
                } else {
                    this.form.pekerja = ''
                }
            },

            updateSppd() {
                this.checkIsValid()
                this.form.post(`${process.env.apiUrl}/user/sppd/${this.form.id}`, {
                    headers: {
                        Authorization: this.$auth.$storage._state['_token.local'],
                    }
                })
                    .then(res => {
                        this.getData()
                        this.resetSppd()
                        Toast.fire({
                            icon: 'success',
                            title: 'Added successfully'
                        })
                    })
                    .catch(err => console.log(err))
            },

            resetSppd() {
                this.form.clear()
                this.form.reset()
                this.hideEditModal()
                $('select[name="pekerja"] option').attr('disabled', false)
                $('#modal_edit').modal('hide')
            },

            // pekerja
            addPekerja(id, ev) {
                this.form.pekerja_id.push(id)
                ev.target.setAttribute('disabled', '')
            },
            delPekerja(id, ev) {
                this.form.pekerja_id.splice(this.form.pekerja_id.indexOf(id), 1)
                $(`select[name='pekerja'] option[value=${id}]`).attr('disabled', false)
            },
        },
    }
</script>