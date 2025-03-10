import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { Session, Seat, Ticket } from '../types'

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

  return {
    selectedSeats,
    currentSession,
    totalPrice,
    selectSeat,
    unselectSeat,
    clearSelection
  }
})