export default function ({ store, redirect }) {
    if (store.state.auth.user.roles[0].id !== 3) {
        if (store.state.auth.user.roles[0].id !== 4) {
            return redirect('/error')
        }
    }
}