import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Ticket } from '../types'

export const useTicketStore = defineStore('tickets', () => {
  const tickets = ref<{ [email: string]: Ticket[] }>({})

  function addTicket(email: string, ticket: Ticket) {
    if (!tickets.value[email]) {
      tickets.value[email] = []
    }
    tickets.value[email].push(ticket)
  }

  function getTicketsByEmail(email: string) {
    return tickets.value[email] || []
  }

  return {
    tickets,
    addTicket,
    getTicketsByEmail
  }
})