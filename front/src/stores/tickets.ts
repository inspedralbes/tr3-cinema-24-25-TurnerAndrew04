import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Ticket } from '../types'

export const useTicketStore = defineStore('tickets', () => {
  // Initialize tickets from localStorage
  //const storedTickets = JSON.parse(localStorage.getItem('tickets') || '[]')
  const tickets = ref<{ [email: string]: Ticket[] }>({})
  const occupiedSeats = ref<{ [key: string]: boolean }>({})

  // Initialize tickets from localStorage
  // storedTickets.forEach((ticket: Ticket) => {
  //   addTicket(ticket.customerEmail, ticket)
  // })

  function addTicket(email: string, ticket: Ticket) {
    if (!tickets.value[email]) {
      tickets.value[email] = []
    }
    tickets.value[email].push(ticket)
    
    // Mark seat as occupied
    const seatKey = `${ticket.sessionId}-${ticket.row}-${ticket.number}`
    occupiedSeats.value[seatKey] = true
  }

  async function getTicketsByEmail(email: string) {
    try {
      const response = await fetch(`http://localhost:8000/api/v1/tickets?email=${email}`);
      
      if (!response.ok) {
        throw new Error('Error al obtener los tickets');
      }
  
      const data = await response.json();
      console.log('Tickets desde la API:', data);
  
      tickets.value[email] = data.tickets; // Guarda en el store
      return data.tickets;
    } catch (error) {
      console.error('Error:', error);
      return [];
    }
  }
  async function purchaseTickets(sessionId: number, customerData: any) {
    try {
      const response = await fetch('/api/v1/tickets', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          session_id: sessionId,
          seats: sessionStorage.selectedSeats, // Verifica que esta variable exista
          customer_name: customerData.name,
          customer_email: customerData.email,
          customer_phone: customerData.phone
        })
      });
  
      if (!response.ok) {
        throw new Error('Error al comprar entradas');
      }
  
      const data = await response.json();
      console.log('Tickets comprados:', data);
  
      // Guardar los tickets en el store
      data.tickets.forEach((ticket: Ticket) => {
        addTicket(ticket.customerEmail, ticket);
      });
  
      return data;
    } catch (error) {
      console.error('Error:', error);
      throw error;
    }
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