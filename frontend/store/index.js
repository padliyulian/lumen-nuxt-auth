export const state = () => ({
    messages: []
})

// getter
export const getters = {
    getMessage (state) {
        return state.messages
    }
}

export const mutations = {
    addMessage (state, data) {
        state.messages.push(data)
    },

    setMessage (state, data) {
        state.messages = data
    }
}