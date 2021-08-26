import store from '../store'
import Programmation from './Programmation.vue'

export default [
  {
    path: '/class/:classId(\\d)/programmation/new',
    name: 'NewProgrammation',
    component: Programmation,
    props: (route) => {
      const id = Number.parseInt(route.params.id)
      const classId = Number.parseInt(route.params.classId)
      return { id, classId }
    },
    meta: {
      permissions (bouncer, to, from) {
        const id = Number.parseInt(to.params.classId)
        const cl = store.getters['classes/classesById'][id]
        return bouncer.can('update', 'class', cl)
      }
    }
  },
  {
    path: '/class/:classId(\\d)/programmation/:id(\\d)',
    name: 'Programmation',
    component: Programmation,
    props: (route) => {
      const classId = Number.parseInt(route.params.classId)
      return { classId }
    },
    meta: {
      permissions (bouncer, to, from) {
        const id = Number.parseInt(to.params.id)
        const cl = store.getters['classes/classesById'][id]
        return bouncer.can('update', 'class', cl)
      }
    }
  }
]
