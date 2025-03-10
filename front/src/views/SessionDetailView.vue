<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import type { Session, Movie, Seat } from '../types'
import { useSessionStore } from '../stores/session'
import SeatMap from '../components/SeatMap.vue'

const route = useRoute()
const sessionStore = useSessionStore()

const session = ref<Session | null>(null)
const movie = ref<Movie | null>(null)
const seats = ref<Seat[]>([])

const customerData = ref({
  name: '',
  email: '',
  phone: ''
})

onMounted(async () => {
  const sessionId = parseInt(route.params.id as string)
  
  // Sample data for the selected session
  const sessionsData = [
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
    },
    {
      session: {
        id: 2,
        movieId: "tt0109830",
        date: "2025-03-20",
        time: "18:00",
        isSpecialDay: true
      },
      movie: {
        id: "tt0109830",
        title: "Forrest Gump",
        year: "1994",
        poster: "https://m.media-amazon.com/images/M/MV5BNWIwODRlZTUtY2U3ZS00Yzg1LWJhNzYtMmZiYmEyNmU1NjMzXkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_SX300.jpg",
        plot: "The presidencies of Kennedy and Johnson, the Vietnam War, the Watergate scandal and other historical events unfold from the perspective of an Alabama man with an IQ of 75.",
        duration: "142 min"
      }
    },
    {
      session: {
        id: 3,
        movieId: "tt0111161",
        date: "2025-03-20",
        time: "20:00",
        isSpecialDay: false
      },
      movie: {
        id: "tt0111161",
        title: "The Shawshank Redemption",
        year: "1994",
        poster: "https://m.media-amazon.com/images/M/MV5BNDE3ODcxYzMtY2YzZC00NmNlLWJiNDMtZDViZWM2MzIxZDYwXkEyXkFqcGdeQXVyNjAwNDUxODI@._V1_SX300.jpg",
        plot: "Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.",
        duration: "142 min"
      }
    }
  ]

  const selectedSession = sessionsData.find(s => s.session.id === sessionId)
  if (selectedSession) {
    session.value = selectedSession.session
    movie.value = selectedSession.movie
    
    // Generate sample seats data
    const seatRows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L']
    seats.value = seatRows.flatMap(row => 
      Array.from({ length: 10 }, (_, i) => ({
        row,
        number: i + 1,
        isVip: row === 'F',
        isOccupied: Math.random() < 0.3 // 30% chance of being occupied
      }))
    )
  }
})

const handleSubmit = async () => {
  if (sessionStore.selectedSeats.length === 0) {
    alert('Selecciona almenys un seient')
    return
  }
  
  // Simulate purchase
  alert(`Compra realitzada amb èxit!\n\nEntrades: ${sessionStore.selectedSeats.length}\nTotal: ${sessionStore.totalPrice}€`)
  sessionStore.clearSelection()
}
</script>

<template>
  <div class="container mx-auto px-4 py-8" v-if="session && movie">
    <div class="bg-white rounded-lg shadow-md p-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div>
          <img :src="movie.poster" :alt="movie.title" class="w-full rounded">
        </div>
        <div>
          <h1 class="text-3xl font-bold mb-4">{{ movie.title }}</h1>
          <p class="text-gray-600 mb-4">{{ movie.plot }}</p>
          <div class="mb-4">
            <p><strong>Data:</strong> {{ new Date(session.date).toLocaleDateString('ca') }}</p>
            <p><strong>Hora:</strong> {{ session.time }}</p>
            <p><strong>Duració:</strong> {{ movie.duration }}</p>
            <p v-if="session.isSpecialDay" class="text-red-600 font-semibold">Dia de l'espectador</p>
          </div>
        </div>
      </div>

      <SeatMap :seats="seats" />

      <div class="mt-8" v-if="sessionStore.selectedSeats.length > 0">
        <h2 class="text-2xl font-bold mb-4">Dades de compra</h2>
        <form @submit.prevent="handleSubmit" class="max-w-md">
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nom complet</label>
            <input
              v-model="customerData.name"
              type="text"
              required
              class="w-full px-3 py-2 border rounded"
            >
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input
              v-model="customerData.email"
              type="email"
              required
              class="w-full px-3 py-2 border rounded"
            >
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Telèfon</label>
            <input
              v-model="customerData.phone"
              type="tel"
              required
              class="w-full px-3 py-2 border rounded"
            >
          </div>
          
          <div class="mb-4">
            <p class="text-xl font-bold">Total: {{ sessionStore.totalPrice }}€</p>
          </div>

          <button
            type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"
          >
            Confirmar compra
          </button>
        </form>
      </div>
    </div>
  </div>
</template>