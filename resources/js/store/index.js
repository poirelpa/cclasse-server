import { createStore } from 'vuex'

// Create a new store instance.
let indexById = function(arr) {
  return {
    data:arr.reduce((map,obj)=>{map[obj.id]=obj;return map},{}),
    order:arr.map(obj=>obj.id)
  }
}
let ordered = function(obj){
  return obj.order.map(order=>obj.data[order])
}

export default createStore({
  state () {
    return {
      classes:{data:{},order:[]},
      levels:{data:{},order:[]},
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
    getLevels(context) {
      if(context.state.levels.length) return
      return axios
        .get('/api/v1/levels')
        .then(response => context.commit('setLevels',response.data.data))
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
    setLevels(state, levels){
      state.levels = indexById(levels)
    }
  }
})
