import { defineStore } from 'pinia'
import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('token') || null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
  actions: {
    async login(email, password) {
      const res = await axios.post(`${API_URL}/login`, { email, password })
      this.token = res.data.access_token
      this.user = res.data.user
      localStorage.setItem('token', this.token)
      localStorage.setItem('user', JSON.stringify(this.user))
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
    },
    async register(name, email, password) {
      const res = await axios.post(`${API_URL}/register`, { name, email, password })
      this.token = res.data.access_token
      this.user = res.data.user
      localStorage.setItem('token', this.token)
      localStorage.setItem('user', JSON.stringify(this.user))
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
    },
    async logout() {
      try {
        await axios.post(`${API_URL}/logout`)
      } catch (e) {}
      this.token = null
      this.user = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      delete axios.defaults.headers.common['Authorization']
    },
  },
})

// Set token on app load if present
const token = localStorage.getItem('token')
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
} 