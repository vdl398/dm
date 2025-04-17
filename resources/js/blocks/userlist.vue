<template>
    <div>
        list
		
		<div v-for="item in list">
		    <div><img :src="item.image" width="40"></div>
		    <div>{{item.name}}</div>
		</div>
		
    </div>
</template>
<script>
import {Request} from './../request.js'

export default {
    data() {
        return {
            list: []
        };
    },
    async mounted() {
	    await this.updateList();
    },
    methods: {
  
        async updateList() {
            let rs = await Request.get('userlist', {});
			console.log(rs);
			this.list = Object.assign([], rs.list);
		},

    },
};
</script>