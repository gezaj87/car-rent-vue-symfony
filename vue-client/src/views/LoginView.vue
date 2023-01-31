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
            InputPassword: '',
            ResponseMessage: '',
        }
    },
    computed: {
        ShowResponseMessage() {
            return this.ResponseMessage !== '';
        }
    },
    methods: {
        async SubmitLogin(e) {

            e.preventDefault();

            const body = {
                email: this.InputEmail,
                password: this.InputPassword
            }

            Helper.ToggleSpinner(true);
            const response = await Helper.FetchApi('/api/login/', 'POST', body);
            Helper.ToggleSpinner(false);

            if (!response.success)
            {
                this.ResponseMessage = response.message;
                console.log(this.ShowResponseMessage)
            }
            else
            {
                console.log(response);
                Helper.SetCookie("token", response.token, 60);
                Helper.Auth(this.state);
                
                // this.ResponseMessage = '';
                // this.$router.push('/home');
            }
        },
        ClearResponseMessage() {
            this.ResponseMessage = '';
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
        // if (this.state.isLoggedIn)
        // {
        //     this.$router.push('/home');
        // }
    }
}

</script>

<template>
    <main>
        
        <div>


            <div id="login">
                
                <div v-if="ShowResponseMessage" id="loginError" className="alert alert-danger mt-3 text-center" role="alert">
                    {{ ResponseMessage }} 
                </div>


                <div className="container">
                    <div id="login-row" className="row justify-content-center align-items-center">
                        <div id="login-column" className="col-md-6">
                            <div id="login-box" className="col-md-12">
                                <form @submit="SubmitLogin" id="login-form" className="form">
                                    <h3 className="text-center text-info">Login</h3>
                                    <div className="form-group">
                                        <label htmlFor="email" className="text-info">Email:</label><br/>
                                        <input @keyup="ClearResponseMessage" v-model="InputEmail" type="text" id="email" className="form-control" required/>
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="password" className="text-info">Password:</label><br/>
                                        <input @keyup="ClearResponseMessage" v-model="InputPassword" type="password" id="password" className="form-control" required/>
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="remember-me" className="text-info"><span></span> <span></span></label><br/>
                                        <button type="submit" className="btn btn-info btn-md">Submit</button>
                                    </div>
                                    <div className="text-right">
                                        <RouterLink to="/register" :class="['text-info']">Register here</RouterLink>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </main>
</template>
