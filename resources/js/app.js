import './bootstrap';
import { createApp } from 'vue';
import PosApp from './components/PosApp.vue';
import AdminDashboard from './components/Admin/AdminDashboard.vue';
import OwnerDashboard from './components/Owner/OwnerDashboard.vue';
import DashboardHome from './components/Admin/DashboardHome.vue';
import ProductManagement from './components/Admin/ProductManagement.vue';
import CategoryManagement from './components/Admin/CategoryManagement.vue';
import OrderManagement from './components/Admin/OrderManagement.vue';
import AdminLogin from './components/Auth/AdminLogin.vue';
import KasirLogin from './components/Auth/KasirLogin.vue';
import OwnerLogin from './components/Auth/OwnerLogin.vue';
import UserManagement from './components/Admin/UserManagement.vue';
import RoleManagement from './components/Admin/RoleManagement.vue';

// Check if we're on login pages
if (document.getElementById('admin-login-app')) {
    createApp(AdminLogin).mount('#admin-login-app');
} else if (document.getElementById('kasir-login-app')) {
    createApp(KasirLogin).mount('#kasir-login-app');
} else if (document.getElementById('owner-login-app')) {
    createApp(OwnerLogin).mount('#owner-login-app');
} else if (document.getElementById('app')) {
    // Check if we're on admin dashboard or owner
    if (window.location.pathname.includes('/admin/owner')) {
        createApp(OwnerDashboard).mount('#app');
    } else if (window.location.pathname.includes('/admin')) {
        const app = createApp(AdminDashboard);
        
        // Register components globally
        app.component('DashboardHome', DashboardHome);
        app.component('ProductManagement', ProductManagement);
        app.component('CategoryManagement', CategoryManagement);
        app.component('OrderManagement', OrderManagement);
        app.component('UserManagement', UserManagement);
        app.component('RoleManagement', RoleManagement);
        
        app.mount('#app');
    } else {
        createApp(PosApp).mount('#app');
    }
}

// Register PWA service worker (non-blocking)
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
  });
}
