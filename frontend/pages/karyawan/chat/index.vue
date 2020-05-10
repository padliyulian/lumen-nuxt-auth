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
                                    <div class="col-lg-6">
                                        <h5>Message list with {{form.name}}</h5>
                                        <div class="chat__box card px-4 py-4">
                                            <template v-for="message in messages">
                                                <template v-if="message.from === id">
                                                    <div :key="message.id" class="text-right">
                                                        <span class="text-info">
                                                            <span class="d-block">{{message.message}}</span>
                                                            <span class="d-block">{{$moment(message.created_at).format('D/MM/YYYY H:m')}}</span>
                                                        </span>
                                                    </div>
                                                </template>
                                                <template v-else>
                                                    <div :key="message.id" class="text-left">
                                                        <span class="text-secondary">
                                                            <span class="d-block">{{message.message}}</span>
                                                            <span class="d-block">{{$moment(message.created_at).format('D/MM/YYYY H:m')}}</span>
                                                        </span>
                                                    </div>
                                                </template>
                                            </template>
                                        </div>
                                        <div class="chat__form">
                                            <form @submit.prevent="sendMessage">
                                                <div class="form-group">
                                                    <textarea rows="3" name="message" v-model="form.message" class="form-control" :class="{ 'is-invalid': form.errors.has('message') }" id="message" placeholder="Type message here ..."></textarea>
                                                    <has-error :form="form" field="message"></has-error>
                                                </div>
                                                <div class="form-group">
                                                    <button :disabled="!form.message" type="submit" class="btn btn-info">Send</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-4">
                                        <h5>User List</h5>
                                        <template v-for="user in users">
                                            <div :key="user.id" @click.prevent="chatWith(user)">
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
    import { Form, HasError, AlertError } from 'vform'
    export default {
        name: 'Chat',
        middleware: 'isKaryawan',
        layout: 'admin',
        head: {
            bodyAttrs: {
                class: 'hold-transition sidebar-mini'
            }
        },

        components: { Header, Form, HasError, AlertError },

        data() {
            return {
                id: 0,
                users: [],
                // messages: [],

                form: new Form({
                    name: '',

                    to: null,
                    message: ''
                })
            }
        },

        created() {
            this.setUserID()
            this.getUsers()
        },

        mounted() {
        },

        computed: {
            messages() {
                return this.$store.getters.getMessage
            },
        },

        methods: {
            setUserID() {
                this.id = this.$auth.user.id
            },

            getUsers() {
                this.$axios.get(`${process.env.apiUrl}/karyawan/chat/user-list`)
                    .then(res => {
                        this.users = res.data.filter(user => user.id != this.id)
                    })
                    .catch(err => console.log(err))
            },

            chatWith(user) {
                this.form.to = user.id
                this.form.name = user.name
                this.$axios.get(`${process.env.apiUrl}/karyawan/chat/user/${user.id}`)
                    .then(res => {
                        this.$store.commit('setMessage', res.data)
                        this.getUsers()
                    })
                    .catch(err => console.log(err))
            },

            sendMessage() {
                if (this.form.to) {
                    this.form.post(`${process.env.apiUrl}/karyawan/chat/user`, {
                        headers: {
                            Authorization: this.$auth.$storage._state['_token.local'],
                        }
                    })
                        .then(res => {
                            this.resetForm()
                            this.getUsers()
                            // this.messages.push(res.data)
                            this.$store.commit('addMessage', res.data)
                        })
                        .catch(err => console.log(err))
                }
            },

            resetForm() {
                this.form.message = ''
            }
        }
    }
</script>


<style lang="scss">
    .chat {
        &__box {
            height: 300px;
            overflow: auto;
        }
    }
</style>
