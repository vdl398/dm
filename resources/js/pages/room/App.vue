<template>
   <headerblock />
   <div v-if="false" class="left-side">
     <div class="navigation">
       <a href="#" class="nav-link icon">
         <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home" viewBox="0 0 24 24">
           <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
           <path d="M9 22V12h6v10"/>
         </svg>
       </a>
       <a href="#" class="nav-link icon">
         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square">
           <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
         </svg>
       </a>
       <a href="#" class="nav-link icon">
         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-hard-drive">
           <line x1="22" y1="12" x2="2" y2="12"/>
           <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
           <line x1="6" y1="16" x2="6.01" y2="16"/>
           <line x1="10" y1="16" x2="10.01" y2="16"/>
         </svg>
       </a>
       <a href="#" class="nav-link icon">
         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
           <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
           <circle cx="9" cy="7" r="4"/>
           <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
           <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
         </svg>
       </a>
        <a href="#" class="nav-link icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder" viewBox="0 0 24 24">
            <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
          </svg>
       </a>
       <a href="#" class="nav-link icon">
         <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings" viewBox="0 0 24 24">
           <circle cx="12" cy="12" r="3"/>
           <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/>
         </svg>
       </a>
     </div>
   </div>
   <div class="app-main">

     <div class="video_wr">
       <videoblock ref="videoblock" :localUuid="connector" v-if="init" />
	   <div v-if="!init && roomAvailable" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
	       <img width="100" :src="'/images/preloader.gif'" />
	   </div>
	   <div v-else-if="!init && !roomAvailable" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
	       {{pageMsg}}
	   </div>
     </div>

     <div v-if="init" class="video-call-actions ">

       <button v-if="audioEnable" class="video-action-button mic-on" @click="audioToggle()"></button>
	   <button v-else class="video-action-button mic-off" @click="audioToggle()"></button>

       <button v-if="videoEnable" class="video-action-button camera-on" @click="videoToggle()"></button>
	   <button v-else class="video-action-button camera-off" @click="videoToggle()"></button>
	   <button style="background: #FFF; width: 46px; height: 46px; border-radius: 8px; margin-left: 10px;" @click="copyLink()">
	       <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.975 14.51a1.05 1.05 0 0 0 0-1.485 2.95 2.95 0 0 1 0-4.172l3.536-3.535a2.95 2.95 0 1 1 4.172 4.172l-1.093 1.092a1.05 1.05 0 0 0 1.485 1.485l1.093-1.092a5.05 5.05 0 0 0-7.142-7.142L9.49 7.368a5.05 5.05 0 0 0 0 7.142c.41.41 1.075.41 1.485 0zm2.05-5.02a1.05 1.05 0 0 0 0 1.485 2.95 2.95 0 0 1 0 4.172l-3.5 3.5a2.95 2.95 0 1 1-4.171-4.172l1.025-1.025a1.05 1.05 0 0 0-1.485-1.485L3.87 12.99a5.05 5.05 0 0 0 7.142 7.142l3.5-3.5a5.05 5.05 0 0 0 0-7.142 1.05 1.05 0 0 0-1.485 0z" fill="#000000"/></svg>
	   </button>

        <div v-if="false" class="participants">
            <div class="participant profile-picture">
                <img src="https://images.unsplash.com/photo-1576110397661-64a019d88a98?ixlib=rb-1.2.1&auto=format&fit=crop&w=1234&q=80" alt="pp">
            </div>
            <div class="participant profile-picture">
                <img src="https://images.unsplash.com/photo-1566821582776-92b13ab46bb4?ixlib=rb-1.2.1&auto=format&fit=crop&w=900&q=60" alt="pp">
            </div>
            <div class="participant profile-picture">
                <img src="https://images.unsplash.com/photo-1600207438283-a5de6d9df13e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1234&q=80" alt="pp">
            </div>
            <div class="participant profile-picture">
                <img src="https://images.unsplash.com/photo-1581824283135-0666cf353f35?ixlib=rb-1.2.1&auto=format&fit=crop&w=1276&q=80" alt="pp">
            </div>
            <div class="participant-more">2+</div>
        </div>

     </div>
   </div>
   
   
   <div v-if="init && chatShow" class="right-side">
    <button class="btn-close-right" @click="()=>{chatShow=!chatShow; resizeBlocks();}">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="feather feather-x-circle" viewBox="0 0 24 24">
        <defs></defs>
        <circle cx="12" cy="12" r="10"></circle>
        <path d="M15 9l-6 6M9 9l6 6"></path>
      </svg>
    </button>
	<chat />
  </div>
  <div v-if="init && !chatShow" style="display: flex; align-items: center;">
       <button class="video-action-button" @click="()=>{chatShow=!chatShow; resizeBlocks();}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
       </button>
  </div>
  
  <button class="expand-btn">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
  </button>

