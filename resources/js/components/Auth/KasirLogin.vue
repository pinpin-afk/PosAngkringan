<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Login Kasir
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Masuk sebagai kasir
        </p>
      </div>
      
      <!-- Error Messages -->
      <div v-if="errors.length > 0" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul>
          <li v-for="error in errors" :key="error">{{ error }}</li>
        </ul>
      </div>

      <!-- Success Messages -->
      <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ successMessage }}
      </div>

      <!-- Login Form -->
      <form @submit.prevent="handleLogin" class="mt-8 space-y-6">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="email" class="sr-only">Email</label>
            <input 
              id="email" 
              v-model="form.email"
              type="email" 
              required 
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
              placeholder="Email address"
            >
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input 
              id="password" 
              v-model="form.password"
              type="password" 
              required 
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
              placeholder="Password"
            >
          </div>
        </div>

        <div>
          <button 
            type="submit" 
            :disabled="loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading" class="mr-2">
              <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ loading ? 'Memproses...' : 'Login Kasir' }}
          </button>
        </div>
        
        <div class="text-center">
          <a 
            href="/admin/login" 
            class="text-green-600 hover:text-green-500 transition-colors duration-200"
          >
            Login sebagai Admin
          </a>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'KasirLogin',
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      errors: [],
      successMessage: '',
      loading: false
    }
  },
  mounted() {
    // Check for flash messages
    this.checkFlashMessages();
  },
  methods: {
    async handleLogin() {
      this.loading = true;
      this.errors = [];
      
      try {
        const response = await fetch('/kasir/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(this.form)
        });

        if (response.ok) {
          // Redirect to kasir POS
          window.location.href = '/kasir/pos';
        } else {
          const data = await response.json();
          if (data.errors) {
            this.errors = Object.values(data.errors).flat();
          } else {
            this.errors = ['Email atau password salah.'];
          }
        }
      } catch (error) {
        console.error('Login error:', error);
        this.errors = ['Terjadi kesalahan saat login. Silakan coba lagi.'];
      } finally {
        this.loading = false;
      }
    },
    
    checkFlashMessages() {
      // Check for success message from server
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('success')) {
        this.successMessage = 'Login berhasil!';
      }
    }
  }
}
</script>
