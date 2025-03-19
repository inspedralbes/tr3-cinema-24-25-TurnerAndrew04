<script setup lang="ts">
import { useAuthStore } from './stores/auth'

const authStore = useAuthStore()
</script>

<template>
  <div class="min-h-screen bg-[var(--background)]">
    <nav class="bg-[var(--surface)] shadow-lg">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
          <router-link :to="{ name: 'home' }" class="text-2xl font-bold text-[var(--primary)]">
            Cinema Pedralbes
          </router-link>
          <div class="flex items-center space-x-4">
            <div class="flex space-x-2">
              <router-link
                :to="{ name: 'sessions' }"
                class="nav-link"
                active-class="active"
              >
                Sessions
              </router-link>
              <router-link
                :to="{ name: 'check-tickets' }"
                class="nav-link"
                active-class="active"
              >
                Les meves entrades
              </router-link>
            </div>
            <div class="border-l border-gray-700 h-6 mx-2"></div>
            <template v-if="authStore.isAuthenticated">
              <router-link
                v-if="authStore.user?.is_admin"
                :to="{ name: 'admin' }"
                class="btn-primary"
              >
                Admin
              </router-link>
              <button
                @click="authStore.logout"
                class="nav-link"
              >
                Tancar sessió
              </button>
            </template>
            <template v-else>
              <router-link
                :to="{ name: 'login' }"
                class="nav-link"
              >
                Iniciar sessió
              </router-link>
              <router-link
                :to="{ name: 'register' }"
                class="btn-primary"
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