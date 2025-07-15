<template>
  <div>
    <h1 class="text-3xl font-bold mb-4">Admin Dashboard</h1>
    <p class="text-gray-700 mb-8">Welcome, admin! Here you can manage users and tasks.</p>
    <div v-if="loading" class="flex justify-center items-center h-32">
      <span class="text-gray-500">Loading stats...</span>
    </div>
    <div v-else-if="error" class="flex justify-center items-center h-32">
      <span class="text-red-500">{{ error }}</span>
    </div>
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6 mb-8">
      <div class="bg-white rounded shadow p-6 flex flex-col items-center min-w-0">
        <div class="text-2xl font-bold text-gray-800 mb-2">{{ stats.users }}</div>
        <div class="text-gray-700">Total Users</div>
      </div>
      <div class="bg-white rounded shadow p-6 flex flex-col items-center min-w-0">
        <div class="text-2xl font-bold text-gray-800 mb-2">{{ stats.tasks }}</div>
        <div class="text-gray-700">Total Tasks</div>
      </div>
      <div class="bg-white rounded shadow p-6 flex flex-col items-center min-w-0">
        <div class="text-2xl font-bold text-yellow-600 mb-2">{{ stats.pending }}</div>
        <div class="text-gray-700">Pending</div>
      </div>
      <div class="bg-white rounded shadow p-6 flex flex-col items-center min-w-0">
        <div class="text-2xl font-bold text-blue-600 mb-2">{{ stats.in_progress }}</div>
        <div class="text-gray-700">In Progress</div>
      </div>
      <div class="bg-white rounded shadow p-6 flex flex-col items-center min-w-0">
        <div class="text-2xl font-bold text-green-600 mb-2">{{ stats.completed }}</div>
        <div class="text-gray-700">Completed</div>
      </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
      <router-link to="/admin/users" class="block focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">
        <div class="bg-white rounded shadow p-6 h-full flex flex-col justify-center hover:bg-gray-50 transition">
          <h2 class="text-xl font-semibold mb-2">Users</h2>
          <p class="text-gray-600">Manage all users in the system.</p>
        </div>
      </router-link>
      <router-link to="/admin/tasks" class="block focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">
        <div class="bg-white rounded shadow p-6 h-full flex flex-col justify-center hover:bg-gray-50 transition">
          <h2 class="text-xl font-semibold mb-2">Tasks</h2>
          <p class="text-gray-600">View and assign tasks to users.</p>
        </div>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const stats = ref({ users: 0, tasks: 0, pending: 0, in_progress: 0, completed: 0 })
const loading = ref(true)
const error = ref(null)

const fetchStats = async () => {
  loading.value = true
  error.value = null
  try {
    const res = await axios.get('http://localhost:8000/api/admin/summary')
    stats.value = res.data
  } catch (err) {
    error.value = 'Failed to load stats'
  } finally {
    loading.value = false
  }
}
onMounted(fetchStats)
</script> 