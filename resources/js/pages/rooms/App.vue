<template>
<headerblock />
<div class="rooms">
    <roomcreate />
    <div class="list">
        <roomlist :param="list.param" />
    </div>
</div>
</template>
<script>
import headerblock from './../../blocks/headerblock.vue'
import roomlist from './../../blocks/roomlist.vue'
import roomcreate from './../../blocks/roomcreate.vue'

export default {
  components: {
    roomlist,
	headerblock,
	roomcreate,
  },
  data() {
    return {
        list: {
		    param: {owner: 'Y'}
		},
    };
  },
  mounted() {
  },
};
</script>