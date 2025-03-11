<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import type { Session, Movie } from '../types'
import SessionCard from '../components/SessionCard.vue'
import movieData from '../data/movies.json'
import { format } from 'date-fns'

const sessions = ref<Array<{ session: Session, movie: Movie }>>([])
const selectedDate = ref('')
const availableDates = ref<string[]>([])

const today = format(new Date(), 'yyyy-MM-dd')

const filteredSessions = computed(() => {
  return sessions.value.filter(({ session }) => session.date === selectedDate.value)
})

onMounted(async () => {
  sessions.value = movieData.sessions
  
  // Get unique dates from sessions
  const dates = new Set(sessions.value.map(({ session }) => session.date))
  availableDates.value = Array.from(dates).sort()
  
  // Set initial date to today if available, otherwise first available date
  if (availableDates.value.includes(today)) {
    selectedDate.value = today
  } else if (availableDates.value.length > 0) {
    selectedDate.value = availableDates.value[0]
  }
})

const formatDate = (dateStr: string) => {
  return new Date(dateStr).toLocaleDateString('ca', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const isToday = (dateStr: string) => dateStr === today
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Sessions disponibles</h1>
    
    <div class="mb-8">
      <label class="block text-gray-700 mb-2">Selecciona una data:</label>
      <select
        v-model="selectedDate"
        class="w-full md:w-auto px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        <option
          v-for="date in availableDates"
          :key="date"
          :value="date"
        >
          {{ formatDate(date) }}{{ isToday(date) ? ' (Avui)' : '' }}
        </option>
      </select>
    </div>
    
    <div v-if="filteredSessions.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <SessionCard
        v-for="{ session, movie } in filteredSessions"
        :key="session.id"
        :session="session"
        :movie="movie"
      />
    </div>
    <div v-else class="text-center text-gray-600">
      No hi ha sessions disponibles per aquesta data.
    </div>
  </div>
</template>