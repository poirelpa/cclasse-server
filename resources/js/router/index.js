import { createWebHistory, createRouter } from "vue-router";
import store from '../store'

import Home from "../components/Home.vue";

import Login from "../components/auth/Login.vue";
import Logout from "../components/auth/Logout.vue";
import Register from "../components/auth/Register.vue";
import ResetPassword from "../components/auth/ResetPassword.vue";

import Class from "../components/classes/Class.vue";

import NotFound from "../components/NotFound.vue";


const routes = [
    {
        path: "/",
        name: "Home",
        component: Home
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
    },
    {
        path: "/logout",
        name: "Logout",
        component: Logout,
        meta: {
          roles: ['admin', 'user']
        }
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
    },
    {
        path: "/reset-password",
        name: "ResetPassword",
        component: ResetPassword,
    },
    {
        path: "/class/:id(\\d+)",
        name: "Class",
        component: Class,
        props:(route) => {
          const id = Number.parseInt(route.params.id)
          return { id }
        },
        meta: {
          permissions (bouncer, to, from) {
            const id = Number.parseInt(to.params.id)
            const cl = store.getters['classes/classesById'][id]
            return bouncer.can('update', 'class',cl)
          }
        }
    },
    {
        path: "/class/new",
        name: "NewClass",
        component: Class,
        meta: {
          permissions (bouncer, to, from) {
            return bouncer.can('create', 'class')
          }
        }
    },
    {
        path: "/:catchAll(.*)",
        component: NotFound,
    },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});



router.beforeEach((to, from, next) => {
    const bouncer = store.getters['auth/bouncer']
    let redirect = function(next,to){

        if(bouncer.isGuest) {
          next({
            name: "Login",
            query: {redirect: to.fullPath}
          });
        } else {
          // TODO
          // Push notification to inform user they do not have permission
          console.error('TODO : remplacer par une notification. Accès refusé.',to,bouncer)
          // redirect to a universal page they will have access to
          next({ name: 'Home', replace: true })
        }
    }
    if (_.has(to.meta, 'permissions') || _.has(to.meta, 'roles')) {
        if (typeof to.meta.permissions === 'function') {
            if (!to.meta.permissions(bouncer, to, from)) {
              redirect(next,to)
              return
            }
        } else if ((_.has(to.meta, 'permissions') && bouncer.cannot(to.meta.permissions)) || (_.has(to.meta, 'roles') && bouncer.isNotA(to.meta.roles))) {
            redirect(next,to)

            return
        }
    }

    // they either have permissions or no permissions are required so continue on
    // to the intended route
    next()
})

export default router;
