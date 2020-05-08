<template>
    <div class="content-wrapper">
        <Header title="Live Chat"/>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-9">
                                        <h5>Message List</h5>
                                        <div>{{messages}}</div>
                                    </div>
                                    <div class="col-lg-3">
                                        <h5>User List</h5>
                                        <template v-for="user in users">
                                            <div :key="user.id" @click.prevent="chatWith(user.id)">
                                                <i v-if="user.isLogin === '0'" class="fas fa-circle text-secondary"></i> 
                                                <i v-else class="fas fa-circle text-success"></i> 
                                                <span>{{user.name}}</span>
                                            </div>
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
    import { Header } from '../../../components/layouts/admin'
    export default {
        name: 'Chat',
        middleware: 'isKaryawan',
        layout: 'admin',
        head: {
            bodyAttrs: {
                class: 'hold-transition sidebar-mini'
            }
        },

        components: { Header },

        data() {
            return {
                users: [],
                messages: []
            }
        },

        created() {
            this.getUsers(`${process.env.apiUrl}/karyawan/chat/user-list`)
        },

        mounted() {
        },

        methods: {
            getUsers(url) {
                this.$axios.get(url)
                    .then(res => {
                        this.users = res.data.filter(user => user.id != this.$auth.user.id)
                    })
                    .catch(err => console.log(err))
            },

            chatWith(id) {
                this.$axios.get(`${process.env.apiUrl}/karyawan/chat/user/${id}`)
                    .then(res => this.messages = res.data)
                    .catch(err => console.log(err))
            },
        }
    }
</script>