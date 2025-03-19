import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Ticket } from '../types'

export const useTicketStore = defineStore('tickets', () => {
  const tickets = ref<{ [email: string]: Ticket[] }>({})
  const occupiedSeats = ref<{ [key: string]: boolean }>({})

  function addTicket(email: string, ticket: Ticket) {
    if (!tickets.value[email]) {
      tickets.value[email] = []
    }
    tickets.value[email].push(ticket)
    
    // Mark seat as occupied
    const seatKey = `${ticket.sessionId}-${ticket.row}-${ticket.number}`
    occupiedSeats.value[seatKey] = true
  }

  function getTicketsByEmail(email: string) {
    return tickets.value[email] || []
  }

  function isSeatOccupied(sessionId: number, row: string, number: number) {
    const seatKey = `${sessionId}-${row}-${number}`
    return occupiedSeats.value[seatKey] || false
  }

  return {
    tickets,
    occupiedSeats,
    addTicket,
    getTicketsByEmail,
    isSeatOccupied
  }
})