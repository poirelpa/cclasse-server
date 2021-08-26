import { createApp } from 'vue'
import router from './router.js'
import store from './store.js'
import App from './App.vue'
import Debug from './utils/Debug.vue'
import Loading from './utils/Loading.vue'
import Notification from './utils/Notification.vue'
import mixin from './utils/mixin.js'
import authModule from './auth'
import classesModule from './classes'
import programmingsModule from './programmings'

authModule.init()
classesModule.init()
programmingsModule.init()

require('./bootstrap')

createApp(App)
  .use(router)
  .use(store)
  .mixin(mixin)
  .directive('focus', { mounted (el) { el.focus() } })
  .component('debug', Debug)
  .component('loading', Loading)
  .component('notification', Notification)
  .mount('body')
