<template>
  <footer class="mt-8 text-center text-xs text-gray-500 py-6">
    <div class="max-w-7xl mx-auto px-4">
      <p>© {{ year }} Angkringan • Owner Panel</p>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'OwnerFooter',
  computed: {
    year() {
      return new Date().getFullYear()
    }
  }
}
</script>


