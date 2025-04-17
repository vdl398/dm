<template>
	<div v-for="item in list" class="room_item">
		<a v-if="item.status != 'Y'" class="del_btn" @click="deleteRoom(item.code)" href="javascript:void(0)">Delete</a>
		<div class="preview">
		    <a class="start_btn" @click="openRoom(item)" href="javascript:void(0)">
			    <img :src="item.image" />
				<div v-if="item.status == 'Y'" style="color: #008080;">Join</div>
				<div v-else style="color: #008080;">Start</div>
			</a>
		</div>
		<div class="title">{{item.title}}</div>
	</div>
</template>
<script>
import {Request} from './../request.js'

export default {
    props: ['param'],
    data() {
        return {
            list: []
        };
    },
    async mounted() {
	    await this.updateList();
    },
    methods: {
	
	    async openRoom(item) { // перейти в коммнату,
		    if (item.status == 'Y') { //если активна комната то просто перейти
			    location.href = '/rooms/'+item.code;
				return true;
			}
		    let rs = await Request.get('room/checksocket', {room_code: item.code, start: 'Y'});  // запустить вебсокет сервер
			if (rs && (rs.status == 'success') && (rs.port > 0)) {
	            location.href = '/rooms/'+item.code;
			}
			else {
			    alert('socket error');
			}
	    },
		
	    async deleteRoom(code) { // удалить комнату
		    let rs = await Request.post('room/delete', {code: code});
            if (rs && (rs.status == 'success')) {
			   await this.updateList();
			}
			else {
				alert(rs.errors[0]);
			}
	    },
  
        async updateList() { // получить список комнат
            let rs = await Request.get('room/list', this.param || {});
			this.list = Object.assign([], rs.list);
		},

    },
};
</script>