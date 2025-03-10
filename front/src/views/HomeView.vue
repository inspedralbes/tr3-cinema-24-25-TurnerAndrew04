<script setup lang="ts">
import { ref, onMounted } from 'vue'
import type { Session, Movie } from '../types'
import SessionCard from '../components/SessionCard.vue'

const featuredSessions = ref<Array<{ session: Session, movie: Movie }>>([])

onMounted(async () => {
  // Sample featured sessions
  featuredSessions.value = [
    {
      session: {
        id: 1,
        movieId: "tt0468569",
        date: "2025-03-20",
        time: "16:00",
        isSpecialDay: false
      },
      movie: {
        id: "tt0468569",
        title: "The Dark Knight",
        year: "2008",
        poster: "https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_SX300.jpg",
        plot: "When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.",
        duration: "152 min"
      }
    }
  ]
})
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold mb-4">Cinema Pedralbes</h1>
      <p class="text-xl text-gray-600">El millor cinema de Barcelona</p>
    </div>

    <div class="mb-12">
      <h2 class="text-2xl font-bold mb-6">Sessions destacades</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <SessionCard
          v-for="({ session, movie }) in featuredSessions"
          :key="session.id"
          :session="session"
          :movie="movie"
        />
      </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8 text-center">
      <h2 class="text-2xl font-bold mb-4">Compra les teves entrades</h2>
      <p class="text-gray-600 mb-6">
        Descobreix les millors pel·lícules i reserva els teus seients preferits
      </p>
      <router-link
        :to="{ name: 'sessions' }"
        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 inline-block"
      >
        Veure totes les sessions
      </router-link>
    </div>
  </div>
</template>