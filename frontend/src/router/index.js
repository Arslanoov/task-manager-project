import Vue from 'vue'
import VueRouter from 'vue-router'
import NotFound from "../components/Error/NotFound";

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('../views/Home.vue')
  },
  {
    path: '/auth/login',
    name: 'auth.login',
    component: () => import('../views/Auth/Login.vue')
  },
  {
    path: '/auth/sign-up',
    name: 'auth.signup',
    component: () => import('../views/Auth/SignUp.vue')
  },
  {
    path: '/todo/main',
    name: 'todo.main',
    component: () => import('../views/Todo/Main/Index.vue')
  },
  {
    path: '/todo/daily',
    name: 'todo.daily',
    component: () => import('../views/Todo/Daily/Index.vue')
  },
  {
    path: '/todo/custom/:id',
    name: 'todo.custom',
    component: () => import('../views/Todo/Custom/Index.vue')
  },
  {
    path: '/404',
    name: '404',
    component: NotFound
  },
  {
    path: '*',
    redirect: '/404'
  }
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

export default router
