import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import SessionsView from '../views/SessionsView.vue'
import SessionDetailView from '../views/SessionDetailView.vue'
import TicketCheckView from '../views/TicketCheckView.vue'
import AdminView from '../views/admin/AdminView.vue'
import AdminSessionsView from '../views/admin/AdminSessionsView.vue'
import AdminReportsView from '../views/admin/AdminReportsView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView
    },
    {
      path: '/sessions',
      name: 'sessions',
      component: SessionsView
    },
    {
      path: '/sessions/:id',
      name: 'session-detail',
      component: SessionDetailView
    },
    {
      path: '/check-tickets',
      name: 'check-tickets',
      component: TicketCheckView
    },
    {
      path: '/admin',
      name: 'admin',
      component: AdminView,
      meta: { requiresAuth: true },
      children: [
        {
          path: 'sessions',
          name: 'admin-sessions',
          component: AdminSessionsView
        },
        {
          path: 'reports',
          name: 'admin-reports',
          component: AdminReportsView
        }
      ]
    }
  ]
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login' })
  } else {
    next()
  }
})

export default router