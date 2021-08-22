require('./bootstrap');


import { createApp } from 'vue';
import App from './components/App.vue'
import Debug from './components/Debug.vue'
import Loading from './components/Loading.vue'
import Notification from './components/Notification.vue'
import router from './router'
import store from './store'


createApp(App).use(router).use(store)
  .component('debug',Debug)
  .component('loading',Loading)
  .component('notification',Notification)
  .mount("body")
