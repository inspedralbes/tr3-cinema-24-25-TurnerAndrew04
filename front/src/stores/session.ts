import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'
import type { Session, Seat, Ticket } from '../types'
import { useTicketStore } from './tickets'

const API_URL = import.meta.env.VITE_API_URL

export const useSessionStore = defineStore('session', () => {
  const selectedSeats = ref<Seat[]>([])
  const currentSession = ref<Session | null>(null)
  const ticketStore = useTicketStore()
  
  const totalPrice = computed(() => {
    return selectedSeats.value.reduce((total, seat) => {
      // Apply special day discount if applicable
      const basePrice = seat.isVip ? 8 : 6
      return total + (currentSession.value?.isSpecialDay ? basePrice * 0.8 : basePrice)
    }, 0)
  })

  function selectSeat(seat: Seat) {
    if (selectedSeats.value.length >= 10) {
      throw new Error('Maximum 10 seats per session')
    }
    
    // Check if seat is already selected
    const isAlreadySelected = selectedSeats.value.some(
      s => s.row === seat.row && s.number === seat.number
    )
    
    if (isAlreadySelected) {
      throw new Error('This seat is already selected')
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
      // For now, simulate API call and store in local storage
      const tickets = selectedSeats.value.map((seat, index) => ({
        id: Date.now() + index,
        sessionId,
        row: seat.row,
        number: seat.number,
        price: seat.isVip ? 8 : 6,
        customerName: customerData.name,
        customerEmail: customerData.email,
        customerPhone: customerData.phone
      }))

      // Store tickets in local storage
      const storedTickets = JSON.parse(localStorage.getItem('tickets') || '[]')
      localStorage.setItem('tickets', JSON.stringify([...storedTickets, ...tickets]))

      // Add purchased tickets to the ticket store
      tickets.forEach(ticket => {
        ticketStore.addTicket(customerData.email, ticket)
      })
      
      // Clear the selection after successful purchase
      clearSelection()
      
      return { tickets }
    } catch (error: any) {
      throw new Error('Error purchasing tickets')
    }
  }

  function setCurrentSession(session: Session) {
    currentSession.value = session
  }

  return {
    selectedSeats,
    currentSession,
    totalPrice,
    selectSeat,
    unselectSeat,
    clearSelection,
    purchaseTickets,
    setCurrentSession
  }
})