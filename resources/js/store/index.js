import { createStore } from 'vuex'
import auth from './auth.js'
import programs from './programs.js'
import utils from '../utils/utils.js'
// Create a new store instance.

export default createStore({
  state: {
    notification: ''
  },
  actions: {
    async notify (context, notification) {
      let message = notification
      if (notification?.response?.data?.errors) {
        // api validation errors
        message = ''
        _.each(notification.response.data.errors, (errors, field) => {
          _.each(errors, error => {
            message += error + '\n'
          })
        })
      }
      context.commit('setNotification', message)
      await utils.sleep(8000)
      context.commit('setNotification', '')
    }
  },
  mutations: {
    setNotification (state, notification) {
      state.notification = notification
    }
  },
  modules: {
    auth: auth,
    programs: programs
  }
})
