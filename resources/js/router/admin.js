import { createRouter, createWebHistory } from 'vue-router';
import AdminDashboard from '../components/Admin/AdminDashboard.vue';
import DashboardHome from '../components/Admin/DashboardHome.vue';
import ProductManagement from '../components/Admin/ProductManagement.vue';
import CategoryManagement from '../components/Admin/CategoryManagement.vue';
import OrderManagement from '../components/Admin/OrderManagement.vue';

const routes = [
  {
    path: '/admin',
    component: AdminDashboard,
    children: [
      {
        path: 'dashboard',
        name: 'admin.dashboard',
        component: DashboardHome
      },
      {
        path: 'products',
        name: 'admin.products',
        component: ProductManagement
      },
      {
        path: 'categories',
        name: 'admin.categories',
        component: CategoryManagement
      },
      {
        path: 'orders',
        name: 'admin.orders',
        component: OrderManagement
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
