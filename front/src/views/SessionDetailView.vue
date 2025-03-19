<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { Session, Movie, Seat, Ticket } from '../types'
import { useSessionStore } from '../stores/session'
import { useTicketStore } from '../stores/tickets'
import SeatMap from '../components/SeatMap.vue'
import movieData from '../data/movies.json'

const route = useRoute()
const router = useRouter()
const sessionStore = useSessionStore()
const ticketStore = useTicketStore()

const session = ref<Session | null>(null)
const movie = ref<Movie | null>(null)
const seats = ref<Seat[]>([])

const customerData = ref({
  name: '',
  email: '',
  phone: ''
})

// Pseudo-random number generator with seed
function seededRandom(seed: number) {
  const x = Math.sin(seed) * 10000
  return x - Math.floor(x)
}

// Function to determine if a seat should be occupied based on session ID
function isOccupied(sessionId: number, row: string, number: number): boolean {
  // First check if the seat has been purchased
  if (ticketStore.isSeatOccupied(sessionId, row, number)) {
    return true
  }

  // If not purchased, use the seeded random occupation
  const seed = sessionId + row.charCodeAt(0) * 100 + number
  const randomValue = seededRandom(seed)
  
  const rowIndex = row.charCodeAt(0) - 'A'.charCodeAt(0)
  
  if (rowIndex < 4) {
    return randomValue < 0.4
  } else if (rowIndex < 8) {
    return randomValue < 0.3
  } else {
    return randomValue < 0.2
  }
}

onMounted(async () => {
  const sessionId = parseInt(route.params.id as string)
  const selectedSession = movieData.sessions.find(s => s.session.id === sessionId)
  
  if (selectedSession) {
    session.value = selectedSession.session
    movie.value = selectedSession.movie
    
    // Generate seats data with deterministic occupation
    const seatRows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L']
    seats.value = seatRows.flatMap(row => 
      Array.from({ length: 10 }, (_, i) => ({
        row,
        number: i + 1,
        isVip: row === 'F',
        isOccupied: isOccupied(sessionId, row, i + 1)
      }))
    )
  }
})

const handleSubmit = async () => {
  if (sessionStore.selectedSeats.length === 0) {
    alert('Selecciona almenys un seient')
    return
  }
  
  // Create tickets for each selected seat
  sessionStore.selectedSeats.forEach(seat => {
    const ticket: Ticket = {
      id: Math.floor(Math.random() * 1000000), // Generate random ID for demo
      sessionId: session.value!.id,
      row: seat.row,
      number: seat.number,
      price: seat.isVip ? 8 : 6,
      customerName: customerData.value.name,
      customerEmail: customerData.value.email,
      customerPhone: customerData.value.phone
    }
    ticketStore.addTicket(customerData.value.email, ticket)
  })

  alert(`Compra realitzada amb èxit!\n\nEntrades: ${sessionStore.selectedSeats.length}\nTotal: ${sessionStore.totalPrice}€`)
  sessionStore.clearSelection()
  
  // Redirect to ticket check view
  router.push({
    name: 'check-tickets',
    query: { email: customerData.value.email }
  })
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