</template>
<script>
import headerblock from './../../blocks/headerblock.vue'
import videoblock from './blocks/videoblock.vue'
import chat from './blocks/chat.vue'
import {Request} from './../../request.js'
import {Auth} from './../../auth.js'
import {Ws} from './../../ws.js'
import $ from 'jquery'

export default {
    components: {
	    headerblock, // header страницы
		videoblock, // компонент вывода медиапотоков
		chat // компонент чата
    },
    data() {
        return {
            code: document.getElementById('app').getAttribute('code'),
			connector: '',
			remoteConnectors: [],
			init: false,
			addMemberInput: '',
			user_name: '',
			videoEnable: true,
			audioEnable: true,
			chatShow: false,
			roomAct: false,
			roomAvailable: true,
			mode: '',
			pageMsg: '',
        };
    },
	provide() {
        return {
            appData: this,
        }
    },
    async mounted() {
	    if (!Auth.isAuth()) { // если не авторизованы, то открываем окно для ввода имени и создаем временного пользователя
		    document.dispatchEvent(new CustomEvent('openJoinForm', {}));	
			return;
		}
	    await new Promise(function(resolve, reject) {  setTimeout(function(){resolve(true);}, 2000);});
		let rsAct = null;
		while(!this.roomAct) { // ждем старта комнаты
		    rsAct = await Request.get('room/checksocket', {room_code: this.code});
		    if (rsAct && (rsAct.status == 'success') && (rsAct.port > 0)) {
		        this.roomAct = true;
				 this.roomAvailable = true;
		    }
			else {
				this.setError('Room is currently unavailable');
			    await new Promise(function(resolve, reject) {  setTimeout(function(){resolve(true);}, 5000);});
			}
		}
        let rsRm = await Request.post('room/addmember', {room_code: this.code, current: 'Y', active: 'Y'}); // добавляем участника
        if (!rsRm || (rsRm.status != 'success')) {
			 this.setError('member error');
             return false;
		}

		if (rsRm.activeExist == 'Y') { // комната уже открыта в другой вкладке
			this.setError('Room is open in another tab');
			return false;
		}
		
	    let rs = await Request.get('room/item', {code: this.code}); //получить данные комнаты
		this.connector = (rs.item && rs.item.hasOwnProperty('connector'))? rs.item.connector : ''; //личный коннектор
		this.remoteConnectors = (rs.hasOwnProperty('remoteConnectors'))? Object.assign([], rs.remoteConnectors) : []; //коннекторы остальных участников
		this.user_name = (rs.hasOwnProperty('user_name'))? rs.user_name : '';
		this.mode = (rs.item && rs.item.hasOwnProperty('mode'))? rs.item.mode : '';
		let initSuccess = false;
        while(!initSuccess) {
		    initSuccess = await Ws.init(this.connector, rsAct.port); //подключаемся к вебсокету
			if (!initSuccess) {
			    await new Promise(function(resolve, reject) {  setTimeout(function(){resolve(true);}, 500);});
			}
		}
		Ws.send({action: 'room_init', data: {code: this.code}}); // присоеденяемся к комнате на вебсокете
		this.init = true;
		this.resizeBlocks();
    },
	
	
    methods: {
	
		resizeBlocks() {
		    setTimeout(()=>{
                let parent = $('.video_wr');
		        let child = parent.find('.video-participant2');
                let s = parent.width() * parent.height();
                let chs = s / child.length;
                let hs = Math.sqrt(chs);
                child.width(hs);
                child.height(hs);
			}, 200);
		},
		
		setError(msg='') {
			this.pageMsg = msg;
			this.roomAvailable = false;
		},

		async audioToggle() {
		    this.$refs.videoblock.audioToggle();
		},
		
		async videoToggle() {
		    this.$refs.videoblock.videoToggle();
		},
		
		copyLink() {
            navigator.clipboard.writeText('https://'+Request.APP_DOMAIN+'/rooms/'+this.code);
		},
		

    },
	
	
};
</script>