import { createStore } from 'vuex'

// Create a new store instance.
export default createStore({
  state () {
    return {
      classes:{}
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
    }
  },
  mutations: {
    setClasses(state, classes){
      state.classes = classes.reduce((map,cl)=>{map[cl.id]=cl;return map},{})
    },
    addClass(state, cl){
      console.log(cl)
      state.classes[cl.id] = cl;
    }
  }
})
