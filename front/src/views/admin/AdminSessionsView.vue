<script setup lang="ts">
import { ref } from 'vue'
import type { Session } from '../../types'

const newSession = ref({
  movieId: '',
  date: '',
  time: '16:00',
  isSpecialDay: false
})

const createSession = async () => {
  // TODO: Implement session creation logic
}

const searchMovie = async (query: string) => {
  // TODO: Implement OMDB API search
}
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold mb-8">Gestió de sessions</h2>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <h3 class="text-xl font-bold mb-4">Nova sessió</h3>
      
      <form @submit.prevent="createSession" class="max-w-md">
        <div class="mb-4">
          <label class="block text-gray-700 mb-2">Pel·lícula (OMDB)</label>
          <input
            type="text"
            @input="e => searchMovie((e.target as HTMLInputElement).value)"
            class="w-full px-3 py-2 border rounded"
          >
        </div>
        
        <div class="mb-4">
          <label class="block text-gray-700 mb-2">Data</label>
          <input
            v-model="newSession.date"
            type="date"
            required
            class="w-full px-3 py-2 border rounded"
          >
        </div>
        
        <div class="mb-4">
          <label class="block text-gray-700 mb-2">Hora</label>
          <select
            v-model="newSession.time"
            class="w-full px-3 py-2 border rounded"
          >
            <option value="16:00">16:00</option>
            <option value="18:00">18:00</option>
            <option value="20:00">20:00</option>
          </select>
        </div>
        
        <div class="mb-4">
          <label class="flex items-center">
            <input
              v-model="newSession.isSpecialDay"
              type="checkbox"
              class="mr-2"
            >
            Dia de l'espectador
          </label>
        </div>
        
        <button
          type="submit"
          class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"
        >
          Crear sessió
        </button>
      </form>
    </div>
  </div>
</template>