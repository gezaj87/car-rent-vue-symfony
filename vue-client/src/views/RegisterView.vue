<script>
import Helper from '../assets/Helper';
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
            InputEmail: '',
            InputPassword1: '',
            InputPassword2: '',
            InputName: '',
            InputAdress: '',
            ResponseMessage: '',
            ShowError: false,
            ShowSuccess: false
        }
    },
    computed: {
        //
    },
    methods: {
        async SubmitRegister(e) {

            e.preventDefault();

            const body = {
                email: this.InputEmail,
                password1: this.InputPassword1,
                password2: this.InputPassword2,
                name: this.InputName,
                address: this.InputAdress

            }

            Helper.ToggleSpinner(true);
            const response = await Helper.FetchApi('/api/register/', 'POST', body);
            Helper.ToggleSpinner(false);
            
            if (!response.success)
            {
                this.ResponseMessage = response.message;
                this.ShowSuccess = false;
                this.ShowError = true;
            }
            else
            {
                this.ResponseMessage = response.message;
                this.ShowError = false;
                this.ShowSuccess = true;

                setTimeout(()=> {
                    this.$router.push('/login');
                }, 1000)
                // this.ResponseMessage = '';
                // this.$router.push('/home');
            }
        },
        ClearResponseMessages() {
            this.ResponseMessage = '';
            this.ShowError = false;
            this.ShowSuccess = false;
        }
    },
    watch: {
        'state.isLoggedIn': function (newValue, oldValue) {
            if (this.state.isLoggedIn)
            {
                this.$router.push('/');
            }
        }
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
    <main>
           
            <div id="login">
                
                <div v-if="ShowError" id="loginError" className="alert alert-danger mt-3 text-center" role="alert">
                    {{ ResponseMessage }} 
                </div>

                <div v-if="ShowSuccess" id="registerSuccess" className="alert alert-success mt-3 text-center" role="alert">
                    {{ ResponseMessage }} 
                </div>


                <div className="container">
                    <div id="login-row" className="row justify-content-center align-items-center">
                        <div id="login-column" className="col-md-6">
                            <div id="login-box" className="col-md-12 register">
                                <form @submit="SubmitRegister" id="login-form" className="form">
                                    <h3 className="text-center text-info">Register</h3>
                                    <div className="form-group">
                                        <label htmlFor="email" className="text-info">Email:</label><br/>
                                        <input @keyup="ClearResponseMessages" v-model="InputEmail" type="text" id="email" className="form-control" />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="password1" className="text-info">Password:</label><br/>
                                        <input @keyup="ClearResponseMessages" v-model="InputPassword1" type="password" id="password1" className="form-control" />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="password2" className="text-info">Password:</label><br/>
                                        <input @keyup="ClearResponseMessages" v-model="InputPassword2" type="password" id="password" className="form-control" />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="name" className="text-info">Name:</label><br/>
                                        <input @keyup="ClearResponseMessages" v-model="InputName" type="text" id="name" className="form-control" />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="address" className="text-info">Adress:</label><br/>
                                        <input @keyup="ClearResponseMessages" v-model="InputAdress" type="text" id="address" className="form-control" />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="remember-me" className="text-info"><span></span> <span></span></label><br/>
                                        <button type="submit" className="btn btn-info btn-md">Submit</button>
                                    </div>
                                    <div className="text-right">
                                        <RouterLink to="/login" :class="['text-info']">Login here</RouterLink>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

        </div>

    </main>
</template>