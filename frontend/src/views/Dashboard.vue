<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md text-center">
      <h2 class="text-2xl font-bold mb-4">Welcome, {{ user?.name || 'User' }}!</h2>
      <p class="text-gray-700 mb-2">Email: <span class="font-mono">{{ user?.email }}</span></p>
      <p class="text-gray-700 mb-6">Role: <span class="font-semibold">{{ user?.role || 'user' }}</span></p>
      <button @click="logout" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 transition">Logout</button>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '../store/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()
const user = auth.user

const logout = async () => {
  await auth.logout()
  router.push('/login')
}
</script> 