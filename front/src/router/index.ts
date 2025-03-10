import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import SessionsView from '../views/SessionsView.vue'
import SessionDetailView from '../views/SessionDetailView.vue'
import TicketCheckView from '../views/TicketCheckView.vue'
import AdminView from '../views/admin/AdminView.vue'
import AdminSessionsView from '../views/admin/AdminSessionsView.vue'
import AdminReportsView from '../views/admin/AdminReportsView.vue'


const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
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

export default router;