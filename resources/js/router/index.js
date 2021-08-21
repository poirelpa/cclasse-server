import { createWebHistory, createRouter } from "vue-router";
import Class from "../components/Class.vue";
import Home from "../components/Home.vue";
import Login from "../components/Login.vue";
import Register from "../components/Login.vue";
import RecoverPassword from "../components/Login.vue";
import NotFound from "../components/NotFound.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
    },
    {
        path: "/recover-password",
        name: "RecoverPassword",
        component: RecoverPassword,
    },
    {
        path: "/class/:id(\\d+)",
        name: "Class",
        component: Class,
        props:(route) => {
          const id = Number.parseInt(route.params.id, 10)
          return { id }
        }
    },
    {
        path: "/class/new",
        name: "NewClass",
        component: Class,
    },
    {
        path: "/:catchAll(.*)",
        component: NotFound,
    },
  /*{
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" * / '../components/About.vue')
  }*/
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
