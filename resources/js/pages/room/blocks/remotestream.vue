<template>
<div class="video-participant2">
    <div class="participant-actions">
        <button v-if="audioEnable" class="video-action-button mic-on"></button>
	    <button v-else class="video-action-button mic-off"></button>
        <button v-if="videoEnable" class="video-action-button camera-on"></button>
	    <button v-else class="video-action-button camera-off"></button>
    </div>
    <a href="#" class="name-tag">{{userName}}</a>
	<video v-show="videoEnable" :poster="'/images/default-avatar.jpg'" :ref="uuid" autoplay="autoplay"></video>
	<video v-show="!videoEnable" :poster="'/images/default-avatar.jpg'"></video>
</div>  
</template>
<script>

import {Request} from './../../../request.js'
import {Ws} from './../../../ws.js'
import {Stream} from './../../../stream.js';
import {MediaMTXWebRTCReader} from './../../../MediaMTXWebRTCReader.js';



export default {
    props: ['localUuid', 'uuid', 'userName'],
    data() {
        return {
			handle: null,
			videoEnable: true,
			audioEnable: true,
        };
    },
    inject: ['appData', 'videohdl'],
    async mounted() {
	    await this.init();
    },
    methods: {
	

	    connect(remoteUuid) {
		    if (remoteUuid != this.uuid) return false;
		    this.handle.recvEnable = true;
		    this.handle.onConnect = (remoteUuid) => { // медиапоток получен
			    if (remoteUuid != this.uuid) return false;
				window.streamList[this.uuid] = this.handle.media;
				if (this.$refs[this.uuid]) {
				    this.$refs[this.uuid].srcObject = window.streamList[this.uuid]; // вывод медиапотока в тег video
			        Ws.send({action: 'room_note', to: remoteUuid, from: this.localUuid, data: {action: 'media_success', streamerUuid: remoteUuid}});	
				}
		    };
	        Ws.send({action: 'rtc', data: {to: remoteUuid, from: this.localUuid, data: {action: 'call'}}}); // запрс медипотока участника
	    },

	    async init() {
			if (this.appData.mode == 'PP') { // без медиасервера
			    if (!window.streamList[this.uuid]) {
	                Ws.send({action: 'connect', data: {to: this.uuid, from: this.localUuid}}); // начать обмен медиапотоками
			    }
			    else {
			        this.$refs[this.uuid].srcObject = window.streamList[this.uuid]; // уже есть существующий медиапоток
			    }
			}
			if (this.appData.mode == 'S') { // с медиасервером
               if (!window.streamList[this.uuid]) { // получить стрим с медиасервера
                    new MediaMTXWebRTCReader({
                        url: new URL('whep', 'https://'+Request.APP_DOMAIN+':8889/' + this.uuid + '/'),
                        onError: (err) => {
                            console.log(err);
                        },
                        onTrack: (evt) => {
					        window.streamList[this.uuid] = evt.streams[0];
                            this.$refs[this.uuid].srcObject = window.streamList[this.uuid];
                        },
                    });
				}
			    else {
			        this.$refs[this.uuid].srcObject = window.streamList[this.uuid]; // уже есть существующий медиапоток
			    }
			}
		},
	
	    onWs(resp) {
		    if (!this.$refs[this.uuid]) return false;
		
		    if (resp.hasOwnProperty('action')) {

			    switch(resp.action) {
				    case 'track_change': // есть изменения  трека удаленного участника
				        if (resp.data.uuid == this.uuid) {
						    this.videoEnable = resp.data.videoEnable;
			                this.audioEnable = resp.data.audioEnable;
						}
				    break;
				    case 'set_connector': // создать стрим для отправки медиапотока для удаленного участника
				        if (resp.status) {
							if (resp.id1 == this.uuid) {
							    this.handle = new Stream(this.localUuid, resp.id1, null, true);
								this.handle.onClose = (remoteUuid) => {
			                        this.handle = null;
									window.streamList[remoteUuid] = null;
									setTimeout(() => {
										this.videohdl.updateConnectors();
									}, 1000);
                                };
								this.connect(resp.id1);	
							}

					    }
				    break;
					case 'remote_closed': // приходит при отключении удаленного участника
					    if (resp.id == this.uuid) {
						    if (this.handle) {
						        this.handle.recvEnable = false;
			                    this.handle = null;
							}
							delete window.streamList[this.uuid];
							setTimeout(() => {
								this.videohdl.updateConnectors();
							}, 500);
					    }
					break;
				}
			}
			
			
		    if (resp && resp.data?.action == 'media_success') {
				if (resp.from == this.uuid) {
				    if (this.handle) this.handle.recvEnable = false;
				}
		    }
			
			if (this.handle) this.handle.wsOnMessage(resp);
			
		},
	
	
	
	
	
    },
  
};
</script>