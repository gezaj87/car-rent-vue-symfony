<script>
import Helper from '../assets/Helper';
import ShowHistoryData from '../components/ShowHistoryData.vue';

export default {
    components: {
        ShowHistoryData
    },
    props: {
        state: {
            type: Object,
            required: true
        },
        
    },
    data() {
        return {
            responseObj: {
                showSuccess: false,
                showError: false,
                message: ''
            },
            userData: {
                email: '',
                name: '',
                address: ''
            },
            inputData: {
                email: '',
                name: '',
                address: '',
                oldPassword: '',
                newPassword1: '',
                newPassword2: ''
            },
            historyData: [],
            uiControll: {
                showEdit: false,
                showChangePassword: false,
                showHistory: false,
            }
        }
    },
    computed: {
        //
    },
    methods: {
        async GetUserData() {
            const body = {
                token: Helper.GetCookie('token')
            }

            Helper.ToggleSpinner(true);
            const response = await Helper.FetchApi('/api/user/', 'POST', body);
            Helper.ToggleSpinner(false);

            console.log(response)

            if (!response.success)
            {
                this.responseObj.showError = true;
                this.responseObj.message = response.message;
            }
            else
            {
                console.log("UserData received from server.")

                this.userData.email = response.userData.email;
                this.userData.name = response.userData.name;
                this.userData.address = response.userData.address;

                this.inputData.email = response.userData.email;
                this.inputData.name = response.userData.name;
                this.inputData.address = response.userData.address;
            }
        },
        async GetHistory() {
            const body = {
                token: Helper.GetCookie('token')
            }

            Helper.ToggleSpinner(true);
            const response = await Helper.FetchApi('/api/history/', 'POST', body);
            Helper.ToggleSpinner(false);

            // const button = document.getElementById("historyButton");
            console.log(response)
            if (response.success)
            {   
                this.historyData = response.history;
                // button.disabled = false;
                console.log("History received from server.");

            }
            else
            {
                // button.disabled = true;
            }


        },
        RestoreDatas() {
            this.inputData.email = this.userData.email;
            this.inputData.name = this.userData.name;
            this.inputData.address = this.userData.address;
        },
        async DeleteUser() {
            const body = {
                token: Helper.GetCookie('token'),
            }

            const iAmSure = window.confirm("Are you sure to delete your profile?");

            if (!iAmSure) return;
            
            Helper.ToggleSpinner(true);
            const response = await Helper.FetchApi('/api/user', 'DELETE', body);
            Helper.ToggleSpinner(false);
            
            if (!response.success)
            {
                this.responseObj.showError = true;
                this.responseObj.message = response.message;
            }
            else
            {
                this.responseObj.showSuccess = true;
                this.responseObj.message = response.message;

                if (response.logout)
                {
                    
                    setTimeout(() => {
                        this.$router.push('/logout');
                    },1000)
                }
            }
            
        },
        ClearResponseObj()
        {
            this.responseObj.showSuccess = false;
            this.responseObj.showError = false;
            this.responseObj.message = '';
        },
        async SubmitForm(e) {

            e.preventDefault();

            const body = {
                token: Helper.GetCookie('token'),
                email: this.inputData.email,
                name: this.inputData.name,
                address: this.inputData.address,
                isPasswordChanged: this.inputData.oldPassword === ''? false : true,
                oldPassword: this.inputData.oldPassword,
                newPassword1: this.inputData.newPassword1,
                newPassword2: this.inputData.newPassword2,
            }

            console.log(body)

            Helper.ToggleSpinner(true);
            const response = await Helper.FetchApi('/api/user', 'PUT', body);
            Helper.ToggleSpinner(false);

            if (!response.success)
            {
                this.responseObj.showSuccess = false;
                this.responseObj.showError = true;
                this.responseObj.message = response.message;
            }
            else
            {
                // setResponseMessage(response.message);
                // Helper.ErrorMessageShow("registerSuccess", true, true);
                this.responseObj.showSuccess = true;
                this.responseObj.showError = false;
                this.responseObj.message = response.message;

                if (response.logout)
                {
                    
                    setTimeout(() => {
                        this.$router.push('/logout');
                    },1000)
                }
                else
                {
                    this.GetUserData();
                    this.DefaultInput();
                    this.DefaultView();
                }



                
            }
        },
        DefaultView() {
            this.uiControll.showEdit = false;
            this.uiControll.showChangePassword = false;
            this.uiControll.showHistory = false;


        },
        DefaultInput() {
            this.inputData.email = this.userData.email;
            this.inputData.name = this.userData.name;
            this.inputData.address = this.userData.address;
            this.inputData.oldPassword = '';
            this.inputData.newPassword1 = '';
            this.inputData.newPassword2 = '';
        }
        
    },
    watch: {
        //
    },
    mounted() {
        if (!this.state.isLoggedIn)
        {
            this.$router.push('/login');
        }
        else
        {
            this.GetUserData();
            this.GetHistory();
        }
    },
    beforeMount() {
        //
    }
}

