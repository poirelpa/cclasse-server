import router from '../router'
import store from '../store'
import restStore from '../utils/restStore.js'
import routes from './routes.js'

routes.forEach((route, i) => {
  router.addRoute(route)
})

store.registerModule('classes', restStore.createStore('classes'))
store.registerModule('levels', restStore.createStore('levels'))
