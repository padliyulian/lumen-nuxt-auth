<template>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">e-office</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img v-if="$auth.user.avatar" :src="`${assetPath}/images/user/${$auth.user.avatar}`" class="img-circle elevation-2" alt="user">
                    <img v-else :src="`${assetPath}/images/user/default.jpg`" class="img-circle elevation-2" alt="user">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{$auth.user.name}}</a>
                </div>
            </div>

            <!-- menu admin & superadmin -->
            <nav v-if="isAdmin" class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <nuxt-link to="/admin" class="nav-link link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </nuxt-link>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link link">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                User
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <nuxt-link to="/admin/role" class="nav-link sub-link">
                                    <i class="fas fa-user-shield nav-icon"></i>
                                    <p>Role</p>
                                </nuxt-link>
                            </li>
                            <li class="nav-item">
                                <nuxt-link to="/admin/user" class="nav-link sub-link">
                                    <i class="fas fa-users nav-icon"></i>
                                    <p>User</p>
                                </nuxt-link>
                            </li>
                            <li class="nav-item">
                                <nuxt-link to="/admin/role_permission" class="nav-link sub-link">
                                    <i class="fas fa-user-shield nav-icon"></i>
                                    <p>Permission</p>
                                </nuxt-link>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a @click.prevent="logout" class="nav-link link" href="#">
                            <i class="nav-icon fas fa-power-off"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- menu user -->
            <nav v-if="isKaryawan" class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <nuxt-link to="/karyawan" class="nav-link link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </nuxt-link>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link link">
                            <i class="nav-icon fas fa-file-contract"></i>
                            <p>
                                SPPD
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <nuxt-link to="/karyawan/sppd" class="nav-link sub-link">
                                    <i class="fas fa-file-signature nav-icon"></i>
                                    <p>Buat</p>
                                </nuxt-link>
                                <nuxt-link to="/karyawan/sppd/pemberi" class="nav-link sub-link">
                                    <i class="fas fa-file-signature nav-icon"></i>
                                    <p>Pemberi Tugas</p>
                                </nuxt-link>
                                <nuxt-link to="/karyawan/sppd/diketahui" class="nav-link sub-link">
                                    <i class="fas fa-file-signature nav-icon"></i>
                                    <p>Mengetahui</p>
                                </nuxt-link>
                                <nuxt-link to="/karyawan/sppd/selesai" class="nav-link sub-link">
                                    <i class="fas fa-file-signature nav-icon"></i>
                                    <p>Selesai</p>
                                </nuxt-link>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link link">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                Izin & Cuti
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <nuxt-link to="/karyawan/sppd" class="nav-link sub-link">
                                    <i class="fas fa-file-signature nav-icon"></i>
                                    <p>Buat</p>
                                </nuxt-link>
                                <nuxt-link to="/karyawan/sppd/pemberi" class="nav-link sub-link">
                                    <i class="fas fa-file-signature nav-icon"></i>
                                    <p>Pemberi Tugas</p>
                                </nuxt-link>
                                <nuxt-link to="/karyawan/sppd/diketahui" class="nav-link sub-link">
                                    <i class="fas fa-file-signature nav-icon"></i>
                                    <p>Mengetahui</p>
                                </nuxt-link>
                                <nuxt-link to="/karyawan/sppd/selesai" class="nav-link sub-link">
                                    <i class="fas fa-file-signature nav-icon"></i>
                                    <p>Selesai</p>
                                </nuxt-link>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <nuxt-link to="/karyawan/chat" class="nav-link link">
                            <i class="nav-icon far fa-comments"></i>
                            <p>Live Chat</p>
                        </nuxt-link>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link link">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                Profil
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <nuxt-link to="/karyawan/user" class="nav-link sub-link">
                                    <i class="fas fa-user nav-icon"></i>
                                    <p>User</p>
                                </nuxt-link>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a @click.prevent="logout" class="nav-link link" href="#">
                            <i class="nav-icon fas fa-power-off"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>
        
        </div>
        
    </aside>
</template>


<script>
    export default {
        data() {
            return {
                isAdmin: false,
                isKaryawan: false,

                assetPath: ''
            }
        },

        created() {
            this.getUserLevel()
            this.assetPath = process.env.assetUrl
        },

        mounted() {
            this.setMenu()
        },

        // computed: {
        //     message() {
        //         return process.env.assetUrl
        //     },
        // },

        methods: {
            getUserLevel(){
                if (this.$auth.user.roles[0].id === 1 || this.$auth.user.roles[0].id === 2) {
                    this.isAdmin = true
                }
                if (this.$auth.user.roles[0].id === 3 || this.$auth.user.roles[0].id === 4) {
                    this.isKaryawan = true
                }
            },

            async logout() {
                await this.$auth.logout()
            },

            setMenu() {
                setTimeout(() => {
                    if (window.innerWidth < 900) {
                        $('.sidebar .nav-link.sub-link').on('click', function() {
                            $('.sidebar-mini').removeClass('sidebar-open')
                            $('.sidebar-mini').addClass('sidebar-collapse')
                        })
                    }

                    $('.sidebar .nav-link.link').on('click', function() {
                        $('.sidebar .nav-link.link').removeClass('active')
                        $(this).addClass('active')
                    })

                    $('.sidebar .nav-link.sub-link').on('click', function() {
                        $('.sidebar .nav-link.sub-link').removeClass('active')
                        $(this).addClass('active')
                    })
                },1000)
            }
        }
    }
</script>