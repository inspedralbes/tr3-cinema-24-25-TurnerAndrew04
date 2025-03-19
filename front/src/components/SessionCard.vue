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
  <div class="movie-card">
    <img :src="movie.poster" :alt="movie.title" class="w-full">
    <div class="movie-card-content">
      <h3 class="text-2xl font-bold mb-2">{{ movie.title }}</h3>
      <div class="text-gray-300 mb-4">
        <p>{{ new Date(session.date).toLocaleDateString('ca') }} - {{ formattedTime }}</p>
        <p v-if="session.isSpecialDay" class="text-[var(--primary)] font-semibold">Dia de l'espectador</p>
      </div>
      <router-link 
        :to="{ name: 'session-detail', params: { id: session.id }}"
        class="btn-primary inline-block"
      >
        Comprar entrades
      </router-link>
    </div>
  </div>
</template>