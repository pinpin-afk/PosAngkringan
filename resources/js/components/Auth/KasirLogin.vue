<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-900 via-teal-900 to-cyan-900 flex items-center justify-center p-4">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 via-teal-600/20 to-cyan-600/20"></div>
    
    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-md">
      <!-- Card Container -->
      <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20 p-8">
        <!-- Logo and Header -->
        <div class="text-center mb-8">
          <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
              <img 
                src="/assets/img/WhatsApp Image 2025-09-07 at 17.12.58.jpeg" 
                alt="Logo Angkringan" 
                class="w-12 h-12 rounded-xl object-cover"
              >
            </div>
          </div>
          <h1 class="text-3xl font-bold text-white mb-2">Kasir Login</h1>
          <p class="text-white/80 text-sm">Masuk sebagai kasir sistem</p>
        </div>

        <!-- Error Messages -->
        <div v-if="errors.length > 0" class="mb-6 bg-red-500/20 border border-red-500/30 text-red-100 px-4 py-3 rounded-xl backdrop-blur-sm">
          <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <ul>
              <li v-for="error in errors" :key="error" class="text-sm">{{ error }}</li>
            </ul>
          </div>
        </div>

        <!-- Success Messages -->
        <div v-if="successMessage" class="mb-6 bg-green-500/20 border border-green-500/30 text-green-100 px-4 py-3 rounded-xl backdrop-blur-sm">
          <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-sm">{{ successMessage }}</span>
          </div>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="space-y-6">
          <!-- Email Input -->
          <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-white/90">Email Address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                </svg>
              </div>
              <input 
                id="email" 
                v-model="form.email"
                type="email" 
                required 
                class="block w-full pl-10 pr-3 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm transition-all duration-200" 
                placeholder="kasir@angkringan.com"
              >
            </div>
          </div>

          <!-- Password Input -->
          <div class="space-y-2">
            <label for="password" class="block text-sm font-medium text-white/90">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
              </div>
              <input 
                id="password" 
                v-model="form.password"
                type="password" 
                required 
                class="block w-full pl-10 pr-3 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/30 backdrop-blur-sm transition-all duration-200" 
                placeholder="Masukkan password"
              >
            </div>
          </div>

          <!-- Login Button -->
          <button 
            type="submit" 
            :disabled="loading"
            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg hover:shadow-xl"
          >
            <span v-if="loading" class="mr-2">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ loading ? 'Memproses...' : 'Login Kasir' }}
          </button>
        </form>

        <!-- Switch to Admin Login -->
        <div class="mt-8 text-center">
          <p class="text-white/70 text-sm mb-2">Bukan kasir?</p>
          <a 
            href="/admin/login" 
            class="inline-flex items-center text-white/90 hover:text-white transition-colors duration-200 text-sm font-medium"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
            Login sebagai Admin
          </a>
        </div>
      </div>
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
