import { createWebHistory, createRouter } from "vue-router";
import Class from "../components/Class.vue";
import Home from "../components/Home.vue";
import Login from "../components/Login.vue";
import Logout from "../components/Logout.vue";
import Register from "../components/Login.vue";
import RecoverPassword from "../components/Login.vue";
import NotFound from "../components/NotFound.vue";

import store from '../store'

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
        path: "/recover-password",
        name: "RecoverPassword",
        component: RecoverPassword,
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
  // Determine if the route requires authentication
  let isConnected = store.state.user.isConnected;
  if (to.matched.some(record => (!record.meta.guest && !isConnected)||((!record.meta.auth && isConnected)))) {
    next({
      name: "Login",
      query: {redirect: to.fullPath}
    });
  } else {
    next();
  }
});

export default router;
