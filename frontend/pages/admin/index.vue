<template>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <Header title="Dashboard"/>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Dashboard</h5>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">{{roleName}}</h6>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>

    </div>
</template>


<script>
    import { Header } from '../../components/layouts/admin'
    export default {
        name: 'Dashboard',
        middleware: 'isAdmin',
        layout: 'admin',
        head: {
            bodyAttrs: {
                class: 'hold-transition sidebar-mini'
            }
        },

        components: { Header },

        data() {
            return {
                roleName: ''
            }
        },

        created() {
            this.getRoleName(`${process.env.apiUrl}/login/user-role-name`)
        },

        mounted() {
            console.log(this.$auth)
        },

        methods: {
            getRoleName(url) {
                this.$axios.get(url)
                    .then(res => this.roleName = res.data[0])
                    .catch(err => console.log(err))
            },
        }
    }
</script>