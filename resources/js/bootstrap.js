import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Attach CSRF token if available
try {
  const tokenMeta = document.head.querySelector('meta[name="csrf-token"]');
  const token = tokenMeta ? tokenMeta.content : (window.csrfToken || null);
  if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
  }
} catch (_) {}
