<script>
// import { RouterLink } from 'vue-router';
import { RouterLink } from 'vue-router'




export default {
    components: {
        RouterLink
    },
    props: {
        state: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            //
        }
    },
    computed: {
        //computed functions
    },
    methods: {
        //
    },
    watch: {
        //
    },
    mounted () {
        //
    },
    beforeMount() {
       //
    }
}


</script>

<template>
    <div class="header_section nav-body">
        <ul class="nav justify-content-center">
            <li v-if="state.isLoggedIn" class="nav-item">
                <RouterLink to="/" :class="['nav-link', 'nav-button']" >Home</RouterLink>
            </li>

            <li v-if="state.isLoggedIn" class="nav-item">
                <a class="nav-link nav-button menu-off" >ABOUT US</a>
            </li>
        
            <li v-if="state.isLoggedIn" class="nav-item">
                <RouterLink to="/cars" :class="['nav-link', 'nav-button']" >CARS</RouterLink>
            </li>
         
            <li v-if="state.isLoggedIn" class="nav-item">
                <RouterLink to="/profile" :class="['nav-link', 'nav-button']" >MY PROFILE</RouterLink>
            </li>
        
         
            <li v-if="state.isLoggedIn" class="nav-item">
                <RouterLink to="/logout" :class="['nav-link', 'nav-button']" >LOGOUT</RouterLink>
            </li>
        
         
            <li v-if="!state.isLoggedIn" class="nav-item">
                <RouterLink to="/login" :class="['nav-link', 'nav-button']" >LOGIN</RouterLink>
            </li>
        
         
            <li v-if="!state.isLoggedIn" class="nav-item">
                <RouterLink to="/register" :class="['nav-link', 'nav-button']" >REGISTER</RouterLink>
            </li>
        </ul>
    </div>
    
</template>

<style scoped>

</style>
