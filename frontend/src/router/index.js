import Vue from 'vue'
import VueRouter from 'vue-router'

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
  }
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

export default router
