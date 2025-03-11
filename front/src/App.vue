<script setup lang="ts">
import { useAuthStore } from './stores/auth'

const authStore = useAuthStore()
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-md mb-8">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
          <router-link :to="{ name: 'home' }" class="text-xl font-bold">
            Cinema Pedralbes
          </router-link>
          <div class="flex items-center space-x-4">
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
            <div class="border-l border-gray-200 h-6 mx-2"></div>
            <template v-if="authStore.isAuthenticated">
              <router-link
                v-if="authStore.user?.is_admin"
                :to="{ name: 'admin' }"
                class="px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700"
              >
                Admin
              </router-link>
              <button
                @click="authStore.logout"
                class="px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100"
              >
                Tancar sessió
              </button>
            </template>
            <template v-else>
              <router-link
                :to="{ name: 'login' }"
                class="px-3 py-2 rounded-md hover:bg-gray-100"
              >
                Iniciar sessió
              </router-link>
              <router-link
                :to="{ name: 'register' }"
                class="px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700"
              >
                Registra't
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <router-view></router-view>
  </div>
</template>