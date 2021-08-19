import { createWebHistory, createRouter } from "vue-router";
import Class from "../components/Class.vue";
import Home from "../components/Home.vue";
import NotFound from "../components/NotFound.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/class/:id",
        name: "Class",
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