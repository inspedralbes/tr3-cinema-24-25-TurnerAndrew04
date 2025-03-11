<script setup lang="ts">
import { ref, onMounted } from 'vue'
import type { Session, Movie } from '../types'
import SessionCard from '../components/SessionCard.vue'
import movieData from '../data/movies.json'

const sessions = ref<Array<{ session: Session, movie: Movie }>>([])

onMounted(async () => {
  sessions.value = movieData.sessions
})
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Sessions disponibles</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <SessionCard
        v-for="{ session, movie } in sessions"
        :key="session.id"
        :session="session"
        :movie="movie"
      />
    </div>
  </div>
</template>