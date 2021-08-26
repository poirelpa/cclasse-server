import router from '../router.js'
import store from '../store.js'
import routes from './routes.js'
import authStore from './store.js'

export default {
  init () {
    routes.forEach((route, i) => {
      router.addRoute(route)
    })

    store.registerModule('auth', authStore)
  }
}
