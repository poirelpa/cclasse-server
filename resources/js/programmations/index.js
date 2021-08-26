import router from '../router.js'
import store from '../store.js'
import restStore from '../utils/restStore.js'
import routes from './routes.js'

export default {
  init () {
    routes.forEach((route, i) => {
      router.addRoute(route)
    })

    store.registerModule('programmations', restStore.createStore('programmations'))
  }
}
