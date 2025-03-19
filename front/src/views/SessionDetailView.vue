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
  <div class="min-h-screen bg-[var(--background)]" v-if="session && movie">
    <!-- Movie Hero Section -->
    <div class="relative h-[50vh] overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[var(--background)] to-[var(--background)]"></div>
      <img 
        :src="movie.poster" 
        :alt="movie.title"
        class="w-full h-full object-cover opacity-50"
      >
    </div>

    <div class="container mx-auto px-4 -mt-32 relative z-10">
      <div class="bg-[var(--surface)] rounded-lg shadow-xl overflow-hidden">
        <div class="p-8">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Movie Poster -->
            <div class="lg:col-span-1">
              <img 
                :src="movie.poster" 
                :alt="movie.title" 
                class="w-full rounded-lg shadow-lg"
              >
            </div>

            <!-- Movie Info -->
            <div class="lg:col-span-2">
              <h1 class="text-4xl font-bold mb-4">{{ movie.title }}</h1>
              <p class="text-[var(--text-secondary)] mb-6">{{ movie.plot }}</p>
              
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                  <p class="text-[var(--text-secondary)]">Data</p>
                  <p class="text-lg">{{ new Date(session.date).toLocaleDateString('ca') }}</p>
                </div>
                <div>
                  <p class="text-[var(--text-secondary)]">Hora</p>
                  <p class="text-lg">{{ session.time }}</p>
                </div>
                <div>
                  <p class="text-[var(--text-secondary)]">Duració</p>
                  <p class="text-lg">{{ movie.duration }}</p>
                </div>
                <div v-if="session.isSpecialDay">
                  <p class="text-[var(--primary)] font-semibold">Dia de l'espectador</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Seat Selection -->
          <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Selecció de seients</h2>
            <div class="bg-[var(--background)] p-8 rounded-lg">
              <SeatMap :seats="seats" />
            </div>
          </div>

          <!-- Purchase Form -->
          <div class="mt-12" v-if="sessionStore.selectedSeats.length > 0">
            <h2 class="text-2xl font-bold mb-6">Dades de compra</h2>
            <form @submit.prevent="handleSubmit" class="max-w-md">
              <div class="space-y-4">
                <div>
                  <label class="block text-[var(--text-secondary)] mb-2">Nom complet</label>
                  <input
                    v-model="customerData.name"
                    type="text"
                    required
                    class="w-full px-4 py-2 rounded-lg bg-[var(--background)] border border-gray-700 text-[var(--text)] focus:outline-none focus:border-[var(--primary)]"
                  >
                </div>
                <div>
                  <label class="block text-[var(--text-secondary)] mb-2">Email</label>
                  <input
                    v-model="customerData.email"
                    type="email"
                    required
                    class="w-full px-4 py-2 rounded-lg bg-[var(--background)] border border-gray-700 text-[var(--text)] focus:outline-none focus:border-[var(--primary)]"
                  >
                </div>
                <div>
                  <label class="block text-[var(--text-secondary)] mb-2">Telèfon</label>
                  <input
                    v-model="customerData.phone"
                    type="tel"
                    required
                    class="w-full px-4 py-2 rounded-lg bg-[var(--background)] border border-gray-700 text-[var(--text)] focus:outline-none focus:border-[var(--primary)]"
                  >
                </div>
                
                <div class="pt-4 border-t border-gray-700">
                  <div class="flex justify-between items-center mb-4">
                    <span class="text-[var(--text-secondary)]">
                      Seients seleccionats: {{ sessionStore.selectedSeats.length }}
                    </span>
                    <span class="text-2xl font-bold">
                      Total: {{ sessionStore.totalPrice }}€
                    </span>
                  </div>

                  <button
                    type="submit"
                    class="w-full btn-primary"
                  >
                    Confirmar compra
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>