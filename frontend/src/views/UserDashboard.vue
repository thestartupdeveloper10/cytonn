<template>
  <div>
    <UserNav />
    <div class="max-w-4xl mx-auto mt-10 p-6">
      <h1 class="text-3xl font-bold mb-8">User Dashboard</h1>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded shadow p-6 flex flex-col items-center">
          <div class="text-2xl font-bold text-yellow-600 mb-2">{{ summary.pending }}</div>
          <div class="text-gray-700">Pending Tasks</div>
        </div>
        <div class="bg-white rounded shadow p-6 flex flex-col items-center">
          <div class="text-2xl font-bold text-blue-600 mb-2">{{ summary.in_progress }}</div>
          <div class="text-gray-700">In Progress</div>
        </div>
        <div class="bg-white rounded shadow p-6 flex flex-col items-center">
          <div class="text-2xl font-bold text-green-600 mb-2">{{ summary.completed }}</div>
          <div class="text-gray-700">Completed</div>
        </div>

      </div>
      <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-2">Your Tasks</h2>
        <p class="text-gray-600">See all tasks assigned to you on the <router-link to="/tasks" class="text-blue-600 underline">Tasks</router-link> page.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import UserNav from '../components/UserNav.vue'
import axios from 'axios'

const summary = ref({ pending: 0, in_progress: 0, completed: 0, users: 0 })

const fetchSummary = async () => {
  const res = await axios.get('http://localhost:8000/api/user/summary')
  summary.value = res.data
}
onMounted(fetchSummary)
</script> 