
import utils from '../utils/utils.js'
import api from '../utils/api.js'

export default {
  namespaced: true,

  state: {
    programs: { data: {}, order: [], isLoaded: false }
  },

  getters: {
    programs (state) { return utils.ordered(state.programs) },
    programssById (state) { return state.programs.data }
  },

  actions: {
    getPrograms (context) {
      if (context.state.programs.isLoaded) return
      return api
        .get('/programs')
        .then(response => (context.commit('setprograms', response.data.data)))
    }
  },
  mutations: {
    setprograms (state, programs) {
      state.programs = utils.indexById(programs)
    }
  }
}
