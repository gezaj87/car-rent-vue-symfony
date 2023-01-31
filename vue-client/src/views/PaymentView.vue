<script>
import Helper from '../assets/Helper';
import CardPreView from '../components/CardPreView.vue';
import CardPayment from '../components/CardPayment.vue';
// import { RouterLink } from 'vue-router'

export default {
    components: {
        CardPreView,
        CardPayment
    },
    props: {
        state: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            name: '',
            cardNumber: '',
            expDate: '',
            cvc: '',
            responseObj: {
                showSuccess: false,
                showError: false,
                message: ''
            }
        }
    },
    computed: {
        //
    },
    methods: {
        AbortPayment()
        {
            this.state.order = {};
            this.$router.push('/');

        },
        async SubmitPayment(e)
        {
            e.preventDefault();

            const body = {
                token: Helper.GetCookie('token'),
                name: this.name,
                cardNumber: this.cardNumber,
                expDate: this.expDate,
                cvc: this.cvc,
                price: this.order.price,
                plate: this.order.plate
            }

            Helper.ToggleSpinner(true);
            const response = await Fetch('/api/pay', 'POST', body);
            Helper.ToggleSpinner(false);

            if (!response.success)
            {
                this.responseMessage = response.message;
                // Helper.ErrorMessageShow("registerError", true, false);
            }
            else
            {
                this.responseMessage = response.message + " Your payment id: " + response.payment_id;
                // Helper.ErrorMessageShow("registerSuccess", true, true);
                
                setTimeout(()=> {
                    this.state.order = {};
                    // this.$router.push('/');
                }, 4000)
            }
        }
        
    },
    watch: {
        'state.isLoggedIn': function (newValue, oldValue) {
            if (!this.state.isLoggedIn)
            {
                this.$router.push('/login');
            }
            
        },
        'state.order': function () {
            if (!Object.keys(this.state.order).length)
            {
                this.$router.push('/');
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
        
        <div id="Payment">

            <h3 className="text-center text-black pt-3">Online Payment</h3>

            <div className='container'>
                <div className='row justify-content-center align-items-center mt-2 p-3 text-info rounded content-background'>
                    <div className='col-md-6'>
                        <div className="">
                            
                            <CardPreView :state="state" />

                        </div>
                    </div>
                    <div className='col-md-6'>
                        <div className="">

                            <CardPayment :state="state" :responseObj="responseObj"/>

                        </div>

                        <div v-if="responseObj.showError" id="registerError" className="alert alert-danger mt-3 text-center" role="alert">
                            {{ responseObj.message }}
                        </div>
                        <div v-if="responseObj.showSuccess" id="registerSuccess" className="alert alert-success mt-3 text-center" role="alert">
                            {{ responseObj.message }}
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>

    </main>
</template>
