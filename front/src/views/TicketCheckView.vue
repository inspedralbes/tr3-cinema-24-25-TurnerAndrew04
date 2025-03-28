<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { jsPDF } from 'jspdf'
import QRCode from 'qrcode'  // Importamos la librería de QR
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

  const tickets = await ticketStore.getTicketsByEmail(email.value)
  console.log('Tickets found:', tickets)

  if (!tickets || tickets.length === 0) {
    error.value = "No s'han trobat entrades per aquest email"
    user.value = null
    return
  }

  user.value = { email: email.value, tickets }
}

// Formatear la fecha (sin la hora)
const formatDate = (date: string) => {
  const options: Intl.DateTimeFormatOptions = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  }

  return new Date(date).toLocaleString('es-ES', options)
}

// Función para generar el QR (enlace al trailer)
const generateQRCode = async (url: string) => {
  return await QRCode.toDataURL(url)
}

// Generar PDF
const downloadPDF = async () => {
  const doc = new jsPDF()

  // Para cada ticket, crear una página nueva
  for (const ticket of user.value!.tickets) {
    if (user.value!.tickets.indexOf(ticket) > 0) {
      doc.addPage()  // Nueva página por cada entrada
    }

    // Añadir título
    doc.setFontSize(18)
    doc.text("Entrada de Cinema", 20, 20)

    // Detalles de la entrada
    doc.setFontSize(12)
    let yPosition = 40
    doc.text(`Pel·lícula: ${ticket.cinema_session.movie.title}`, 20, yPosition)
    yPosition += 10
    doc.text(`Reservat per: ${ticket.customer_name}`, 20, yPosition)
    yPosition += 10
    doc.text(`Seient: ${ticket.seat_id}`, 20, yPosition)
    yPosition += 10
    doc.text(`Preu: ${ticket.price}€`, 20, yPosition)
    yPosition += 10
    doc.text(`Data: ${formatDate(ticket.cinema_session.date)}`, 20, yPosition)
    yPosition += 15

    // Generar el QR (enlace al trailer)
    // Aquí pondrías el enlace del trailer de la película (por ejemplo: YouTube)
    const qrUrl = `https://www.youtube.com/watch?v=${ticket.cinema_session.movie.trailer_id}` // acabar
    const qrCodeUrl = await generateQRCode(qrUrl)

    // Añadir QR en la página
    doc.addImage(qrCodeUrl, 'PNG', 160, 40, 30, 30) // Tamaño y posición del QR

    // Añadir pie de página
    doc.setFontSize(8)
    doc.text("Escaneja el QR para veure el tràiler de la pel·lícula", 20, yPosition + 50)
  }

  // Descargar el PDF
  doc.save("entrades.pdf")
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

          <!-- Mostrar fecha sin hora de la sesión -->
          <p class="text-black"><strong>Data:</strong> {{ formatDate(ticket.cinema_session.date) }}</p>
        </div>

        <!-- Botón para descargar el PDF -->
        <button @click="downloadPDF" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 mt-4">
          Descarregar PDF
        </button>
      </div>
    </div>
  </div>
</template>