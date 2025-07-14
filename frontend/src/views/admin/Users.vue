<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Users</h1>
      <button @click="openCreate" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">Add User</button>
    </div>
    <table class="min-w-full bg-white rounded shadow">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="py-2 px-4">Name</th>
          <th class="py-2 px-4">Email</th>
          <th class="py-2 px-4">Role</th>
          <th class="py-2 px-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id" class="border-b">
          <td class="py-2 px-4">{{ user.name }}</td>
          <td class="py-2 px-4">{{ user.email }}</td>
          <td class="py-2 px-4">{{ user.role }}</td>
          <td class="py-2 px-4 space-x-2">
            <button @click="editUser(user)" class="text-blue-600 hover:underline">Edit</button>
            <button @click="deleteUser(user.id)" class="text-red-600 hover:underline">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Create/Edit Modal -->
    <div v-if="showCreate || showEdit" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded shadow w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">{{ showEdit ? 'Edit User' : 'Add User' }}</h2>
        <form @submit.prevent="showEdit ? updateUser() : createUser()">
          <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input v-model="form.name" type="text" required class="w-full px-3 py-2 border rounded" />
            <div v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name[0] }}</div>
          </div>
          <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input v-model="form.email" type="email" required class="w-full px-3 py-2 border rounded" />
            <div v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email[0] }}</div>
          </div>
          <div class="mb-4" v-if="!showEdit">
            <label class="block mb-1">Password</label>
            <input v-model="form.password" type="password" required class="w-full px-3 py-2 border rounded" />
            <div v-if="errors.password" class="text-red-600 text-sm mt-1">{{ errors.password[0] }}</div>
          </div>
          <div class="mb-4" v-else>
            <label class="block mb-1">Password <span class="text-gray-500 text-xs">(leave blank to keep current)</span></label>
            <input v-model="form.password" type="password" class="w-full px-3 py-2 border rounded" />
            <div v-if="errors.password" class="text-red-600 text-sm mt-1">{{ errors.password[0] }}</div>
          </div>
          <div class="mb-4">
            <label class="block mb-1">Role</label>
            <select v-model="form.role" required class="w-full px-3 py-2 border rounded">
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
            <div v-if="errors.role" class="text-red-600 text-sm mt-1">{{ errors.role[0] }}</div>
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

const users = ref([])
const showCreate = ref(false)
const showEdit = ref(false)
const form = ref({ name: '', email: '', password: '', role: 'user' })
const errors = ref({})
const API_URL = 'http://localhost:8000/api/users'

const fetchUsers = async () => {
  const res = await axios.get(API_URL)
  users.value = res.data
}
onMounted(fetchUsers)

const closeModal = () => {
  showCreate.value = false
  showEdit.value = false
  form.value = { name: '', email: '', password: '', role: 'user' }
  errors.value = {}
}

const openCreate = () => {
  showCreate.value = true
  showEdit.value = false
  form.value = { name: '', email: '', password: '', role: 'user' }
  errors.value = {}
}

const createUser = async () => {
  try {
    await axios.post(API_URL, form.value)
    closeModal()
    fetchUsers()
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors
    } else {
      alert('An error occurred')
    }
  }
}

const editUser = (user) => {
  form.value = { name: user.name, email: user.email, password: '', role: user.role, id: user.id }
  showEdit.value = true
  showCreate.value = false
  errors.value = {}
}

const updateUser = async () => {
  try {
    const { id, ...data } = form.value
    if (!data.password) delete data.password
    await axios.put(`${API_URL}/${id}`, data)
    closeModal()
    fetchUsers()
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors
    } else {
      alert('An error occurred')
    }
  }
}

const deleteUser = async (id) => {
  if (confirm('Are you sure you want to delete this user?')) {
    await axios.delete(`${API_URL}/${id}`)
    fetchUsers()
  }
}
</script> 