<script>
import Helper from '../assets/Helper';
import LoadCars from '../components/LoadCars.vue';
// import { RouterLink } from 'vue-router'

export default {
    components: {
        // RouterLink
        LoadCars
    },
    props: {
        state: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            cars: [],
        }
    },
    computed: {
        //
    },
    methods: {
        async GetCars(avaiableOnly = false)
        {
            

            const body = {
                token: Helper.GetCookie('token'),
                avaiableOnly: avaiableOnly
            }

            Helper.ToggleSpinner(true);
            const response = await Helper.FetchApi('/api/cars/', 'POST', body);
            Helper.ToggleSpinner(false);
            
            if (response.success)
            {
                this.cars = response.cars;
            }

            console.log(response)
        },
        
    },
    watch: {
        'state.isLoggedIn': function (newValue, oldValue) {
            if (!this.state.isLoggedIn)
            {
                this.$router.push('/login');
            }
            
        },
        'state.order': function (newValue, oldValue) {
            if (newValue !== oldValue)
            {
                console.log("rendelés történt")
            }
        }
    },
    mounted() {
        if (!this.state.isLoggedIn)
        {
            this.$router.push('/login');
        }
        this.GetCars();
    },
    beforeMount() {
        //
    }
}

</script>

<template>
    <main>
        <div id="Cars">

            <h3 className="text-center text-black pt-3">Cars</h3>

            <div className='container'>
                <div className='row justify-content-center align-items-center'>
                    <div className='col-md-12'>
                        <div className="mt-2 p-5 text-info rounded content-background">

                            <LoadCars :cars="cars" :state="state"/>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</template>
