<template>
    <div class="chat-container">
      <div v-if="false" class="chat-header">
        <button class="chat-header-button">
         Live Chat
        </button>
      </div>
      <div class="chat-area" ref="chatarea">
        <div v-for="item in list" class="message-wrapper" v-bind:class="{ 'reverse' : item.owner == 'Y'}">
          <div class="profile-picture">
			<div class="_avatar" :style="[{ backgroundImage: `url(${item.image})`}]"></div>
          </div>
          <div class="message-content">
            <div class="message">
			    <p class="name">{{item.name}}</p>
			    <div v-if="item.files.length">
				    <div v-for="f in item.files">
					    <a target="_blank" v-if="f.type == 'image'" :href="f.src" style="margin-bottom: 10px;"><img :src="f.src" width="200" /></a>
				        <a target="_blank" v-else :href="f.src" style="margin-bottom: 10px;">
						    <div style="background: #C0C0C0; display: flex; flex-direction: column; align-items: center; padding: 10px;width: 100px;">
                            <div style="width: 50px;">
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 305 305" xml:space="preserve">
                                    <g>
	                                    <rect x="45.885" y="7.5" style="fill:#FFDB77;" width="173.23" height="250"/>
	                                    <polygon style="fill:#28D2E4;" points="219.115,47.5 219.115,257.5 85.885,257.5 85.885,297.5 259.115,297.5 259.115,47.5 	"/>
	                                    <g>
		                                    <path style="fill:#22313F;" d="M259.115,40h-32.5V7.5c0-4.142-3.357-7.5-7.5-7.5H45.885c-4.143,0-7.5,3.358-7.5,7.5v250
			                                 c0,4.142,3.357,7.5,7.5,7.5h32.5v32.5c0,4.142,3.357,7.5,7.5,7.5h173.23c4.143,0,7.5-3.358,7.5-7.5v-250
			                                 C266.615,43.358,263.258,40,259.115,40z M53.385,15h158.23c0,8.349,0,226.321,0,235c-5.558,0-147.952,0-158.23,0
			                                 C53.385,250,53.385,15,53.385,15z M251.615,290H93.385v-25h125.73c4.143,0,7.5-3.358,7.5-7.5V55h25V290z"/>
		                                    <path style="fill:#22313F;" d="M92.465,78.713h80.07c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-80.07
			                                 c-4.142,0-7.5,3.358-7.5,7.5C84.965,75.355,88.322,78.713,92.465,78.713z"/>
		                                    <path style="fill:#22313F;" d="M92.465,122.211h80.07c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-80.07
			                                 c-4.142,0-7.5,3.358-7.5,7.5S88.322,122.211,92.465,122.211z"/>
		                                    <path style="fill:#22313F;" d="M92.465,165.709h80.07c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-80.07
			                                 c-4.142,0-7.5,3.358-7.5,7.5S88.322,165.709,92.465,165.709z"/>
	                                    </g>
                                    </g>
                                </svg>
							</div>
                            <div style="word-break: break-all; color: #000;">
						        {{f.name}}
							</div>
							</div>
						</a>
					</div>
				</div> 
			    {{item.message}}
			</div>
          </div>
        </div>
      </div>
	  <div v-if="remote.typing.show" style="color: #000; font-size: 12px; margin-left: 20px;">
	    {{remote.typing.name}} typing...
	  </div>
	  <div class="ch_f_b">
	    <div class="ch_f_item" v-for="(item, index) in files">
		    <div class="ch_f_n">{{item.name}}</div>
			<div class="ch_f_d" @click="deleteFile(index)">
                <svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 482.428 482.429" xml:space="preserve">
                    <g>
	                    <g>
		                    <path d="M381.163,57.799h-75.094C302.323,25.316,274.686,0,241.214,0c-33.471,0-61.104,25.315-64.85,57.799h-75.098
			                 c-30.39,0-55.111,24.728-55.111,55.117v2.828c0,23.223,14.46,43.1,34.83,51.199v260.369c0,30.39,24.724,55.117,55.112,55.117
			                 h210.236c30.389,0,55.111-24.729,55.111-55.117V166.944c20.369-8.1,34.83-27.977,34.83-51.199v-2.828
			                C436.274,82.527,411.551,57.799,381.163,57.799z M241.214,26.139c19.037,0,34.927,13.645,38.443,31.66h-76.879
			                C206.293,39.783,222.184,26.139,241.214,26.139z M375.305,427.312c0,15.978-13,28.979-28.973,28.979H136.096
			                c-15.973,0-28.973-13.002-28.973-28.979V170.861h268.182V427.312z M410.135,115.744c0,15.978-13,28.979-28.973,28.979H101.266
			                c-15.973,0-28.973-13.001-28.973-28.979v-2.828c0-15.978,13-28.979,28.973-28.979h279.897c15.973,0,28.973,13.001,28.973,28.979
			                V115.744z"/>
		                    <path d="M171.144,422.863c7.218,0,13.069-5.853,13.069-13.068V262.641c0-7.216-5.852-13.07-13.069-13.07
			                    c-7.217,0-13.069,5.854-13.069,13.07v147.154C158.074,417.012,163.926,422.863,171.144,422.863z"/>
		                    <path d="M241.214,422.863c7.218,0,13.07-5.853,13.07-13.068V262.641c0-7.216-5.854-13.07-13.07-13.07
			                   c-7.217,0-13.069,5.854-13.069,13.07v147.154C228.145,417.012,233.996,422.863,241.214,422.863z"/>
		                    <path d="M311.284,422.863c7.217,0,13.068-5.853,13.068-13.068V262.641c0-7.216-5.852-13.07-13.068-13.07
			                   c-7.219,0-13.07,5.854-13.07,13.07v147.154C298.213,417.012,304.067,422.863,311.284,422.863z"/>
	                    </g>
                    </g>
                </svg>
			</div>
		</div>
	  </div>
      <div class="chat-typing-area-wrapper">
        <div class="chat-typing-area">
		  <input v-show="false" id="msg_file" type="file" ref="msgfile" @change="selectFile" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" />
          <button class="send-button" @click="()=>{$refs.msgfile.click();}" style="background-color: #FFF;">
            <svg height="20px" width="20px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 197.696 197.696" xml:space="preserve">
                <g>
	                <path style="fill:#010002;" d="M179.546,73.358L73.111,179.783c-13.095,13.095-34.4,13.095-47.481,0.007
		             c-13.095-13.095-13.095-34.396,0-47.495l13.725-13.739l92.696-92.689l11.166-11.159c8.829-8.833,23.195-8.833,32.038,0
		             c8.829,8.836,8.829,23.209,0,32.041L145.79,76.221l-74.383,74.383l-1.714,1.714c-4.42,4.413-11.606,4.42-16.026,0
		            c-4.42-4.413-4.42-11.599,0-16.019l76.101-76.097c1.582-1.578,1.582-4.141,0-5.723c-1.585-1.582-4.134-1.582-5.723,0
		            l-76.097,76.101c-7.58,7.573-7.58,19.895,0,27.464c7.566,7.573,19.884,7.566,27.464,0l1.714-1.714l74.383-74.383l29.465-29.472
		            c11.989-11.989,12-31.494,0-43.487c-11.986-11.986-31.49-11.986-43.487,0l-11.152,11.159L33.64,112.84l-13.725,13.732
		            c-16.252,16.244-16.252,42.685,0,58.937c16.241,16.252,42.678,16.248,58.929,0L185.265,79.081c1.585-1.578,1.585-4.137,0-5.719
		            C183.68,71.777,181.131,71.777,179.546,73.358z"/>
                </g>
            </svg>
          </button>
          <textarea ref="ch_input" v-model="input" id="ch_input" rows="2" @keyup="chatInputResize"></textarea>
          <button @click="addMessage" class="send-button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send" viewBox="0 0 24 24">
              <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

	
