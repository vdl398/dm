<template>
    <div class="video-participant2">
         <a href="#" class="name-tag">{{appData.user_name}}</a>
		 <video v-show="appData.videoEnable"  :poster="'/images/default-avatar.jpg'" :ref="localUuid" autoplay="autoplay"></video>
		 <video v-show="!appData.videoEnable"  :poster="'/images/default-avatar.jpg'"></video>
    </div>  
    <remotestream v-if="mediaInit" v-for="item in appData.remoteConnectors" :uuid="item.code" :userName="item.user_name" :localUuid="localUuid" ref="remoteBlockList" />
</template>
<script>

import {Request} from './../../../request.js'
import {Ws} from './../../../ws.js'
import {Stream} from './../../../stream.js';
import {MediaMTXWebRTCPublisher} from './../../../MediaMTXWebRTCPublisher.js';
import remotestream from './remotestream.vue'

export default {
    components: {
	    remotestream, // омпонент для вывода удаленных участников
    },
    props: ['localUuid'],
    data() {
        return {
			videoDevicesExist: false,
			audioDevicesExist: false,
            local: {
		        stream: null
	        },
			remote: {
			    list: {},
			},
			mediaInit: false,
        };
    },
	provide() {
        return {
            videohdl: this,
        }
    },
    inject: ['appData'],
    async mounted() {
	    await this.init();
    },
    methods: {

	    async updateConnectors() { // актуализовать участников
	        let rs = await Request.get('room/item', {code: this.appData.code});
		    this.appData.remoteConnectors = (rs.hasOwnProperty('remoteConnectors'))? Object.assign([], rs.remoteConnectors) : [];
			this.appData.resizeBlocks();
		},
		
		async audioToggle() { // аудио вкл/выкл
		    if (this.audioDevicesExist) {
		        const audio = this.$refs[this.localUuid].srcObject.getTracks().filter(track => track.kind === "audio")[0];
                audio.enabled = !audio.enabled;
			    this.appData.audioEnable = audio.enabled;
				Ws.send({action: 'track_change', data: {uuid: this.localUuid, audioEnable: this.appData.audioEnable, videoEnable: this.appData.videoEnable}});
			}
		},
		
		async videoToggle() { // видео вкл/выкл
		    if (this.videoDevicesExist) {
		        const video = this.$refs[this.localUuid].srcObject.getTracks().filter(track => track.kind === "video")[0];
                video.enabled = !video.enabled;
			    this.appData.videoEnable = video.enabled;
				Ws.send({action: 'track_change', data: {uuid: this.localUuid, audioEnable: this.appData.audioEnable, videoEnable: this.appData.videoEnable}});
			}
		},

	    async init() {
		    let rsInit = await this.initMedia();
			if (!rsInit || !this.appData.mode) {
			    return false;
			}
		    document.addEventListener('wsRecv', (e)=> {
	            this.onWs(e.detail);

				Object.entries(this.remote.list).forEach(([key, value]) => {
                    if (this.remote.list[key] && this.remote.list[key].stream) this.remote.list[key].stream.wsOnMessage(e.detail);
                });

				if (this.$refs.remoteBlockList) {
				    for(let k in this.$refs.remoteBlockList) {
					    this.$refs.remoteBlockList[k].onWs(e.detail);
					}
				}

            }, false);
			if (this.appData.mode == 'PP') { // без медиасервера
			    Ws.send({action: 'set_streamer'}); // чтоб к нам могли подключится другие участники для организации сеанса webrtc
				this.mediaInit = true;
			}
			if (this.appData.mode == 'S') { // с медиасервером
			    new MediaMTXWebRTCPublisher({
                    url:  new URL('whip', 'https://'+Request.APP_DOMAIN+':8889/' + this.localUuid + '/publish'),
                    stream: this.local.stream,
                    videoCodec: '',
                    videoBitrate: '10000',
                    audioCodec: '',
                    audioBitrate: '32',
                    audioVoice: true,
                    onError: (err) => {
	                    console.log(err);
                    },
                    onConnected: (evt) => {
						this.mediaInit = true;
						//сообщить другим участникам что у нас есть канал на медиасервере , и чтоб актуализовали подключения
						Ws.send({action: 'update_connectors', data: {uuid: this.localUuid, audioEnable: this.appData.audioEnable, videoEnable: this.appData.videoEnable}});
                    },
                });
			}
		},
	
	    onWs(resp) { // обработка входящих сообщение вебсокета
		    if (resp && resp.data?.action == 'call') { // получили запрос от удаленного участника на подключение, вызывается в блоке remotestream.vue у удаленого участника
				this.remote.list[resp.from] = {
		            uuid: resp.from,
			        streamer: false,
			        stream: new Stream(this.localUuid, resp.from, this.local.stream, false)
		        };
				
				this.remote.list[resp.from].stream.audioDevicesExist = this.audioDevicesExist;
				this.remote.list[resp.from].stream.videoDevicesExist = this.videoDevicesExist;
				
		        this.remote.list[resp.from].stream.onClose = (remoteUuid) => {
			        this.remote.list[remoteUuid] = null;
                };				
			    this.remote.list[resp.from].stream.recvEnable = true;
	            this.remote.list[resp.from].stream.senderInit(); // инициализируем отправителя
	        }
			
		    if (resp && resp.data?.action == 'media_success') { // удаленный участник получил медиапоток
			    this.remote.list[resp.from].stream.recvEnable = false;
				this.updateConnectors();
		    }
			
			if (resp.action == 'update_connectors') { // ктото отправил запрос актуализовать подключения
			    this.updateConnectors();
			}
		},

        async initMedia() {
            return new Promise((resolve, reject) => {
                if (this.appData.connector) {
	                let browserUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
                    if (!browserUserMedia ) {
			            alert("Your browser doesn't support WebRTC");
						resolve(false);
		            }
		            else {
                        navigator.mediaDevices.enumerateDevices().then((devices) => {
                            this.videoDevicesExist = (devices.filter(device => device.kind === 'videoinput').length > 0);
	                        this.audioDevicesExist = (devices.filter(device => device.kind === 'audioinput').length > 0);
							this.appData.videoEnable = this.videoDevicesExist;
							this.appData.audioEnable = this.audioDevicesExist;
                            let getUserMedia = browserUserMedia.bind( navigator );
                            getUserMedia(
	                            {
		                            audio: this.appData.audioEnable,
		                            video: this.appData.videoEnable,
	                            },
	                            (media)=> {
					                this.local.stream = media;
									this.$refs[this.localUuid].srcObject = this.local.stream;
					                resolve(true);
	                            },
	                            ( err )=> {
							        console.log({mediaerr: err});
                                    resolve(false);
	                            }
                            );
                        });
		            }
                }
				else {
				    resolve(false);
				}
            });	
		},

    },
  
};
</script>