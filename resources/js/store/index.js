import { createStore } from 'vuex'

// Create a new store instance.
export default createStore({
  state () {
    return {
      classes:[]
    }
  },
  actions: {
    fetchClasses(state){
      return axios
        .get('/api/v1/classes')
        .then(response => (this.commit('setClasses',response.data.data)))
    }
  },
  mutations: {
    setClasses(state, classes){
      state.classes = classes
    }
  }
})
