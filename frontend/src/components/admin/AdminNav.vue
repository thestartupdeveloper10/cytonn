<template>
  <nav class="w-56 bg-gray-900 text-white flex flex-col py-8 px-4 space-y-2">
    <div class="mb-8 text-2xl font-bold tracking-wide text-center">Admin</div>
    <router-link to="/admin/dashboard" class="py-2 px-3 rounded hover:bg-gray-700" active-class="bg-gray-800">Dashboard</router-link>
    <router-link to="/admin/users" class="py-2 px-3 rounded hover:bg-gray-700" active-class="bg-gray-800">Users</router-link>
    <router-link to="/admin/tasks" class="py-2 px-3 rounded hover:bg-gray-700" active-class="bg-gray-800">Tasks</router-link>
    <div class="flex-1"></div>
    <router-link to="/dashboard" class="py-2 px-3 rounded hover:bg-gray-700 text-sm text-gray-300" active-class="bg-gray-800">User Dashboard</router-link>
  </nav>
</template> 