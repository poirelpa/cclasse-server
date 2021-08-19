import { createStore } from 'vuex'

// Create a new store instance.
let indexById = function(a) {a.reduce((map,obj)=>{map[obj.id]=obj;return map},{})}

export default createStore({
  state () {
    return {
      classes:{},
      levels:{}
    }
  },
  actions: {
    getClasses(context){
      return axios
        .get('/api/v1/classes')
        .then(response => (context.commit('setClasses',response.data.data)))
    },
    createClass(context,cl){
      return axios
      .post('/api/v1/classes',cl)
      .then(response => (context.commit('addClass',response.data.data)))
    },
    getLevels(context) {
      if(context.state.levels.length) return
      return axios
        .get('/api/v1/levels')
        .then(response => (context.commit('setLevels',response.data.data)))
    }
  },
  mutations: {
    setClasses(state, classes){
      state.classes = classes.reduce((map,obj)=>{map[obj.id]=cl;return map},{})
    },
    addClass(state, cl){
      state.classes[cl.id] = cl;
    },
    setLevels(state, levels){
      console.log(levels)
      state.levels = indexById(levels)
      console.log(state.levels)
    }
  }
})
