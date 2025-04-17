<template>
	<v-dialog v-model="popup.show">
	        <div>
		        <div style="display: flex; width: 100%; align-items: center; justify-content: center; margin-top: 20px; margin-bottom: 20px;">New Room</div>
	            <div style="width: 100%; background: #FFFFFF;">
				    <input v-model="popup.addMemberInput" placeholder="invite member">
		        </div>
		        <div style="display: flex; width: 100%; align-items: center; justify-content: center; margin-top: 20px; margin-bottom: 20px;">
		            <div style="padding: 5px 15px; background: #9dcf00; color: #FFFFFF; cursor: pointer;" @click="add()">Add</div>
		            <div style="padding: 5px 15px; border: 1px solid #000000; margin-left: 40px; cursor: pointer;" @click="cancel()">Cancel</div>
			    </div>
		    </div>
	</v-dialog>
</template>
<script>
import {Request} from './../../../request.js'

export default {
  data() {
    return {
		popup: {
			show: false,
			addMemberInput: '',
		},
    };
  },
  async mounted() {

  },
  methods: {
  
        showPopup() {
           this.popup.show = true;
		},
		
		cancel() {
            this.popup.show = false;
		},
  
		async add() {

		
		/*
            let rs = await Request.post('room/addmember', {room_code: this.code, email: this.popup.addMemberInput});
            if (rs && (rs.status == 'success')) {
			   alert('ok');
			   return true;
			}
			if (rs.errors) {
			    console.log({err: rs.errors});
			    for (const [key, value] of Object.entries(rs.errors)) {
                    if (this.popup.fields.hasOwnProperty(key) && value) {
					   this.popup.fields[key].errorMessage = (Array.isArray(value))? value.join(", ") : value;
					}
                }
			}
			*/
			
			
			
		},

  },
};
</script>