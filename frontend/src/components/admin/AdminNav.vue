<template>
  <nav
    :class="[
      'bg-gray-900 text-white flex flex-col py-8 px-4 space-y-2 z-40',
      'fixed top-0 left-0 w-56 md:static md:block',
      show ? 'block' : 'hidden',
      'transition-transform duration-200',
    ]"
  >
    <div class="mb-8 text-2xl font-bold tracking-wide text-center flex  items-center justify-between">
      <span>Admin</span>
      <button v-if="isMobile" @click="$emit('close')" class="md:hidden ml-2 p-1 rounded hover:bg-gray-700 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
      </button>
    </div>
<div class="flex flex-col space-y-2">
  <router-link to="/admin/dashboard" class="py-2 px-3 rounded hover:bg-gray-700" active-class="bg-gray-800" @click.native="onNavClick">Dashboard</router-link>
    <router-link to="/admin/users" class="py-2 px-3 rounded hover:bg-gray-700" active-class="bg-gray-800" @click.native="onNavClick">Users</router-link>
    <router-link to="/admin/tasks" class="py-2 px-3 rounded hover:bg-gray-700" active-class="bg-gray-800" @click.native="onNavClick">Tasks</router-link>
</div>
   

    <div class="flex-1"></div>
    <button @click="logout" class="px-4 flex justify-center py-2 bg-red-600 hover:bg-red-700 rounded text-white font-semibold flex items-center space-x-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" /></svg>
      <span>Logout</span>
    </button>
  </nav>
</template>

<script setup>
import { useAuthStore } from '../../store/auth'
import { useRouter } from 'vue-router'
import { onMounted, ref } from 'vue'

const props = defineProps({
  show: { type: Boolean, default: false }
})

const auth = useAuthStore()
const router = useRouter()

const logout = async () => {
  await auth.logout()
  router.push('/login')
}

const isMobile = ref(false)
const checkMobile = () => {
  isMobile.value = window.innerWidth < 768
}
onMounted(() => {
  checkMobile()
  window.addEventListener('resize', checkMobile)
})

const onNavClick = () => {
  if (isMobile.value) {
    window.setTimeout(() => {
      emit('close')
    }, 100)
  }
}
const emit = defineEmits(['close'])
</script> 