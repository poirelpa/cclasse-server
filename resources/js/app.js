require('./bootstrap');


import { createApp } from 'vue';
import App from './components/App.vue'
import Debug from './components/utilities/Debug.vue'
import Loading from './components/utilities/Loading.vue'
import Notification from './components/utilities/Notification.vue'
import router from './router'
import store from './store'


createApp(App).use(router).use(store)
  .component('debug',Debug)
  .component('loading',Loading)
  .component('notification',Notification)
  .mount("body")
