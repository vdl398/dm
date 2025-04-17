<template>
   <headerblock />
</template>
<script>
import headerblock from './../../blocks/headerblock.vue'
export default {
    components: {
	    headerblock,
    },
    data() {
        return {};
    },
	provide() {
        return {
            appData: this,
        }
    },
    async mounted() {

    },
    methods: {

    },
};
</script>