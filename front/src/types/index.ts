export interface Session {
  id: number
  movieId: string
  date: string
  time: string
  isSpecialDay: boolean
}

export interface Movie {
  id: string
  title: string
  year: string
  poster: string
  plot: string
  duration: string
}

export interface Seat {
  row: string
  number: number
  isVip: boolean
  isOccupied: boolean
}

export interface Ticket {
  id: number
  sessionId: number
  row: string
  number: number
  price: number
  customerName: string
  customerEmail: string
  customerPhone: string
}

export interface User {
  email: string
  tickets: Ticket[]
}