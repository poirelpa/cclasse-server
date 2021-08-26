
export default [

  {
    path: '/login',
    name: 'Login',
    component: () => import('./Login.vue')
  },
  {
    path: '/logout',
    name: 'Logout',
    component: () => import('./Logout.vue'),
    meta: {
      roles: ['admin', 'user']
    }
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('./Register.vue')
  },
  {
    path: '/reset-password',
    name: 'ResetPassword',
    component: () => import('./ResetPassword.vue')
  }
]
