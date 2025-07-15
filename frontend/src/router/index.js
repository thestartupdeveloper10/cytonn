import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import { useAuthStore } from '../store/auth'
import AdminLayout from '../views/admin/AdminLayout.vue'
import AdminDashboard from '../views/admin/AdminDashboard.vue'



// Placeholder components for Users and Tasks
const AdminUsers = () => import('../views/admin/Users.vue')
const AdminTasks = () => import('../views/admin/Tasks.vue')
import UserDashboard from '../views/UserDashboard.vue'
const UserTasks = () => import('../views/UserTasks.vue')

const routes = [
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: UserDashboard,
    meta: { requiresAuth: true },
  },
  {
    path: '/tasks',
    name: 'UserTasks',
    component: UserTasks,
    meta: { requiresAuth: true },
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      { path: 'dashboard', name: 'AdminDashboard', component: AdminDashboard },
      { path: 'users', name: 'AdminUsers', component: AdminUsers },
      { path: 'tasks', name: 'AdminTasks', component: AdminTasks },
      { path: '', redirect: { name: 'AdminDashboard' } },
    ],
  },
  { path: '/', redirect: '/dashboard' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const auth = useAuthStore()
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    next('/login')
  } else if ((to.path === '/login' || to.path === '/register') && auth.isAuthenticated) {
    if (auth.user?.role === 'admin') {
      next('/admin/dashboard')
    } else {
      next('/dashboard')
    }
  } else if (to.matched.some(r => r.meta.requiresAdmin)) {
    if (auth.user?.role !== 'admin') {
      next('/dashboard')
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router 