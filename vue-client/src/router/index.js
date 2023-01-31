import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue';
import LoginView from '../views/LoginView.vue';
import LogoutView from '../views/LogoutView.vue';
import RegisterView from '../views/RegisterView.vue';
import CarsView from '../views/CarsView.vue';
import PaymentView from '../views/PaymentView.vue';
import ProfileView from '../views/ProfileView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      props: true
      
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      props: true

    },
    {
      path: '/logout',
      name: 'logout',
      component: LogoutView,
      props: true
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
      props: true
    },
    {
      path: '/cars',
      name: 'cars',
      component: CarsView,
      props: true

    },
    {
      path: '/payment',
      name: 'payment',
      component: PaymentView,
      props: true
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/profile',
      name: 'profile',
      component: ProfileView,
      props: true
    }
  ]
})



export default router
