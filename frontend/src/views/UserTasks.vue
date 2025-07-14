<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">My Tasks</h1>
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
        <tr v-for="task in tasks" :key="task.id" class="border-b">
          <td class="py-2 px-4">{{ task.title }}</td>
          <td class="py-2 px-4">{{ task.description }}</td>
          <td class="py-2 px-4">{{ task.status }}</td>
          <td class="py-2 px-4">{{ task.deadline }}</td>
          <td class="py-2 px-4">
            <select v-model="task.status" @change="updateStatus(task)" class="px-2 py-1 rounded border">
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const tasks = ref([])
const API_URL = 'http://localhost:8000/api/user/tasks'

const fetchTasks = async () => {
  const res = await axios.get(API_URL)
  tasks.value = res.data
}
onMounted(fetchTasks)

const updateStatus = async (task) => {
  await axios.put(`http://localhost:8000/api/tasks/${task.id}`, { status: task.status })
  fetchTasks()
}
</script> 