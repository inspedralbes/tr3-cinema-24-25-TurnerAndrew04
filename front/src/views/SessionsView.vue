<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import type { Session, Movie } from '../types'
import SessionCard from '../components/SessionCard.vue'
import axios from 'axios'
import { format } from 'date-fns'

const sessions = ref<Array<{ session: Session, movie: Movie }>>([])
const selectedDate = ref('')
const availableDates = ref<string[]>([])
const error = ref('')
const isLoading = ref(false)

const today = format(new Date(), 'yyyy-MM-dd')

// Filtrar sesiones por fecha seleccionada
const filteredSessions = computed(() => {
  return sessions.value.filter(({ session }) => session.date === selectedDate.value)
})

// Obtener sesiones desde la API
const fetchSessions = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/v1/sessions')
    return response.data.sessions // Asegurar que accedemos al array de sesiones correctamente
  } catch (error) {
    console.error('Error fetching sessions:', error)
    throw new Error('Failed to fetch sessions')
  }
}

onMounted(async () => {
  isLoading.value = true
  error.value = ''

  try {
    const sessionsData = await fetchSessions()

    // Guardar sesiones directamente ya que el backend las devuelve con las películas incluidas
    sessions.value = sessionsData

    // Extraer fechas únicas de las sesiones
    const dates = new Set(sessions.value.map(({ session }) => session.date))
    availableDates.value = Array.from(dates).sort()

    // Seleccionar la fecha actual o la primera disponible
    selectedDate.value = availableDates.value.includes(today) ? today : availableDates.value[0] || ''
  } catch (err) {
    error.value = 'Error loading sessions. Please try again later.'
    console.error('Error in onMounted:', err)
  } finally {
    isLoading.value = false
  }
})

// Formatear fecha
const formatDate = (dateStr: string) => {
  return new Date(dateStr).toLocaleDateString('ca', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Verificar si la fecha es hoy
const isToday = (dateStr: string) => dateStr === today
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Sessions disponibles</h1>

    <div v-if="error" class="mb-8 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
      {{ error }}
    </div>

    <div v-if="isLoading" class="text-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 mx-auto"></div>
      <p class="mt-4">Carregant sessions...</p>
    </div>

    <template v-else>
      <div class="mb-8">
        <label class="block text-gray-700 mb-2">Selecciona una data:</label>
        <select 
          v-model="selectedDate"
          class="w-full md:w-auto px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option v-for="date in availableDates" :key="date" :value="date">
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
    </template>
  </div>
</template>