<script setup lang="ts">
import { computed } from 'vue'
import type { Session, Movie } from '../types'

const props = defineProps<{
  session: Session
  movie: Movie
}>()

const formattedTime = computed(() => {
  return new Date(`2000-01-01T${props.session.time}`).toLocaleTimeString('ca', {
    hour: '2-digit',
    minute: '2-digit'
  })
})
</script>

<template>
  <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
    <img :src="movie.poster" :alt="movie.title" class="w-full h-48 object-cover rounded mb-4">
    <h3 class="text-xl font-bold mb-2">{{ movie.title }}</h3>
    <div class="text-gray-600">
      <p>{{ new Date(session.date).toLocaleDateString('ca') }}</p>
      <p>{{ formattedTime }}</p>
      <p v-if="session.isSpecialDay" class="text-red-600 font-semibold">Dia de l'espectador</p>
    </div>
    <router-link 
      :to="{ name: 'session-detail', params: { id: session.id }}"
      class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
    >
      Comprar entrades
    </router-link>
  </div>
</template>