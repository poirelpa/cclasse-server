
import utils from '../utils/utils.js'

export default {
  namespaced: true,

  state: {
    classes:{data:{},order:[],isLoaded:false},
    levels:{data:{},order:[],isLoaded:false},
  },

  getters: {
    classes(state) { return utils.ordered(state.classes) },
    classesById(state) { return state.classes.data },
    levels(state) { return utils.ordered(state.levels) },
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
      clearClasses(context, cl){
        context.commit('setClasses',null)
      },
      getLevels(context) {
        if(context.state.levels.isLoaded) return
        return axios
          .get('/api/v1/levels')
          .then(response => context.commit('setLevels',response.data.data))
      },
    },
    mutations: {
      setClasses(state, classes){
        state.classes = utils.indexById(classes)
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
        state.levels = utils.indexById(levels)
      }
    }
}
