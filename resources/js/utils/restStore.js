// based on https://markus.oberlehner.net/blog/generic-content-vuex-modules/

import api from '../utils/api.js'

function createRestApi (api, serviceName) {
  const getLink = item => {
    if (!item?._links?.self) {
      throw new Error(`no link for item ${item} in ${serviceName}`)
    }
    return item._links.self
  }
  return {
    load: (url) => api.get(url ?? ('/' + serviceName)).then(response => response.data.data),
    create: (item, url) => api.post(url ?? ('/' + serviceName), item).then(response => response.data.data),
    read: (item) => api.get(getLink(item)).then(response => response.data.data),
    update: (item) => api.put(getLink(item), item).then(response => response.data.data),
    delete: (item) => api.delete(getLink(item), item)
  }
}

// return the list of commits needed to store the item and its relationships
function flatten (item, relationships) {
  relationships ??= {}

  return [
    [
      // the item with relationships replaced by ids
      'add',
      {
        /// merge the item fields
        ...item,
        /// with relationships replaced by ids
        ...Object.keys(relationships).reduce((previousFields, field) => ({
          ...previousFields,
          [field]: Array.isArray(item[field])
            ? item[field]?.map(x => x.id)
            : item[field]?.id
        }), {})
      },
      null
    ],
    ...Object.keys(relationships).map(field => [
      `${relationships[field]}/add`,
      item[field],
      { root: true }
    ], [])
  ]
}

function inflate (item, relationships, rootGetters) {
  if (typeof (item) !== 'object') {
    return item
  }
  return {
    ...item,
    ...Object.keys(relationships).reduce((previousFields, field) => ({
      ...previousFields,
      [field]: Array.isArray(item[field])
        ? item[field].map(id => rootGetters[`${relationships[field]}/find`](id))
        : rootGetters[`${relationships[field]}/find`](item[field])
    }), {})
  }
}

function performCommits (commits, commit) {
  commits.forEach(c => {
    if (Array.isArray(c[1])) {
      c[1].forEach(i => {
        if (i) {
          commit(c[0], i, c[2])
        }
      })
    } else if (c[1]) {
      commit(c[0], c[1], c[2])
    }
  })
}

export default {
  createStore (params) {
    let options = params
    if (typeof (params) === 'string') {
      options = {
        resourceName: params,
        relationships: {}
      }
    }

    options.api ??= createRestApi(api, options.resourceName)
    options.relationships ??= {}

    return {
      namespaced: true,
      state: {
        allIds: [],
        byId: {},
        byParentId: {},
        loadedItems: {},
        isLoaded: false,
        isLoadedFor: []
      },
      getters: {
        find: (state, _, __, rootGetters) => id => inflate(state.byId[id], options.relationships, rootGetters),
        list: (state, getters) => state.allIds.map(id => getters.find(id)),
        listFor: (state, getters) => parentId => state.byParentId[parentId].map(id => getters.find(id)),
        isLoaded: state => state.isLoaded,
        isLoadedFor: state => parentId => state.isLoadedFor[parentId],
        isItemLoaded: state => state.loadedItems
      },
      actions: {
        async load ({ state, commit, rootGetters }, param) {
          if (state.isLoaded) return

          let items
          let parent
          if (options.nested) {
            if (!param) {
              throw Error('Missing parameter for nested resource loading')
            }
            parent = param
            if (typeof parent === 'number') {
              await this.dispatch(`${options.nested}/load`, null, { root: true })
              parent = rootGetters[`${options.nested}/find`](parent)
            }
            if (typeof parent === 'object') {
              items = await options.api.load(parent._links[options.resourceName])

              items.forEach((item) => {
                item._parentId = parent.id
              })
            } else {
              throw Error('Wrong parametertype for nested resource loading : expected object or id, got ' + typeof parent)
            }
          } else {
            items = await options.api.load()
          }

          items.forEach((item) => {
            performCommits(flatten(item, options.relationships), commit)
          })
          if (options.nested) {
            commit('setLoadedFor', parent.id)
          } else {
            commit('setLoaded', true)
          }
        },
        async create ({ commit, rootGetters }, param) {
          let item = param
          let savedItem
          if (options.nested) {
            if (!param.parent) {
              throw Error('Missing parent key in parameter object for nested resource creating')
            }
            let parent = param.parent
            item = param.item
            if (typeof parent === 'number') {
              await this.dispatch(`${options.nested}/load`, null, { root: true })
              parent = rootGetters[`${options.nested}/find`](parent)
            }
            if (typeof parent === 'object') {
              savedItem = await options.api.create(item, parent._links[options.resourceName])
            } else {
              throw Error('Wrong parametertype for nested resource loading : expected object or id, got ' + typeof parent)
            }
          } else {
            savedItem = await options.api.create(item)
          }
          performCommits(flatten(savedItem, options.relationships), commit)
          commit('setItemLoaded', savedItem)
          return savedItem
        },
        async read ({ getters, commit }, item) {
          // if (getters.isLoaded[item.id]) return getters.find(item.id)

          const savedItem = await options.api.read(item)
          if (savedItem) {
            performCommits(flatten(savedItem, options.relationships), commit)
            commit('setItemLoaded', savedItem)
          }
          return savedItem
        },
        async update ({ commit }, item) {
          const savedItem = await options.api.update(item)
          if (savedItem) {
            performCommits(flatten(savedItem, options.relationships), commit)
            commit('setItemLoaded', savedItem)
          }
          return savedItem
        },
        async delete ({ commit }, item) {
          await options.api.delete(item)
          commit('remove', item)
        },
        clear ({ commit }) {
          commit('clear')
        }
      },
      mutations: {
        add (state, item) {
          state.byId[item.id] = item
          if (options.nested) {
            state.byParentId[item._parentId] ??= []
            if (!state.byParentId[item._parentId].includes(item.id)) {
              state.byParentId[item._parentId].push(item.id)
            }
          }
          if (state.allIds.includes(item.id)) return
          state.allIds.push(item.id)
        },
        setLoaded (state, value) {
          state.isLoaded = value
        },
        setItemLoaded (state, item) {
          state.loadedItems[item.id] = true
        },
        setLoadedFor (state, value) {
          state.isLoadedFor.push(value)
        },
        remove (state, item) {
          const index = state.allIds.indexOf(item.id)
          if (index > -1) {
            state.allIds.splice(index, 1)
          }
          state.loadedItems[item.id] = false
          state.byId[item.id] = false
        },
        clear (state) {
          state.allIds = []
          state.byId = {}
          state.byParentId = {}
          state.loadedItems = {}
          state.isLoaded = false
        }
      }
    }
  }
}
