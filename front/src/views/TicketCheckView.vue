<script setup lang="ts">
import { ref } from 'vue'
import type { User } from '../types'

const email = ref('')
const user = ref<User | null>(null)
const error = ref('')

const checkTickets = async () => {
  // TODO: Implement ticket checking logic
}
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
      <h1 class="text-3xl font-bold mb-8">Consulta les teves entrades</h1>
      
      <form @submit.prevent="checkTickets" class="mb-8">
        <div class="mb-4">
          <label class="block text-gray-700 mb-2">Email</label>
          <input
            v-model="email"
            type="email"
            required
            class="w-full px-3 py-2 border rounded"
          >
        </div>
        <button
          type="submit"
          class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"
        >
          Consultar
        </button>
      </form>

      <div v-if="error" class="text-red-600 mb-4">
        {{ error }}
      </div>

      <div v-if="user">
        <h2 class="text-2xl font-bold mb-4">Les teves entrades</h2>
        <div v-for="ticket in user.tickets" :key="ticket.id" class="bg-white rounded-lg shadow-md p-4 mb-4">
          <p><strong>Sessió:</strong> {{ ticket.sessionId }}</p>
          <p><strong>Seient:</strong> {{ ticket.row }}{{ ticket.number }}</p>
          <p><strong>Preu:</strong> {{ ticket.price }}€</p>
        </div>
      </div>
    </div>
  </div>
</template>