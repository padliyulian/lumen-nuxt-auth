import Pusher from 'pusher-js'

export default ({ store }) => {
    const socket = new Pusher('64207aed9000e83ab30c', {
        cluster: 'ap1',
        forceTLS: true
})

const channel = socket.subscribe('chat')
    channel.bind('SendMessage', function (data) {
        // data dari pusher dimasukan ke dalam state di vuex melalu mutation setNotif
        store.commit('addMessage', data.message)
    })
}