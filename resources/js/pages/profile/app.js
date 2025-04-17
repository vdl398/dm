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


import Hdr from "./Header.vue";
import {Auth} from './../../auth.js'

Auth.init().then(function(rs) {

    const vuetify = createVuetify({
      components,
      directives
    })


    const Header = createApp(Hdr);
    Header.use(VueAxios, axios);
    Header.use(vuetify);

    Header.mount('#header');
});