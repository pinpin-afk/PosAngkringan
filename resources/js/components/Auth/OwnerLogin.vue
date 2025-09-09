<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-700 flex items-center justify-center p-4">
    <div class="relative z-10 w-full max-w-md">
      <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20 p-8">
        <div class="text-center mb-8">
          <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
              <img src="/assets/img/WhatsApp Image 2025-09-07 at 17.12.58.jpeg" alt="Logo" class="w-12 h-12 rounded-xl object-cover">
            </div>
          </div>
          <h1 class="text-3xl font-bold text-white mb-2">Owner Login</h1>
          <p class="text-white/80 text-sm">Masuk sebagai pemilik</p>
        </div>

        <div v-if="errors.length > 0" class="mb-6 bg-red-500/20 border border-red-500/30 text-red-100 px-4 py-3 rounded-xl backdrop-blur-sm">
          <ul>
            <li v-for="e in errors" :key="e" class="text-sm">{{ e }}</li>
          </ul>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-medium text-white/90 mb-1">Email</label>
            <input id="email" v-model="form.email" type="email" required class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30" placeholder="owner@angkringan.com">
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-white/90 mb-1">Password</label>
            <input id="password" v-model="form.password" type="password" required class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/30" placeholder="Password">
          </div>

          <button type="submit" :disabled="loading" class="w-full py-3 rounded-xl text-sm font-semibold text-white bg-gray-900/80 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-white/30 disabled:opacity-50">{{ loading ? 'Memproses...' : 'Masuk' }}</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OwnerLogin',
  data() {
    return {
      form: { email: '', password: '' },
      loading: false,
      errors: []
    }
  },
  methods: {
    async submit() {
      this.loading = true;
      this.errors = [];
      try {
        const res = await fetch('/owner/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(this.form)
        });
        const data = await res.json();
        if (res.ok && data?.success) {
          window.location.href = data.redirect || '/admin/owner';
        } else {
          this.errors = data?.errors || ['Login gagal'];
        }
      } catch (e) {
        this.errors = ['Terjadi kesalahan'];
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>


