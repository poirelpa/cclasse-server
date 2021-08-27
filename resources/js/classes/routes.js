import Class from './Class.vue'
import store from '../store'

export default [
  {
    path: '/class/:id(\\d+)',
    name: 'Class',
    component: Class,
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
  },
  {
    path: '/class/new',
    name: 'NewClass',
    component: Class,
    props: (route) => {
      const classId = Number.parseInt(route.params.classId)
      return { classId }
    },
    meta: {
      permissions (bouncer, to, from) {
        return bouncer.can('create', 'class')
      }
    }
  }
]
