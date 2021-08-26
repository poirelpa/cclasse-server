import { createWebHistory, createRouter } from 'vue-router'
import store from '../store'

import Programmation from '../components/classes/Programmation.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../components/Home.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../components/auth/Login.vue')
  },
  {
    path: '/logout',
    name: 'Logout',
    component: () => import('../components/auth/Logout.vue'),
    meta: {
      roles: ['admin', 'user']
    }
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../components/auth/Register.vue')
  },
  {
    path: '/reset-password',
    name: 'ResetPassword',
    component: () => import('../components/auth/ResetPassword.vue')
  },
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
    meta: {
      permissions (bouncer, to, from) {
        const id = Number.parseInt(to.params.id)
        const cl = store.getters['classes/classesById'][id]
        return bouncer.can('update', 'class', cl)
      }
    }
  },
  {
    name: '404',
    path: '/:catchAll(.*)',
    component: () => import('../components/NotFound.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const bouncer = store.getters['auth/bouncer']
  const redirect = function (next, to) {
    if (bouncer.isGuest) {
      next({
        name: 'Login',
        query: { redirect: to.fullPath }
      })
    } else {
      store.dispatch('notify', 'Accès refusé.')
      console.error('Accès refusé.', to)
      // redirect to a universal page they will have access to
      next({ name: 'Home', replace: true })
    }
  }
  if (bouncer.isGuest && to.name === '404') {
    redirect(next, to)
    return
  }
  if (_.has(to.meta, 'permissions') || _.has(to.meta, 'roles')) {
    if (typeof to.meta.permissions === 'function') {
      if (!to.meta.permissions(bouncer, to, from)) {
        redirect(next, to)
        return
      }
    } else if ((_.has(to.meta, 'permissions') && bouncer.cannot(to.meta.permissions)) || (_.has(to.meta, 'roles') && bouncer.isNotA(to.meta.roles))) {
      redirect(next, to)

      return
    }
  }

  // they either have permissions or no permissions are required so continue on
  // to the intended route
  next()
})

export default router
