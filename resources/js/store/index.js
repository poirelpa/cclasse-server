import { createStore } from 'vuex'

// Create a new store instance.
let indexById = function(arr) {
  return {
    data:arr.reduce((map,obj)=>{map[obj.id]=obj;return map},{}),
    order:arr.map(obj=>obj.id),
    isLoaded:true
  }
}
let ordered = function(obj){
  return obj.order.map(order=>obj.data[order])
}

export default createStore({
  state () {
    return {
      classes:{data:{},order:[],isLoaded:false},
      levels:{data:{},order:[],isLoaded:false},
      user:{isConnected:false}
    }
  },
  getters: {
    classes(state) { return ordered(state.classes) },
    classesById(state) { return state.classes.data },
    levels(state) { return ordered(state.levels) },
    levelsById(state) { return state.levels.data }
  },
  actions: {
    getClasses(context){
      if(context.state.classes.isLoaded) return
      return axios
        .get('/api/v1/classes')
        .then(response => (context.commit('setClasses',response.data.data)))
    },
    createClass(context, cl){
      return axios
      .post('/api/v1/classes', cl)
      .then(response => {
        context.commit('addClass',response.data.data)
        return response.data.data
      })
    },
    updateClass(context, cl){
      console.log('up',cl)
      return axios
      .put(cl._links.self, cl)
      .then(response => {
        context.commit('updateClass',response.data.data)
        return response.data.data
      })
    },
    deleteClass(context, cl){
      return axios
      .delete(cl._links.self)
      .then(response => {
        context.commit('deleteClass',cl.id)
      })
    },
    getLevels(context) {
      if(context.state.levels.isLoaded) return
      return axios
        .get('/api/v1/levels')
        .then(response => context.commit('setLevels',response.data.data))
    },
    login(context, credentials) {
      return axios.post('http://cclasse.test/oauth/token',{
        grant_type: "password",
        client_id: "2",
        client_secret: "EEeXAjmAuUA5r93AJI2THCUdDil4WZkUwCll28uE",
        username: credentials.email,
        password: credentials.password,
        scope: "*"
      }).then(response => {
        if(credentials.remember){
          localStorage.setItem('access_token',response.data.access_token)
          localStorage.setItem('expires_in',response.data.expires_in)
          localStorage.setItem('refresh_token',response.data.refresh_token)
        }
        window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.access_token;
        console.log(response)
        context.commit('setTokens',response.data)
      })
    }
  },
  mutations: {
    setClasses(state, classes){
      state.classes = indexById(classes)
    },
    addClass(state, cl){
      state.classes.data[cl.id] = cl
      state.classes.order.unshift(cl.id)
      return cl
    },
    updateClass(state, cl){
      state.classes.data[cl.id] = cl
      return state.classes.data[cl.id]
    },
    deleteClass(state, id){
      delete state.classes.data[id]
      state.classes.order.splice(state.classes.order.indexOf(id),1)
    },
    setLevels(state, levels){
      state.levels = indexById(levels)
    },
    setTokens(state, tokens){
      state.user.token = tokens
      state.user.isConnected = true
    }
  }
})
