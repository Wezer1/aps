import Vue from "vue"
import Store from "./components/StorePosts.vue"
import Update from "./components/UpdatePosts.vue";
import Delete from "./components/DeletePosts.vue";
import "./bootstrap"
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
require('./bootstrap');



createApp(App)
    .use(router)
    .mount('#app')


new Vue({
    el: '#app',
    components: {
        Store,
        Update,
        Delete
    }
})

