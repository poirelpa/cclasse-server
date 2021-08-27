import store from '../store'
import Programming from './Programming.vue'

export default [
  {
    path: '/class/:classId(\\d)/programming/new',
    name: 'NewProgramming',
    component: Programming,
    props: (route) => {
      const classId = Number.parseInt(route.params.classId)
      return { classId }
    },
    meta: {
      permissions (bouncer, to, from) {
        const id = Number.parseInt(to.params.classId)
        const cl = store.getters['classes/find'](id)
        return bouncer.can('update', 'class', cl)
      }
    }
  },
  {
    path: '/class/:classId(\\d)/programming/:id(\\d)',
    name: 'Programming',
    component: Programming,
    props: (route) => {
      const id = Number.parseInt(route.params.id)
      const classId = Number.parseInt(route.params.classId)
      return { id, classId }
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
