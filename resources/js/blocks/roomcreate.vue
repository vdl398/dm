<template>
    <a v-if="big_btn" @click="showPopup" href="javascript:void(0)"><div class="create_romm_wr"><img :src="'/images/create_room.png'" width="150"><div class="_title">Create Room</div></div></a>
	<a v-else @click="showPopup" style="background: green; color: #FFF; padding: 8px; border-radius: 8px;" href="javascript:void(0)">New room +</a>
	<v-dialog v-model="popup.show">
		<div class="popup_wr _edit_room">
		    <div class="_hd">Create room</div>
			<div class="form-input col-lg-12 my-4">
                        <label for="r_name_inp" class="form-label fs-6 text-uppercase fw-bold text-black">Name</label>
                        <input v-model="popup.fields.title.value" type="text" id="r_name_inp" name="name" placeholder="Name" class="form-control ps-3">
						<div class="_err">{{popup.fields.title.errorMessage}}</div>
            </div>
			<div class="form-input col-lg-12 my-4">
                <input type="radio" value="PP" v-model="popup.fields.mode.value">
                <label>5 members limit</label>
                <br>
                <input type="radio" value="S" v-model="popup.fields.mode.value">
                <label>more than 5 members</label>
				<div class="_err">{{popup.fields.mode.errorMessage}}</div>
            </div>	
		    <div class="_nv">
		                <div class="btn_success" @click="create()">Create</div>
		                <div class="btn_cancel" @click="cancel()">Cancel</div>
			</div>
		</div>
	</v-dialog>
</template>
<script>
import {Request} from './../request.js'
import {Auth} from './../auth.js'

export default {
  props: ['big_btn'],
  data() {
    return {
		popup: {
			show: false,
			fields: {
			    title: {value: '', errorMessage: ''},
				mode: {value: 'PP', errorMessage: ''},
			}
		},
    };
  },
  async mounted() {

  },
  methods: {
  
        showPopup() {
		   if (Auth.isAuth()) this.popup.show = true;
		   else alert('Please login to create a room');
		},
		
		cancel() {
            this.popup.show = false;
		},

        //создать комнату
		async create() {
		    this.popup.fields.title.errorMessage = '';
		    let rs = await Request.post('room/create', {title: this.popup.fields.title.value, mode: this.popup.fields.mode.value});
            if (rs && (rs.status == 'success')) {
			   location.href = '/rooms';
			   return true;
			}
			if (rs.errors) {
			    for (const [key, value] of Object.entries(rs.errors)) {
                    if (this.popup.fields.hasOwnProperty(key) && value) {
					   this.popup.fields[key].errorMessage = (Array.isArray(value))? value.join(", ") : value;
					}
                }
			}
		},

  },
};
</script>