<template>
<headerblock />
<div class="_home_wr">
<roomcreate :big_btn="true" />
</div>
</template>
<script>
import headerblock from './../../blocks/headerblock.vue'
import roomcreate from './../../blocks/roomcreate.vue'


export default {
  components: {
	headerblock,
	roomcreate,
  },
  data() {
    return {

    };
  },
  mounted() {

  },
};
</script>