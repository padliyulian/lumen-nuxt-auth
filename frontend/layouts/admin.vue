<template>
    <div class="wrapper">
        <Navbar/>
        <Sidebar/>
        <nuxt />
        <ControlSidebar/>
        <Footer/>    
    </div>
</template>


<script>
    import { Navbar, Sidebar, ControlSidebar, Footer  } from '../components/layouts/admin'
    export default {
        components: { Navbar, Sidebar, ControlSidebar, Footer }
    }
</script>
