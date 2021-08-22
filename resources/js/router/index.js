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
        component: Home,
        meta: {
          guest:true,
          auth:true
        }
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta: {
          guest:true,
          auth:false
        }
    },
    {
        path: "/logout",
        name: "Logout",
        component: Logout,
        meta: {
          guest:false,
          auth:true
        }
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
        meta: {
          guest:true,
          auth:false
        }
    },
    {
        path: "/reset-password",
        name: "ResetPassword",
        component: ResetPassword,
        meta: {
          guest:true,
          auth:false
        }
    },
    {
        path: "/class/:id(\\d+)",
        name: "Class",
        component: Class,
        props:(route) => {
          const id = Number.parseInt(route.params.id, 10)
          return { id }
        },
        meta: {
          guest:false,
          auth:true
        }
    },
    {
        path: "/class/new",
        name: "NewClass",
        component: Class,
        meta: {
          guest:false,
          auth:true
        }
    },
    {
        path: "/:catchAll(.*)",
        component: NotFound,
        meta: {
          guest:true,
          auth:true
        }
    },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});



router.beforeEach((to, from, next) => {
    const bouncer = store.getters['auth/bouncer']

    if (_.has(to.meta, 'permissions') || _.has(to.meta, 'roles')) {
        if (typeof to.meta.permissions === 'function') {
            if (!to.meta.permissions(bouncer, to, from)) {
              if(isGuest()) {
                next({
                  name: "Login",
                  query: {redirect: to.fullPath}
                });
              } else {
                // TODO
                // Push notification to inform user they do not have permission
                console.error('TODO : remplacer par une notification. Accès refusé.')
                // redirect to a universal page they will have access to
                next({ name: 'home', replace: true })
              }
            }
        } else if ((_.has(to.meta, 'permissions') && bouncer.cannot(to.meta.permissions)) || (_.has(to.meta, 'roles') && bouncer.isNotA(to.meta.roles))) {
            // Push notification to inform user they do not have permission

            // redirect to a universal page they will have access to
            next({ name: 'home', replace: true })

            return
        }
    }

    // they either have permissions or no permissions are required so continue on
    // to the intended route
    next()
})

export default router;
