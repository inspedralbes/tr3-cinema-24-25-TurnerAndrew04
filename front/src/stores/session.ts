import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'
import type { Session, Seat, Ticket } from '../types'

const API_URL = import.meta.env.VITE_API_URL

export const useSessionStore = defineStore('session', () => {
  const selectedSeats = ref<Seat[]>([])
  const currentSession = ref<Session | null>(null)
  
  const totalPrice = computed(() => {
    return selectedSeats.value.reduce((total, seat) => {
      return total + (seat.isVip ? 8 : 6)
    }, 0)
  })

  function selectSeat(seat: Seat) {
    if (selectedSeats.value.length >= 10) {
      throw new Error('Maximum 10 seats per session')
    }
    selectedSeats.value.push(seat)
  }

  function unselectSeat(seat: Seat) {
    selectedSeats.value = selectedSeats.value.filter(s => 
      s.row !== seat.row || s.number !== seat.number
    )
  }

  function clearSelection() {
    selectedSeats.value = []
  }

  async function purchaseTickets(sessionId: number, customerData: {
    name: string
    email: string
    phone: string
  }) {
    try {
      const response = await axios.post(`${API_URL}/v1/tickets`, {
        session_id: sessionId,
        seats: selectedSeats.value.map(seat => ({
          row: seat.row,
          number: seat.number
        })),
        customer_name: customerData.name,
        customer_email: customerData.email,
        customer_phone: customerData.phone
      })
      
      return response.data
    } catch (error: any) {
      throw new Error(error.response?.data?.message || 'Error purchasing tickets')
    }
  }

  return {
    selectedSeats,
    currentSession,
    totalPrice,
    selectSeat,
    unselectSeat,
    clearSelection,
    purchaseTickets
  }
})