require('./bootstrap');


import { createApp } from 'vue';
import App from './components/App.vue'
import router from './router'
import store from './store'


let app = createApp(App).use(router).use(store)
app.component('debug', {
  props:['obj'], template: `<div class="text-gray font-mono text-xs whitespace-pre">{{JSON.stringify(obj,null,2)}}</div>`
})
app.mount("#app")
