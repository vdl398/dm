<template>
    <li class="nav-item">
	    <a href="/profile" style="display: flex; align-items: center;">
		    <div style="margin-right: 10px;">{{auth.title}}</div>
			 <div v-if="auth.photo" class="_avatar" :style="[{ backgroundImage: `url(${auth.photo})`}]"></div>
		</a>
	</li>
    <li v-if="!auth.id" class="nav-item">
        <a @click="loginOpen()" class="nav-link mx-md-4" href="javascript:void(0)">Login</a>
    </li>
    <li v-if="auth.id" class="nav-item">
        <a @click="logout" class="nav-link mx-md-4" href="javascript:void(0)">logout</a>
    </li>
    <li v-if="!auth.id" class="nav-item">
        <a @click="registerOpen" class="btn-medium btn btn-primary" href="javascript:void(0)">Register</a>
    </li>
	<v-dialog v-model="login.show">
		<div class="popup_wr">
		    <div class="_hd">{{(login.tmp)? 'Join' : 'Login'}}</div>
			<div v-if="login.tmp" class="form-input col-lg-12 my-4">
                        <label for="name_inp" class="form-label fs-6 text-uppercase fw-bold text-black">Name</label>
                        <input v-model="login.name.value" type="text" id="name_inp" name="name" placeholder="Name" class="form-control ps-3">
						<div class="_err">{{login.name.errorMessage}}</div>
            </div>	
			<div v-if="!login.tmp" class="form-input col-lg-12 my-4">
                        <label for="InputEmail1" class="form-label fs-6 text-uppercase fw-bold text-black">Email</label>
                        <input v-model="login.email.value" type="text" id="InputEmail1" name="email" placeholder="Email" class="form-control ps-3">
						<div class="_err">{{login.email.errorMessage}}</div>
            </div>
            <div v-if="!login.tmp" class="form-input col-lg-12 my-4">
                        <label for="inputPassword1" class="form-label  fs-6 text-uppercase fw-bold text-black">Password</label>
                        <input v-model="login.password.value" type="password" id="inputPassword1" placeholder="Password" class="form-control ps-3" aria-describedby="passwordHelpBlock">
						<div class="_err">{{login.password.errorMessage}}</div>
            </div>
		    <div class="_nv">
		                <div class="btn_success" @click="loginSubmit()">{{(login.tmp)? 'Join' : 'Login'}}</div>
		                <div class="btn_cancel" @click="loginCancel()">Cancel</div>
			</div>
		</div>
	</v-dialog>
	<v-dialog v-model="register.show">
		<div class="popup_wr">
		    <div class="_hd">Register</div>
			<div class="form-input col-lg-12 my-4">
                <label for="name_inp" class="form-label fs-6 text-uppercase fw-bold text-black">Name</label>
                <input v-model="register.name.value" type="text" id="name_inp" name="name" placeholder="Name" class="form-control ps-3">
				<div class="_err">{{register.name.errorMessage}}</div>
            </div>
			<div class="form-input col-lg-12 my-4">
                <label for="email2_inp" class="form-label fs-6 text-uppercase fw-bold text-black">Email</label>
                <input v-model="register.email.value" type="text" id="email2_inp" name="email" placeholder="Email" class="form-control ps-3">
				<div class="_err">{{register.email.errorMessage}}</div>
            </div>
			<div class="form-input col-lg-12 my-4">
                <label for="password2_inp" class="form-label fs-6 text-uppercase fw-bold text-black">Password</label>
                <input v-model="register.password.value" type="text" id="password2_inp" name="password" placeholder="Password" class="form-control ps-3">
				<div class="_err">{{register.password.errorMessage}}</div>
            </div>
			<div class="form-input col-lg-12 my-4">
                <label for="password_confirmation_inp" class="form-label fs-6 text-uppercase fw-bold text-black">Password confirm</label>
                <input v-model="register.password_confirmation.value" type="text" id="password_confirmation_inp" name="password_confirmation" placeholder="Password confirm" class="form-control ps-3">
				<div class="_err">{{register.password_confirmation.errorMessage}}</div>
            </div>
		    <div class="_nv">
		        <div class="btn_success" @click="registerSubmit()">Register</div>
		        <div class="btn_cancel" @click="registerCancel()">Cancel</div>
			</div>
		</div>
	</v-dialog>
</template>
<script>

import {Request} from './../request.js'
import {Auth} from './../auth.js'



export default {
  data() {
    return {
	    auth: {
		    id: 0,
			title: '',
			photo: '',
		},
	    login: {
		    name: {value: '', errorMessage: ''},
            email: {value: '', errorMessage: ''},
	        password: {value: '', errorMessage: ''},
			tmp: false,
			show: false,
		},
	    register: {
		    name: {value: '', errorMessage: ''},
            email: {value: '', errorMessage: ''},
	        password: {value: '', errorMessage: ''},
			password_confirmation: {value: '', errorMessage: ''},
			show: false,
		},
    };
  },
  async mounted() {
    document.addEventListener('openJoinForm', (e)=> {
		this.loginOpen(true);
    }, false);	
	if (Auth.isAuth()) {
	    let user = Auth.getUser();
	    this.auth.id = user.id;
	    this.auth.title = user.name || user.login;
		this.auth.photo = user.photo;
	}
  },
  methods: {
  
        loginOpen(isTmp=false) {
           this.login.show = true;
		   this.login.tmp = isTmp;
		},
		
		loginCancel() {
            this.login.show = false;
			if (this.login.tmp) location.href = '/';
		},
  
  		async loginSubmit() {
		    if (this.login.tmp) {
			    this.loginTmpSubmit();
				return;
			}
		    this.login.email.errorMessage = '';
	        this.login.password.errorMessage = '';
		    let rs = await Request.post('login', {email: this.login.email.value, password: this.login.password.value});

            if (rs && (rs.status == 'success')) {
			   location.href = location.href;
			   return true;
			}
			if (rs.errors) {
			    for (const [key, value] of Object.entries(rs.errors)) {
                    if (this.login.hasOwnProperty(key) && value) {
					   this.login[key].errorMessage = (Array.isArray(value))? value.join(", ") : value;
					}
                }
			}
		},
		
  		async loginTmpSubmit() {
		    this.login.name.errorMessage = '';
		    let rs = await Request.post('login', {name: this.login.name.value, tmp: 'Y'});
            if (rs && (rs.status == 'success')) {
			   location.href = location.href;
			   return;
			}
			location.href = '/';
		},

        registerOpen() {
           this.register.show = true;
		},
		
		registerCancel() {
            this.register.show = false;
		},
  
  		async registerSubmit() {

			this.register.name.errorMessage = '';
            this.register.email.errorMessage = '';
	        this.register.password.errorMessage = '';
			this.register.password_confirmation.errorMessage = '';

		    let rs = await Request.post('register', {name: this.register.name.value, email: this.register.email.value, password: this.register.password.value, password_confirmation: this.register.password_confirmation.value});
			if (rs && (rs.status == 'success')) {
			    location.href = '/rooms';
			}
			
			if (rs.response?.data?.errors) {
			    for (const [key, value] of Object.entries(rs.response?.data?.errors)) {
                    if (this.register.hasOwnProperty(key) && value) {
					   this.register[key].errorMessage = (Array.isArray(value))? value.join(", ") : value;
					}
                }
			}
		},

  		logout() {
			location.href = '/logout';
		},

  },
};
</script>