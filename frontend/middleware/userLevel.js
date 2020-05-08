export default function ({ store, redirect }) {
    if (!store.state.authenticated) {
        return redirect('/login')
    } else {
        if (store.state.auth.user.roles[0].id === 2) {
            return redirect('/admin')
        }
        if (store.state.auth.user.roles[0].id === 4) {
            return redirect('/user')
        }
    }
}