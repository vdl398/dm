import './../../bootstrap';

import { createApp } from "vue";
import axios from 'axios'
import VueAxios from 'vue-axios'



// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'


import App from "./App.vue";
import {Auth} from './../../auth.js'


Auth.init().then(function(rs) {

    const vuetify = createVuetify({
      components,
      directives
    })


    const app = createApp(App);
    app.use(VueAxios, axios);
    app.use(vuetify);

    app.mount('#app');
});