import Progressions from './Progressions.vue'
import store from '../store'

export default [
  {
    path: '/class/:id(\\d+)/progressions',
    name: 'Progressions',
    component: Progressions,
    props: (route) => {
      const id = Number.parseInt(route.params.id)
      return { id }
    },
    meta: {
      permissions (bouncer, to, from) {
        const id = Number.parseInt(to.params.id)
        const cl = store.getters['classes/find'](id)
        return bouncer.can('update', 'class', cl)
      }
    }
  }
]
