<script>
import Helper from '../assets/Helper';

export default {
    components: {
        //
    },
    props: {
        state: {
            type: Object,
            required: true
        },
        responseObj: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            name: '',
            cardNumber: '',
            expDate: '',
            cvc: '',
        }
    },
    computed: {
        //
    },
    methods: {
        ClearResponseObj()
        {
            this.responseObj.showSuccess = false;
            this.responseObj.showError = false;
            this.responseObj.message = '';
        },
        CardNumberEnter(e)
        {
            this.ClearResponseObj();
            Helper.checkDigit(e)? e.target.value = Helper.cc_format(e.target.value) : e.target.value = '';
        },
        ExpDateEnter(e)
        {
            this.ClearResponseObj();
            if (e.target.value.length < 5)
            {
                Helper.checkDigit(e)? e.target.value = e.target.value.replace(/\W/gi, '').replace(/(.{2})/g, '$1/') : e.target.value = '';
            }
        },
        CvcEnter(e)
        {
            this.ClearResponseObj();
            Helper.checkDigit(e)? e.target.value = e.target.value : e.target.value = '';
        },
        Abort()
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
                price: this.state.order.price,
                plate: this.state.order.plate
            }


            console.log(body)


            Helper.ToggleSpinner(true);
            const response = await Helper.FetchApi('/api/pay', 'POST', body);
            Helper.ToggleSpinner(false);

            console.log(response);

            if (!response.success)
            {
                this.responseObj.showError = true;
                this.responseObj.message = response.message;
            }
            else
            {
                this.responseObj.showSuccess = true;
                this.responseObj.message = response.message + " Your payment id: " + response.payment_id;
                
                setTimeout(()=> {
                    this.state.order = {};
                    // this.$router.push('/');
                }, 4000)
            }
        }
        
    },
    watch: {
        //
    },
    mounted() {
        //
    },
    beforeMount() {
        //
    }
}

</script>

<template>
        
    <div className='pay-card'>

        <form @submit="SubmitPayment">
            <div>
                <label>
                    <span>Name</span>
                    <input v-model="name" id='name' type='text' autoFocus="true" required />
                </label>

                <label>
                    <span>Card number</span>
                    <input @keyup="CardNumberEnter" v-model="cardNumber" id='cardNumber' type='text' autoFocus="true" maxLength="19" required/>
                </label>

                <label>
                    <span>Lejárat</span>
                    <input @keyup="ExpDateEnter" v-model="expDate" id='expDate' type='text' autoFocus="true" maxLength="5" required/>
                </label>

                <label>
                    <span>CVC</span>
                    <input @keyup="CvcEnter" v-model="cvc" id='cvc' type='text' autoFocus="true" maxLength="3" required/>
                </label>
            </div>
                    
            <button type="submit" className="btn btn-primary btn-lg btn-block">Pay €{{ this.state.order.price }}</button>
            <button @click="Abort" type="button" className="btn btn-danger btn-lg btn-block">Abort</button>
            

            
            
        </form>
    </div>

</template>
