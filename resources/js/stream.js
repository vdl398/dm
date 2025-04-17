export class Stream {

    local = {
	    uuid: null
	};

	remote = {
	    uuid: null,
		streamerUuid: null,
		connectStatus: false
	};
	
	handle = null;
	media = null;
	configuration = null;
	options = null;
	recvEnable = false;
	
	videoDevicesExist = false;
	audioDevicesExist = false;
	isStreamer = false;
	
	onTrack = function(media, remoteUuid) {
	};
	
	onConnect = function(remoteUuid) {
	};
	
	onClose = function (remoteUuid) {
    };

    constructor(localUuid, remoteUuid, media, isStreamer=false) {
        this.local.uuid = localUuid;
		this.remote.uuid = remoteUuid;	
		this.configuration = { iceServers: [{ urls: "stuns:stun.l.google.com:19302" }] };
        this.options = {"optional": [{"DtlsSrtpKeyAgreement": true}]};
		this.media = media;
		this.isStreamer = isStreamer;
    }
	
	wsOnMessage(resp) {
		if (!(resp && this.recvEnable && resp.hasOwnProperty('data') && (resp.to == this.local.uuid) && (resp.from == this.remote.uuid))) return false;
		if (resp.data.hasOwnProperty('action')) {
			switch(resp.data.action) {
				case 'setCandidate':
				    if (resp.data.candidate) {
						this.handle.addIceCandidate(resp.data.candidate).catch(function(e) {console.log('addIceCandidate err');});
					}
				break;
				case 'setDescription':
				    if (resp.data.description) {
					    switch (resp.data.description.type) {
		                    case "offer":
			                    this.offerResv(resp.data.description);
			                break;
		                    case "answer":
			                    this.answerResv(resp.data.description);
			                break;
                            default:
                                console.log("Unsupported SDP type");
                            break;
	                    }
					}
				break;
			}
		}
	}

	wsSend(data={}) {
	    document.dispatchEvent(new CustomEvent('wsSend', {detail: {action: 'rtc', data: data}}));
	};	
	
	receiverInit() {
	    this.handle = new RTCPeerConnection(this.configuration, this.options);
		this.handle.ontrack = (evt)=> {
			this.media = evt.streams[0];
			this.onTrack(this.media, this.remote.uuid);
        };	
		
		this.handle.addEventListener("iceconnectionstatechange", ev => {
			if (this.recvEnable ) {
			    this.recvEnable = false;
				this.remote.connectStatus = true;
				if (this.isStreamer) this.onConnect(this.remote.uuid);
			}
            if (ev.currentTarget.iceConnectionState == 'disconnected') {
				this.onClose(this.remote.uuid);
			}
        }, false);	
		
		this.handle.addEventListener("icecandidateerror", ev => {
            this.close();
        }, false);
		
		this.handle.onclose = ()=> {
            this.onClose(this.remote.uuid);
        };
	}

	
    senderInit() {
	    if (!this.media) {
		    console.log('media err');
			return false;
		}
	    this.handle = new RTCPeerConnection(this.configuration, this.options);
        this.handle.onicecandidate = (evt)=> {
            this.wsSend({to: this.remote.uuid, from: this.local.uuid, data: {action: 'setCandidate', candidate: evt.candidate }});
        };
	    this.handle.onnegotiationneeded = ()=> {
		    this.handle.createOffer({'OfferToReceiveAudio': true, 'OfferToReceiveVideo': true}).then((offer)=> {
				return this.handle.setLocalDescription(offer);
            }).then(()=> {
                this.wsSend({to: this.remote.uuid, from: this.local.uuid, data: {action: 'setDescription', description: this.handle.localDescription }});
            }).catch((e)=> {console.log('createOffer err');});
		};
		
		if (this.handle && this.audioDevicesExist) this.handle.addTrack(this.media.getAudioTracks()[0], this.media);
        if (this.handle && this.videoDevicesExist) this.handle.addTrack(this.media.getVideoTracks()[0], this.media);	
	}
	
    offerResv(desc) {
		this.receiverInit();
		this.handle.setRemoteDescription(desc);
        this.handle.createAnswer({'OfferToReceiveAudio': true, 'OfferToReceiveVideo': true}).then((answer)=> {
            return this.handle.setLocalDescription(answer);        
        }).then(()=> {
            this.wsSend({to: this.remote.uuid, from: this.local.uuid, data: {action: 'setDescription', description: this.handle.localDescription }});
        }).catch((e)=> {console.log('createAnswer err');});
    }
	
	answerResv(desc) {
        if (this.handle) {
			this.handle.setRemoteDescription(desc).catch(function(e) {console.log('setRemoteDescription err');});
		}
    }
	
	close() {
		this.handle.close();
	}
	

}