<script setup lang="ts">
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-md mb-8">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
          <router-link :to="{ name: 'home' }" class="text-xl font-bold">
            Cinema Pedralbes
          </router-link>
          <div class="flex space-x-4">
            <router-link
              :to="{ name: 'sessions' }"
              class="px-3 py-2 rounded-md hover:bg-gray-100"
              active-class="bg-gray-100"
            >
              Sessions
            </router-link>
            <router-link
              :to="{ name: 'check-tickets' }"
              class="px-3 py-2 rounded-md hover:bg-gray-100"
              active-class="bg-gray-100"
            >
              Les meves entrades
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <router-view></router-view>
  </div>
</template>