</template>

<script>

import {Request} from './../../../request.js'
import {Ws} from './../../../ws.js'



export default {
    data() {
        return {
            list: [],
			input: '',
			files: [],
			remote: {
			    typing: {
				    show: false,
					name: '',
				}
			},
        };
    },
    inject: ['appData'],
    async mounted() {
		document.addEventListener('wsRecv', (e)=> {
		    if (e.detail && e.detail.hasOwnProperty('event')) {
	            this.onWs(e.detail);
			}
        }, false);
		await this.updateList();
    },
    methods: {
	
	    onWs(data) {
		    if (data.event == 'chat_update') {
			    this.updateList();
			}
		    if ((data.event == 'chat_typing') && (data.user_name != this.appData.user_name)) {
			    this.remote.typing.show = true;
				this.remote.typing.name = data.user_name;
                setTimeout(()=>{ 
			        this.remote.typing.show = false;
				    this.remote.typing.name = '';
				}, 1000);
			}
		},
	
        async updateList() {
            let rs = await Request.get('room/getmessages', {room_code: this.appData.code});
			this.list = Object.assign([], rs.messages);
			setTimeout(()=> {
			    this.$refs.chatarea.scrollTop = this.$refs.chatarea.scrollHeight;
			}, 100);
		},

		async addMessage() {
            let formData = new FormData();
			for(let file of this.files) {
                formData.append('file[]', file, file.name);
			}
			formData.append('room_code', this.appData.code);
			formData.append('message', this.input);
		    let rs = await Request.post('room/addmessage', formData, {formData: true, headers: {'Content-Type': 'multipart/form-data'}});
            if (rs && (rs.status == 'success')) {
			   this.input = '';
			   this.files = [];
			   Ws.send({action: 'room_note', data: {event: 'chat_update'}});
			   return true;
			}
            return false;
		},
		selectFile(e) {
		    this.files.push(this.$refs.msgfile.files[0]);
			this.$refs.msgfile.value = '';
		},
		deleteFile(index) {
		    let list = [];
		    for (let k in this.files) {
			    if (k != index) list.push(this.files[k]);
			}
			this.files = Object.assign([], list);
		},
        chatInputResize(e)
        {
	        if(this.$refs.ch_input.scrollTop > 0){
		         this.$refs.ch_input.style.height = this.$refs.ch_input.scrollHeight + "px";
            }
			if (!this.input) this.$refs.ch_input.style.height = "30px";
			Ws.send({action: 'room_note', data: {event: 'chat_typing', user_name: this.appData.user_name}});
        },

    },
  
};
</script>