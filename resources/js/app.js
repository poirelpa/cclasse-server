import { createApp } from 'vue'
import App from './components/App.vue'
import Debug from './components/utilities/Debug.vue'
import Loading from './components/utilities/Loading.vue'
import Notification from './components/utilities/Notification.vue'
import router from './router'
import store from './store'
import mixin from './utils/mixin.js'
import('./classes')

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
