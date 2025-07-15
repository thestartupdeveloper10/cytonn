<template>
  <nav class="w-full bg-gray-900 text-white flex items-center px-6 py-3 shadow">
    <div class="flex items-center space-x-6">
      <router-link to="/dashboard" class="font-bold text-xl tracking-wide">Cytonn</router-link>
      <router-link to="/dashboard" class="px-3 py-2 rounded hover:bg-gray-700" active-class="bg-gray-800">Dashboard</router-link>
      <router-link to="/tasks" class="px-3 py-2 rounded hover:bg-gray-700" active-class="bg-gray-800">Tasks</router-link>
    </div>
    <div class="flex-1"></div>
    <div class="flex items-center space-x-4">
      <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-lg font-bold">U</div>
      <button @click="logout" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white font-semibold flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" /></svg>
        <span>Logout</span>
      </button>
    </div>
  </nav>
</template>

<script setup>
import { useAuthStore } from '../store/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

const logout = async () => {
  await auth.logout()
  router.push('/login')
}
</script> 