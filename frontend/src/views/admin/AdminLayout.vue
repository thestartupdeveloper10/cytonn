<template>
  <div class="min-h-screen flex bg-gray-100">
    <AdminNav />
    <main class="flex-1 p-8">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import AdminNav from '../../components/admin/AdminNav.vue'
</script> 