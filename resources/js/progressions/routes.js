import Progressions from './Progressions.vue'
import store from '../store'

export default [
  {
    path: '/class/:classId(\\d+)/progressions',
    name: 'Progressions',
    component: Progressions,
    props: (route) => {
      const classId = Number.parseInt(route.params.classId)
      return { classId }
    },
    meta: {
      permissions (bouncer, to, from) {
        const classId = Number.parseInt(to.params.classId)
        const cl = store.getters['classes/find'](classId)
        return bouncer.can('update', 'class', cl)
      }
    }
  }
]
