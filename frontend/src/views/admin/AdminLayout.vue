<template>
  <div class="min-h-screen flex bg-gray-100">
    <button @click="toggleNav" class="md:hidden fixed top-4 left-4 z-30 bg-gray-900 text-white p-2 rounded shadow focus:outline-none focus:ring-2 focus:ring-blue-400">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
    </button>
    <AdminNav :show="showNav" @close="showNav = false" />
    <main class="flex-1 p-8">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AdminNav from '../../components/admin/AdminNav.vue'

const showNav = ref(false)
const toggleNav = () => {
  showNav.value = !showNav.value
}
</script> 