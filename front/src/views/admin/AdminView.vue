<script setup lang="ts">
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-md">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
          <h1 class="text-xl font-bold">Administració</h1>
          <div class="flex space-x-4">
            <router-link
              :to="{ name: 'admin-sessions' }"
              class="px-3 py-2 rounded-md hover:bg-gray-100"
              active-class="bg-gray-100"
            >
              Sessions
            </router-link>
            <router-link
              :to="{ name: 'admin-reports' }"
              class="px-3 py-2 rounded-md hover:bg-gray-100"
              active-class="bg-gray-100"
            >
              Informes
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
      <router-view></router-view>
    </main>
  </div>
</template>