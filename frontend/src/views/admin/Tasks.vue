<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Tasks</h1>
      <button @click="openCreate" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">Add Task</button>
    </div>
    <table class="min-w-full bg-white rounded shadow">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="py-2 px-4">Title</th>
          <th class="py-2 px-4">Assigned To</th>
          <th class="py-2 px-4">Status</th>
          <th class="py-2 px-4">Deadline</th>
          <th class="py-2 px-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="task in tasks" :key="task.id" class="border-b">
          <td class="py-2 px-4">{{ task.title }}</td>
          <td class="py-2 px-4">{{ task.assigned_to?.name }}</td>
          <td class="py-2 px-4">{{ task.status }}</td>
          <td class="py-2 px-4">{{ task.deadline }}</td>
          <td class="py-2 px-4 space-x-2">
            <button @click="editTask(task)" class="text-blue-600 hover:underline">Edit</button>
            <button @click="deleteTask(task.id)" class="text-red-600 hover:underline">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Create/Edit Modal -->
    <div v-if="showCreate || showEdit" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded shadow w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">{{ showEdit ? 'Edit Task' : 'Add Task' }}</h2>
        <form @submit.prevent="showEdit ? updateTask() : createTask()">
          <div class="mb-4">
            <label class="block mb-1">Title</label>
            <input v-model="form.title" type="text" required class="w-full px-3 py-2 border rounded" />
            <div v-if="errors.title" class="text-red-600 text-sm mt-1">{{ errors.title[0] }}</div>
          </div>
          <div class="mb-4">
            <label class="block mb-1">Description</label>
            <textarea v-model="form.description" class="w-full px-3 py-2 border rounded"></textarea>
            <div v-if="errors.description" class="text-red-600 text-sm mt-1">{{ errors.description[0] }}</div>
          </div>
          <div class="mb-4">
            <label class="block mb-1">Deadline</label>
            <input v-model="form.deadline" type="date" class="w-full px-3 py-2 border rounded" />
            <div v-if="errors.deadline" class="text-red-600 text-sm mt-1">{{ errors.deadline[0] }}</div>
          </div>
          <div class="mb-4">
            <label class="block mb-1">Assign To</label>
            <select v-model="form.assigned_to" required class="w-full px-3 py-2 border rounded">
              <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
            </select>
            <div v-if="errors.assigned_to" class="text-red-600 text-sm mt-1">{{ errors.assigned_to[0] }}</div>
          </div>
          <div class="mb-4">
            <label class="block mb-1">Status</label>
            <select v-model="form.status" required class="w-full px-3 py-2 border rounded">
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
            </select>
            <div v-if="errors.status" class="text-red-600 text-sm mt-1">{{ errors.status[0] }}</div>
          </div>
          <div class="flex justify-end space-x-2">
            <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">{{ showEdit ? 'Update' : 'Create' }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const tasks = ref([])
const users = ref([])
const showCreate = ref(false)
const showEdit = ref(false)
const form = ref({ title: '', description: '', deadline: '', assigned_to: '', status: 'pending', id: null })
const errors = ref({})
const API_URL = 'http://localhost:8000/api/tasks'
const USERS_URL = 'http://localhost:8000/api/users'

const fetchTasks = async () => {
  const res = await axios.get(API_URL)
  tasks.value = res.data
}
const fetchUsers = async () => {
  const res = await axios.get(USERS_URL)
  users.value = res.data
}
onMounted(() => {
  fetchTasks()
  fetchUsers()
})

const closeModal = () => {
  showCreate.value = false
  showEdit.value = false
  form.value = { title: '', description: '', deadline: '', assigned_to: '', status: 'pending', id: null }
  errors.value = {}
}

const openCreate = () => {
  showCreate.value = true
  showEdit.value = false
  form.value = { title: '', description: '', deadline: '', assigned_to: '', status: 'pending', id: null }
  errors.value = {}
}

const createTask = async () => {
  try {
    const { id, ...data } = form.value
    await axios.post(API_URL, data)
    closeModal()
    fetchTasks()
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors
    } else {
      alert('An error occurred')
    }
  }
}

const editTask = (task) => {
  form.value = { ...task, assigned_to: task.assigned_to, id: task.id }
  showEdit.value = true
  showCreate.value = false
  errors.value = {}
}

const updateTask = async () => {
  try {
    const { id, ...data } = form.value
    await axios.put(`${API_URL}/${id}`, data)
    closeModal()
    fetchTasks()
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors
    } else {
      alert('An error occurred')
    }
  }
}

const deleteTask = async (id) => {
  if (confirm('Are you sure you want to delete this task?')) {
    await axios.delete(`${API_URL}/${id}`)
    fetchTasks()
  }
}
</script> 