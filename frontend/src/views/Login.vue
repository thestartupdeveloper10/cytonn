<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <form @submit.prevent="login" class="bg-white p-8 rounded shadow-md w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
      <div class="mb-4">
        <label class="block mb-1 text-gray-700">Email</label>
        <input v-model="email" type="email" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
      </div>
      <div class="mb-6">
        <label class="block mb-1 text-gray-700">Password</label>
        <input v-model="password" type="password" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
      </div>
      <button type="submit" class="w-full bg-gray-800 text-white py-2 rounded hover:bg-gray-700 transition">Login</button>
      <p v-if="error" class="text-red-500 mt-4 text-center">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../store/auth'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const error = ref('')
const auth = useAuthStore()
const router = useRouter()

const login = async () => {
  error.value = ''
  try {
    await auth.login(email.value, password.value)
    router.push('/dashboard')
  } catch (e) {
    error.value = e.response?.data?.message || 'Login failed'
  }
}
</script> 