</script>

<template>
        
   
    <main>

        <div id="Profile">

            <h3 className="text-center text-black pt-3">My Profile</h3>

            <div v-if="responseObj.showError" id="registerError" className="alert alert-danger mt-3 text-center" role="alert">
                {{responseObj.message}}
            </div>
            <div v-if="responseObj.showSuccess" id="registerSuccess" className="alert alert-success mt-3 text-center" role="alert">
                {{ responseObj.message }} 
            </div>



            
            <div className='container'>
                <div className='row justify-content-center '>

                    <div className='col-md-9'>
                        <div className="mt-2 p-4 text-info rounded content-background">
                            <div id="login-box" className="col-md-12">
                                <form @submit="SubmitForm" id="login-form" className="form">
                                    <div class="d-grid gap-2">
                                        <button @click="uiControll.showEdit = !uiControll.showEdit" type="button" className="btn btn-block btn-secondary btn-lg btn-block mb-2">EDIT your Profile</button>
                                        <button @click="DeleteUser" type="button" className="btn btn-danger btn-sm btn-block mb-2">DELETE your Profile</button>
                                    </div>
                                
                                
                                
                                    <div className="form-group text-center">
                                        <label htmlFor="email" className="text-info">Email</label><br/>
                                        <input v-model="inputData.email" type="text" id="email" className="form-control text-center" :readonly="!uiControll.showEdit" />
                                    </div>
                                    <div className="form-group text-center">
                                        <label htmlFor="name" className="text-info">Name</label><br/>
                                        <input v-model="inputData.name" type="text" id="name" className="form-control text-center" :readonly="!uiControll.showEdit" />
                                    </div>
                                    <div className="form-group text-center">
                                        <label htmlFor="address" className="text-info">Address</label><br/>
                                        <input v-model="inputData.address" type="text" id="address" className="form-control text-center" :readonly="!uiControll.showEdit" />
                                    </div>
                                    

                                    <div className='d-flex justify-content-center mb-3 mt-3'>
                                        <button @click="uiControll.showChangePassword = !uiControll.showChangePassword" className="btn btn-info" type="button">Change password</button>
                                    </div>

                                    <div :class="uiControll.showChangePassword? '' : 'd-none' ">
                                        <div className="form-group mb-1 mt-1">
                                            <label htmlFor="password0" className="text-info">Old password:</label><br/>
                                            <input v-model="inputData.oldPassword" type="text" id="password0" className="form-control text-center" />
                                        </div>
                                        <div className="form-group mb-1 mt-1">
                                            <label htmlFor="password1" className="text-info">New password:</label><br/>
                                            <input v-model="inputData.newPassword1" type="text" id="password1" className="form-control text-center" />
                                        </div>
                                        <div className="form-group mb-1 mt-1">
                                            <label htmlFor="password2" className="text-info">New password:</label><br/>
                                            <input v-model="inputData.newPassword2" type="text" id="password2" className="form-control text-center"/>
                                        </div>
                                    </div>
                                    
                                    <div className=''>
                                        <div class="d-grid gap-2">
                                            <button @click="RestoreDatas" type="button" className="btn btn-warning btn-lg btn-block">Restore</button>
                                            <button type="submit" className="btn btn-primary btn-lg btn-block" >Save changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <hr/>
                            <div class="d-grid gap-2">
                                <button @click="uiControll.showHistory = !uiControll.showHistory" type="button" id="paymentButton" className="btn btn-secondary btn-md btn-block" :disabled="historyData.length === 0? true : false" >Previous Payments</button>
                            </div>
                            <div id="history" :class="uiControll.showHistory? '' : 'd-none' ">
                                
                                <ShowHistoryData :history="historyData"/>

                                
                            </div>



                        </div>
                    </div>

                    
                    


                </div>
            </div>





        </div>


    </main>

</template>
