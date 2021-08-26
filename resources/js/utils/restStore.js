import api from '../utils/api.js'

function createRestApi (api, serviceName) {
  return {
    load: () => api.get('/' + serviceName).then(response => response.data.data),
    create: (item) => api.post('/' + serviceName, item).then(response => response.data.data),
    read: (item) => api.get(item._links.self).then(response => response.data.data),
    update: (item) => api.put(item._links.self, item).then(response => response.data.data),
    delete: (item) => api.put(item._links.self, item)
  }
}
export default {
  createStore (params) {
    let options = params
    if (typeof (params) === 'string') {
      options = {
        name: params
      }
    }
    if (options.api === undefined) {
      options.api = createRestApi(api, options.name)
    }

    return {
      namespaced: true,
      state: {
        allIds: [],
        byId: {},
        isLoaded: false
      },
      getters: {
        find: state => state.byId,
        list: (state, getters) => state.allIds.map(id => getters.find[id]),
        isLoaded: state => state.isLoaded
      },
      actions: {
        async load ({ state, commit }) {
          if (state.isLoaded) return

          const items = await options.api.load()
          items.forEach((item) => {
            commit('add', item)
          })
          commit('setLoaded', true)
        },
        async create ({ commit }, item) {
          const savedItem = await options.api.create(item)
          commit('add', savedItem)
          return savedItem
        },
        async read ({ commit }, item) {
          const savedItem = await options.api.read(item)
          commit('add', savedItem)
          return savedItem
        },
        async update ({ commit }, item) {
          const savedItem = await options.api.update(item)
          commit('add', savedItem)
          return savedItem
        },
        async delete ({ commit }, item) {
          commit('remove', item)
          await options.api.delete(item)
        },
        clear ({ commit }) {
          commit('clear')
        }
      },
      mutations: {
        add (state, item) {
          state.byId[item.id] = item
          if (state.allIds.includes(item.id)) return
          state.allIds.push(item.id)
        },
        setLoaded (state, value) {
          state.isLoaded = value
        },
        remove (state, item) {
          const index = state.allIds.indexOf(item.id)
          if (index > -1) {
            state.allIds.splice(index, 1)
          }
          delete state.byId[item.id]
        },
        clear (state) {
          state.allIds = []
          state.byId = {}
          state.isLoaded = false
        }
      }
    }
  }
}
