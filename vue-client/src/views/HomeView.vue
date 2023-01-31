<script>
import Helper from '../assets/Helper';
// import { RouterLink } from 'vue-router'

export default {
    components: {
        // RouterLink
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
        //
    },
    methods: {
        //
    },
    watch: {
        'state.isLoggedIn': function (newValue, oldValue) {
            if (!this.state.isLoggedIn)
            {
                this.$router.push('/login');
            }
            
        }
    },
    mounted() {
        if (!this.state.isLoggedIn)
        {
            this.$router.push('/login');
        }
    },
    beforeMount() {
        //
    }
}

</script>

<template>
    <main>
        <div id="Home">

            <h3 className="text-center text-black pt-3">Home</h3>



            <div className='container'>
                <div className='row justify-content-center align-items-center'>
                    <div className='col-md-12'>
                        <div className="mt-2 p-5 text-info rounded content-background">
                            <h4 className='text-center'>Welcome to our Car Rent Service!</h4>
                            <div className='home-page'>
                                <img src='img/main.webp'/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</template>
