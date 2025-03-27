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
const isLoading = ref(false)
const error = ref('')

const customerData = ref({
  name: '',
  email: '',
  phone: ''
})

// Fetch seats from the API
async function fetchSeats(sessionId: number) {
  try {
    const response = await fetch(`http://localhost:8000/api/sessions/${sessionId}/seats`);
    if (!response.ok) throw new Error('Error fetching seats');

    const data = await response.json();

    return data.map((seat: any) => ({
      id: seat.id,
      row: seat.row,
      number: seat.number,
      isVip: seat.is_vip,
      isOccupied: seat.is_occupied, // 
      disabled: seat.is_occupied // Deshabilitar si está ocupado
    }));
  } catch (error) {
    console.error('Error fetching seats:', error);
    throw error;
  }
}

onMounted(async () => {
  const sessionId = parseInt(route.params.id as string)
  const selectedSession = movieData.sessions.find(s => s.session.id === sessionId)

  if (selectedSession) {
    session.value = selectedSession.session
    movie.value = selectedSession.movie

    try {
      // Fetch seats from the API
      seats.value = await fetchSeats(sessionId)
    } catch (err) {
      error.value = 'Error loading seats'
      console.error(err)
    }
  }
})

const handleSubmit = async () => {
  if (sessionStore.selectedSeats.length === 0) {
    error.value = 'Selecciona almenys un seient'
    return
  }

  error.value = ''
  isLoading.value = true

  try {
    // Llamar a la función para comprar los tickets
    await purchaseTickets(session.value!.id, customerData.value)

    // Redirect to ticket check view
    router.push({
      name: 'check-tickets',
      query: { email: customerData.value.email }
    })
  } catch (err: any) {
    error.value = err.message
  } finally {
    isLoading.value = false
  }
}

async function purchaseTickets(sessionId: number, customerData: any) {
  const payload = {
    session_id: sessionId,
    seats: sessionStore.selectedSeats.map(seat => ({ id: seat.id })), // Ahora debería funcionar
    customer_name: customerData.name,
    customer_email: customerData.email,
    customer_phone: customerData.phone,
  }

  console.log("Payload enviado al backend:", payload);

  try {
    const response = await fetch('http://localhost:8000/api/tickets', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });

    const data = await response.json();

    if (!response.ok) throw new Error(data.message || 'Error al comprar entradas');

    console.log('Tickets comprados:', data);
    return data;
  } catch (error) {
    console.error('Error en la compra:', error);
    throw error;
  }
}
</script>

<template>
  <div class="min-h-screen bg-[var(--background)]" v-if="session && movie">
    <!-- Movie Hero Section -->
    <div class="relative h-[50vh] overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[var(--background)] to-[var(--background)]">
      </div>
      <img :src="movie.poster" :alt="movie.title" class="w-full h-full object-cover opacity-50">
    </div>

    <div class="container mx-auto px-4 -mt-32 relative z-10">
      <div class="bg-[var(--surface)] rounded-lg shadow-xl overflow-hidden">
        <div class="p-8">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Movie Poster -->
            <div class="lg:col-span-1">
              <img :src="movie.poster" :alt="movie.title" class="w-full rounded-lg shadow-lg">
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
              <div v-if="error" class="mb-4 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-500">
                {{ error }}
              </div>
              <div class="mb-4">
                <div class="flex items-center gap-4 justify-center">
                  <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-red-500 rounded"></div>
                    <span>Ocupat</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-gray-500 rounded"></div>
                    <span>Disponible</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-blue-500 rounded"></div>
                    <span>Seleccionat</span>
                  </div>
                </div>
              </div>
              <SeatMap :seats="seats" />
            </div>
          </div>

          <!-- Purchase Form -->
          <div class="mt-12" v-if="sessionStore.selectedSeats.length > 0">
            <h2 class="text-2xl font-bold mb-6">Dades de compra</h2>

            <div v-if="error" class="mb-4 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-500">
              {{ error }}
            </div>

            <form @submit.prevent="handleSubmit" class="max-w-md">
              <div class="space-y-4">
                <div>
                  <label class="block text-[var(--text-secondary)] mb-2">Nom complet</label>
                  <input v-model="customerData.name" type="text" required :disabled="isLoading"
                    class="w-full px-4 py-2 rounded-lg bg-[var(--background)] border border-gray-700 text-[var(--text)] focus:outline-none focus:border-[var(--primary)]">
                </div>
                <div>
                  <label class="block text-[var(--text-secondary)] mb-2">Email</label>
                  <input v-model="customerData.email" type="email" required :disabled="isLoading"
                    class="w-full px-4 py-2 rounded-lg bg-[var(--background)] border border-gray-700 text-[var(--text)] focus:outline-none focus:border-[var(--primary)]">
                </div>
                <div>
                  <label class="block text-[var(--text-secondary)] mb-2">Telèfon</label>
                  <input v-model="customerData.phone" type="tel" required :disabled="isLoading"
                    class="w-full px-4 py-2 rounded-lg bg-[var(--background)] border border-gray-700 text-[var(--text)] focus:outline-none focus:border-[var(--primary)]">
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

                  <button type="submit" class="w-full btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="isLoading">
                    <span v-if="isLoading">Processant...</span>
                    <span v-else>Confirmar compra</span>
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