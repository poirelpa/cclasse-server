import Vue from 'vue'
import { mapState, mapGetters } from 'vuex'

import Bouncer from '../utils/bouncer'
import axios from 'axios'

export default {
  namespaced: true,

  state: {
    user: {}
  },

  getters: {
    bouncer: (state) => new Bouncer(state.user),
    can: (state, getters) => getters.bouncer.can.bind(getters.bouncer),
    cannot: (state, getters) => getters.bouncer.cannot.bind(getters.bouncer),
    isGuest: (state) => !state.user?.id,
    isA: (state, getters) => getters.bouncer.isA.bind(getters.bouncer),
    isNotA: (state, getters) => getters.bouncer.isNotA.bind(getters.bouncer)
  },

  actions: {
    async loginWithCredentials(context, credentials) {
      let response = await axios.post('/oauth/token',{
        grant_type: "password",
        client_id: "1",
        client_secret: "Gl4z1uob1D1spvWZ7llFTh5orQUSF5HvzN9LunKO",
        username: credentials.email,
        password: credentials.password,
        scope: "*"
      })

      if(credentials.remember){
        localStorage.setItem('access_token', response.data.access_token)
        localStorage.setItem('expires_in', response.data.expires_in)
        localStorage.setItem('refresh_token', response.data.refresh_token)
      }

      axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.access_token;
          
      context.commit('isLoggedIn')

      let user = await axios.get('/api/v1/auth/current').then(response => response.data)
      context.commit('setUser', user)

      user.isConnected = true
      context.commit('setUser', user)
    },
    async loginWithToken(context){
      let token = localStorage.getItem('access_token')
      if(token) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

        let user = await axios.get('/api/v1/auth/current').then(response => response.data)
        context.commit('setUser', user)
        return user
      }
    },
    logout(context){
      delete axios.defaults.headers.common['Authorization'];
      localStorage.removeItem('access_token')
      localStorage.removeItem('expires_in')
      localStorage.removeItem('refresh_token')

      context.commit('setUser', {isConnected:false})
    },
    resetPassword(context,data){
      return axios.post('/api/v1/auth/resetPassword',data).then(response => response.data)
    },
    register(context,data){
      return axios.post('/api/v1/auth/register',data).then(response => response.data)
    }
  },
  mutations: {
    isLoggedIn(state){
      state.user.isConnected = true
    },
    setUser(state, data){
      state.user = data
    }
  }
}
