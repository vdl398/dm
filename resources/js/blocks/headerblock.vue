<template>
  <header id="nav" class="site-header position-fixed text-white bg-dark">
    <nav id="navbar-example2" class="navbar navbar-expand-lg py-2">
      <div class="container ">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar2" aria-labelledby="offcanvasNavbar2Label">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbar2Label">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
              aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav align-items-center justify-content-end align-items-center flex-grow-1 ">
              <li class="nav-item">
                <a class="nav-link active me-md-4" href="/">Home</a>
              </li>
              <li v-if="isAuth" class="nav-item">
                <a class="nav-link active me-md-4" href="/rooms">My rooms</a>
              </li>
              <authblock />
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>
</template>
<script>

import {Request} from './../request.js'
import roomcreate from './roomcreate.vue'
import authblock from './authblock.vue'
import {Auth} from './../auth.js'

export default {
  components: {
    authblock,
    roomcreate,
  },
  data() {
    return {

    };
  },
  computed: {
     isAuth() {
	     return Auth.isAuth();
	 }
  },
  async mounted() {

  },
  methods: {
  


  },
};
</script>