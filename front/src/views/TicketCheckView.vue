<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import type { User, Ticket } from '../types'
import { useTicketStore } from '../stores/tickets'

const route = useRoute()
const ticketStore = useTicketStore()

const email = ref('')
const user = ref<User | null>(null)
const error = ref('')

onMounted(() => {
  if (route.query.email) {
    email.value = route.query.email as string
    checkTickets()
  }
})

const checkTickets = async () => {
  error.value = ''
  console.log('Checking tickets for:', email.value)

  // Obtener los tickets desde el store
  const tickets = await ticketStore.getTicketsByEmail(email.value)
  console.log('Tickets found:', tickets)

  if (!tickets || tickets.length === 0) {
    error.value = "No s'han trobat entrades per aquest email"
    user.value = null
    return
  }

  // Asegúrate de que los tickets están correctamente estructurados con la relación a la película y sesión
  user.value = { email: email.value, tickets }
}

const formatDate = (date: string) => {
  const options: Intl.DateTimeFormatOptions = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }

  return new Date(date).toLocaleString('es-ES', options)
}
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
      <h1 class="text-3xl font-bold mb-8 text-white">Consulta les teves entrades</h1>

      <form @submit.prevent="checkTickets" class="mb-8">
        <div class="mb-4">
          <label class="block text-gray-700 mb-2 text-black">Email</label>
          <input v-model="email" type="email" required class="w-full px-3 py-2 border rounded text-black font-bold">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
          Consultar
        </button>
      </form>

      <div v-if="error" class="text-red-600 mb-4">
        {{ error }}
      </div>

      <div v-if="user">
        <h2 class="text-2xl font-bold mb-4 text-white">Les teves entrades</h2>
        <div v-for="ticket in user.tickets" :key="ticket.id" class="bg-white rounded-lg shadow-md p-4 mb-4">
          <!-- Mostrar título de la película -->
          <p class="font-bold mb-2 text-black">{{ ticket.cinema_session.movie.title }}</p>

          <!-- Mostrar nombre del comprador -->
          <p class="text-black"><strong>Reservat per:</strong> {{ ticket.customer_name }}</p>

          <!-- Mostrar número de asiento -->
          <p class="text-black"><strong>Seient:</strong> {{ ticket.seat_id }}</p>

          <!-- Mostrar precio de la entrada -->
          <p class="text-black"><strong>Preu:</strong> {{ ticket.price }}€</p>

          <!-- Mostrar fecha sin hora -->
          <p class="text-black"><strong>Data:</strong> {{ formatDate(ticket.cinema_session.date) }}</p>
        </div>
      </div>
    </div>
  </div>
</template>