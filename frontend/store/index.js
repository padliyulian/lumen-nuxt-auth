export const state = () => ({
    users: [],
    messages: []
})

// getter
export const getters = {
    getUser (state) {
        return state.users
    },

    getMessage (state) {
        return state.messages
    }
}

export const mutations = {
    addMessageCount (state, data) {
        state.users.filter(user => {
            if (user.id == data) {
                user.messages_count++;
            }
        })
    },

    setUser (state, data) {
        state.users = data
    },

    addMessage (state, data) {
        state.messages.push(data)
    },

    setMessage (state, data) {
        state.messages = data
    }
}