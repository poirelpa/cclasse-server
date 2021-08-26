import { createWebHistory, createRouter } from 'vue-router'
import store from './store.js'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('./components/Home.vue')
  },
  {
    name: '404',
    path: '/:catchAll(.*)',
    component: () => import('./components/NotFound.vue')
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

  next()
})

export default router
