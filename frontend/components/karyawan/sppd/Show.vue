<template>
    <div class="modal fade" id="modal_show" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail SPPD</h5>
                    <button @click.prevent="resetView" type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h6>Ditugaskan Kepada :</h6>
                            <table class="table table-responsive-sm table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th>Nama</th>
                                        <th>Divisi</th>
                                        <th>Jabatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(karyawan, index) in row.karyawan"> 
                                        <tr v-if="karyawan.pivot.status === 'pekerja'" :key="karyawan.id">
                                            <td class="text-center">{{index+1}}</td>
                                            <td>{{karyawan.nama}}</td>
                                            <td>{{karyawan.divisi.nama}}</td>
                                            <td>{{karyawan.jabatan.nama}}</td>
                                        </tr>
                                    </template>    
                                </tbody>
                            </table>
                            <table class="table table-responsive table-borderless">
                                <tbody>
                                    <tr>
                                        <td>Tujuan</td>
                                        <td>:</td>
                                        <td>{{row.tujuan}}</td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Pekerjaan</td>
                                        <td>:</td>
                                        <td>{{row.pekerjaan}}</td>
                                    </tr>
                                    <tr>
                                        <td>Hari Kerja</td>
                                        <td>:</td>
                                        <td>
                                            {{$moment(row.mulai).format('DD MMM YYYY hh:mm')}} s.d {{$moment(row.selesai).format('DD MMM YYYY hh:mm')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Biaya</td>
                                        <td>:</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-6">
                                                    Uang Makan
                                                </div>
                                                <div class="col-6">
                                                    : {{convertToRupiah(row.biaya_makan)}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    Uang Saku
                                                </div>
                                                <div class="col-6">
                                                    : {{convertToRupiah(row.biaya_saku)}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    Uang Transport
                                                </div>
                                                <div class="col-6">
                                                    : {{convertToRupiah(row.biaya_transport)}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    Uang Penginapan
                                                </div>
                                                <div class="col-6">
                                                    : {{convertToRupiah(row.biaya_penginapan)}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    Biaya Total
                                                </div>
                                                <div class="col-6">
                                                    : {{convertToRupiah(row.biaya_total)}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    Biaya Sementara
                                                </div>
                                                <div class="col-6">
                                                    : {{convertToRupiah(row.biaya_sementara)}}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                            <div>Demikian surat ini dibuat agar dapat dilaksanakan dengan baik.</div>     
                            <div>Hajimena, {{$moment(row.created_at).format('DD MMM YYYY')}}</div> 
                            <div class="row mt-4 e-over-auto">
                                <div class="col-6 text-center">
                                    <span class="d-block">Pemberi Tugas</span>
                                    <template v-for="karyawan in row.karyawan"> 
                                        <template v-if="karyawan.pivot.status === 'pemberi_tugas'">
                                            <template v-if="karyawan.pivot.progres === 'diketahui' || karyawan.pivot.progres === 'selesai'">
                                                <div :key="karyawan.id">
                                                    <img :src="`${assetPath}/images/karyawan/${karyawan.ttd}`" alt="ttd" width="150px">
                                                    <div>{{karyawan.nama}}</div>
                                                </div>    
                                            </template>
                                            <template v-else>
                                                <div :key="karyawan.id">
                                                    <div class="mt-5">{{karyawan.nama}}</div>
                                                </div>    
                                            </template>
                                        </template>
                                    </template>  
                                    <div class="d-block">Dir/Mgr/Kabag</div>
                                </div>
                                <div class="col-6 text-center">
                                    <div>Diketahui</div>
                                    <template v-for="karyawan in row.karyawan"> 
                                        <template v-if="karyawan.pivot.status === 'diketahui'">
                                            <template v-if="karyawan.pivot.progres === 'selesai'">
                                                <div :key="karyawan.id">
                                                    <img :src="`${assetPath}/images/karyawan/${karyawan.ttd}`" alt="ttd" width="150px">
                                                    <div>{{karyawan.nama}}</div>
                                                </div>    
                                            </template>
                                            <template v-else>
                                                <div :key="karyawan.id">
                                                    <div class="mt-5">{{karyawan.nama}}</div>
                                                </div>    
                                            </template>
                                        </template>
                                    </template>  
                                    <div>Mgr / Kabag HRD</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click.prevent="resetView" type="button" class="btn btn-secondary">Close</button>
                    <button v-if="isPrint" @click.prevent="printSppd" type="button" class="btn btn-info">Print</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            row: {
                type: Object,
                default: () => ({}),
            },
            hideViewModal: {
                type: Function,
                default: () => ({}),
            },
        },

        data() {
            return {
                assetPath: '',
                isPrint: false,
            }
        },

        created() {
            this.assetPath = process.env.assetUrl
        },

        mounted() {
            this.row.karyawan.forEach(karyawan => {
                if (karyawan.pivot.progres === 'selesai') {
                    this.isPrint = true
                }
            })
        },

        methods: {
            resetView() {
                $('#modal_show').modal('hide')
                this.hideViewModal()
            },

            printSppd() {
                this.$axios({
                    url: `${process.env.apiUrl}/user/sppd/${this.row.id}`,
                    method: 'GET',
                    responseType: 'blob',
                }).then((res) => {
                    const url = window.URL.createObjectURL(new Blob([res.data], {type: 'application/pdf'}))
                    // const link = document.createElement('a')
                    // link.href = url
                    // link.setAttribute('download', 'file.pdf')
                    // document.body.appendChild(link)
                    // link.click()
                    window.open(url)
                    this.resetView()
                })
            },

            convertToRupiah(angka)
            {
                let rupiah = ''		
                let angkarev = angka.toString().split('').reverse().join('')
                for(let i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.'
                return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('')
            }
        }
    }
</script>