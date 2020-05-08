export default function ({ store, redirect }) {
    if (store.state.auth.user.roles[0].id !== 2) {
        return redirect('/error')
    }
}