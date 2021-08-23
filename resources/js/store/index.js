import { createStore } from 'vuex'
import auth from './auth.js'
import classes from './classes.js'
import utils from '../utils/utils.js'
// Create a new store instance.

export default createStore({
  state:{
    notification:""
  },
  actions:{
    async notify(context, notification){
      context.commit('setNotification', notification)
      await sleep(5000)
      context.commit('setNotification', "")
    }
  },
  mutations:{
    setNotification(state, notification){
      state.notification = notification
    }
  },
  modules: {
    auth:auth,
    classes:classes
  }
})
