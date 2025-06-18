import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '@/pages/index.vue'

const routes = [
  {
    path: '/index',
    name: 'Dashboard',
    component: Dashboard,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
