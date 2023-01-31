<script>
import Helper from '../assets/Helper';


export default {
    components: {
        // TheWelcome
    },
    props: {
        state: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            text: '',
            ShowStartLogout: true,
            ShowResponseMessage: false,
        }
    },
    computed: {
        //computed functions
    },
    methods: {
        async logout(){

            for (let i = 5; i > 0; i--) {
                await new Promise(resolve => {
                    setTimeout(() => {
                    this.text = `Logging out in ${i}...`;
                    resolve();
                    }, (5 - i) * 400);
                });
            }

            Helper.Logout(this.state);

        }
    },
    watch: {
        'state.isLoggedIn': function (newValue, oldValue) {
            if (!this.state.isLoggedIn)
            {
                this.$router.push('/login');
            }
    }
    },
    mounted () {
        this.logout();
    },
    beforeMount() {
        if (!this.state.isLoggedIn)
        {
            this.$router.push('/login');
        }
    }
}

</script>

<template>
    <main>
        
        <div id="Home">

            <div v-if="ShowResponseMessage" id="loginError" className="alert alert-success mt-3 text-center" role="alert">
                {{ text }} 
            </div>

            <div className='container'>
                <div className='row justify-content-center align-items-center'>
                    <div className='col-md-12'>
                        <div className="mt-2 p-5 text-info rounded content-background">
                            <p className='text-center'>{{text}}</p>
                            <div className='home-page'>
                                <!-- text goes here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                
    </main>
</template>
