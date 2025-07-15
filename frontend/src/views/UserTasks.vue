<template>
  <div>
    <UserNav />
    <div class="max-w-5xl mx-auto mt-10 p-6">
      <h1 class="text-2xl font-bold mb-6">My Tasks</h1>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="py-2 px-4">Title</th>
              <th class="py-2 px-4">Description</th>
              <th class="py-2 px-4">Status</th>
              <th class="py-2 px-4">Deadline</th>
              <th class="py-2 px-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="task in tasks" :key="task.id" class="border-b hover:bg-gray-50 transition">
              <td class="py-2 px-4 font-medium">{{ task.title }}</td>
              <td class="py-2 px-4 text-gray-700">{{ task.description }}</td>
              <td class="py-2 px-4">
                <span :class="statusClass(task.status)">{{ statusLabel(task.status) }}</span>
              </td>
              <td class="py-2 px-4">{{ task.deadline }}</td>
              <td class="py-2 px-4">
                <select v-model="task.status" @change="updateStatus(task)" class="px-2 py-1 rounded border focus:outline-none focus:ring-2 focus:ring-blue-400">
                  <option value="pending">Pending</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import UserNav from '../components/UserNav.vue'

const tasks = ref([])
const API_URL = 'http://127.0.0.1:8000/api/my-tasks'

const fetchTasks = async () => {
  const res = await axios.get(API_URL)
  tasks.value = res.data
}
onMounted(fetchTasks)

const updateStatus = async (task) => {
  console.log("task id",task)
  await axios.patch(`http://127.0.0.1:8000/api/tasks/${task.id}/status`, { status: task.status })
  fetchTasks()
}

const statusClass = (status) => {
  switch (status) {
    case 'pending':
      return 'inline-block px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800';
    case 'in_progress':
      return 'inline-block px-2 py-1 text-xs rounded bg-blue-100 text-blue-800';
    case 'completed':
      return 'inline-block px-2 py-1 text-xs rounded bg-green-100 text-green-800';
    default:
      return '';
  }
}

const statusLabel = (status) => {
  switch (status) {
    case 'pending':
      return 'Pending';
    case 'in_progress':
      return 'In Progress';
    case 'completed':
      return 'Completed';
    default:
      return status;
  }
}
</script> 