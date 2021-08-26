import { createApp } from 'vue'
import router from './router.js'
import store from './store.js'
import App from './App.vue'
import Debug from './utils/Debug.vue'
import Loading from './utils/Loading.vue'
import Notification from './utils/Notification.vue'
import mixin from './utils/mixin.js'
import auth from './auth'
import classes from './classes'
import prog from './programmations'

auth.init()
classes.init()
prog.init()

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
