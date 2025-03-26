<script setup lang="ts">
import { ref, onMounted } from 'vue'
import type { Session, Movie } from '../types'
import SessionCard from '../components/SessionCard.vue'
import movieData from '../data/movies.json'

const featuredSessions = ref<Array<{ session: Session, movie: Movie }>>([])

onMounted(async () => {
  featuredSessions.value = movieData.sessions
})
</script>

<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <div class="relative h-[70vh] overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[var(--background)]"></div>
      <img
        src="https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
        alt="Cinema" class="w-full h-full object-cover">
      <div class="absolute inset-0 flex items-center justify-center text-center">
        <div class="max-w-4xl px-4">
          <h1 class="text-5xl md:text-7xl font-bold mb-6">Cinema Pedralbes</h1>
          <p class="text-xl md:text-2xl text-gray-200 mb-8">Descobreix la màgia del cinema en la millor pantalla de
            Barcelona</p>
          <router-link :to="{ name: 'sessions' }" class="btn-primary text-lg">
            Compra les teves entrades
          </router-link>
        </div>
      </div>
    </div>

    <!-- Featured Movies -->
    <div class="container mx-auto px-4 py-16">
      <h2 class="text-3xl font-bold mb-8">Sessions disponibles</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <SessionCard v-for="{ session, movie } in featuredSessions" :key="session.id" :session="session"
          :movie="movie" />
      </div>
    </div>

    <!-- Features Section -->
    <div class="bg-[var(--surface)] py-16">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
          <div class="p-6">
            <div class="text-4xl mb-4">🎥</div>
            <h3 class="text-xl font-bold mb-2">Última tecnologia</h3>
            <p class="text-gray-400">Pantalla 4K i so envoltant Dolby Atmos</p>
          </div>
          <div class="p-6">
            <div class="text-4xl mb-4">🍿</div>
            <h3 class="text-xl font-bold mb-2">Snack Bar</h3>
            <p class="text-gray-400">Crispetes acabades de fer i refrescos</p>
          </div>
          <div class="p-6">
            <div class="text-4xl mb-4">💺</div>
            <h3 class="text-xl font-bold mb-2">Seients VIP</h3>
            <p class="text-gray-400">Butaques reclinables amb més espai</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>