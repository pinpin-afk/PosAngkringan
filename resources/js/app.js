import './bootstrap';
import '../css/app.css';
import { createApp } from 'vue';
import PosApp from './components/PosApp.vue';
import PosAppMobile from './components/PosAppMobile.vue';
import PosAppTablet from './components/PosAppTablet.vue';
import PosAppISeller from './components/PosAppISeller.vue';
import PaymentView from './components/PaymentView.vue';
import PosMemberManagement from './components/PosMemberManagement.vue';
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
import MemberManagement from './components/Admin/MemberManagement.vue';
import RoleManagement from './components/Admin/RoleManagement.vue';
import PosOrderReport from './components/PosOrderReport.vue';
import { setupNetworkWatcher } from './offline/sync';

setupNetworkWatcher();

// try {
//   import('@capacitor/app').then(({ App }) => {
//     App.addListener('backButton', ({ canGoBack }) => {
//       const atRoot = window.location.pathname === '/' || window.location.pathname === '/kasir/pos';
//       if (!atRoot && (canGoBack || window.history.length > 1)) {
//         window.history.back();
//       } else {
//       }
//     });
//   }).catch(() => {
//     window.addEventListener('popstate', () => {});
//   });
// } catch (_) {}

if (document.getElementById('admin-login-app')) {
    createApp(AdminLogin).mount('#admin-login-app');
} else if (document.getElementById('kasir-login-app')) {
    createApp(KasirLogin).mount('#kasir-login-app');
} else if (document.getElementById('owner-login-app')) {
    createApp(OwnerLogin).mount('#owner-login-app');
} else if (document.getElementById('app')) {
    if (window.location.pathname.includes('/admin/owner')) {
        createApp(OwnerDashboard).mount('#app');
    } else if (window.location.pathname.includes('/admin')) {
        const app = createApp(AdminDashboard);
        
        app.component('DashboardHome', DashboardHome);
        app.component('ProductManagement', ProductManagement);
        app.component('CategoryManagement', CategoryManagement);
        app.component('OrderManagement', OrderManagement);
        app.component('UserManagement', UserManagement);
        app.component('MemberManagement', MemberManagement);
        app.component('RoleManagement', RoleManagement);
        
        app.mount('#app');
    } else if (window.location.pathname.includes('/kasir/report')) {
        createApp(PosOrderReport).mount('#app');
    } else if (window.location.pathname.includes('/kasir/payment')) {
        createApp(PaymentView).mount('#app');
    } else {
        const screenWidth = window.innerWidth;
        let component;
        
        if (screenWidth <= 768) {
            // Mobile
            component = PosAppMobile;
        } else if (screenWidth <= 1024) {
            // Tablet
            component = PosAppTablet;
        } else {
            // Desktop
            component = PosAppISeller;
        }
        
        createApp(component).mount('#app');
        
        window.addEventListener('resize', () => {
            const newScreenWidth = window.innerWidth;
            let newComponent;
            
            if (newScreenWidth <= 768) {
                newComponent = PosAppMobile;
            } else if (newScreenWidth <= 1024) {
                newComponent = PosAppTablet;
            } else {
                newComponent = PosAppISeller;
            }
            
            if (newComponent !== component) {
                const appElement = document.getElementById('app');
                if (appElement) {
                    appElement.innerHTML = '';
                    createApp(newComponent).mount('#app');
                }
            }
        });
    }
}

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
  });
}
