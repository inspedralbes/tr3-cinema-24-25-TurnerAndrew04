<script setup lang="ts">
import { computed } from 'vue'
import type { Seat } from '../types'
import { useSessionStore } from '../stores/session'

const props = defineProps<{ seats: Seat[] }>()

const sessionStore = useSessionStore()

// Agrupa los asientos por fila y los ordena
const rows = computed(() => {
  const rowMap = new Map<string, Seat[]>()
  props.seats.forEach(seat => {
    if (!rowMap.has(seat.row)) {
      rowMap.set(seat.row, [])
    }
    rowMap.get(seat.row)?.push(seat)
  })
  return Array.from(rowMap.entries()).sort(([a], [b]) => a.localeCompare(b))
})

// Verifica si un asiento está seleccionado
const isSelected = (seat: Seat) => sessionStore.selectedSeats.some(s => 
  s.row === seat.row && s.number === seat.number
)

// Maneja la selección y deselección de asientos
const toggleSeat = (seat: Seat) => {
  // Si el asiento está ocupado, no hace nada
  if (seat.isOccupied) return
  
  // Si no está ocupado, alterna la selección
  isSelected(seat) ? sessionStore.unselectSeat(seat) : sessionStore.selectSeat(seat)
}

// Establece el precio de un asiento (si es VIP será 8€, si no, lo ajustas como necesites)
const getSeatPrice = (seat: Seat) => seat.isVip ? 8 : seat.price
</script>

<template>
  <div class="flex flex-col items-center gap-4">
    <!-- Representación de la pantalla -->
    <div class="w-full h-8 bg-gray-300 rounded text-center leading-8 mb-8">
      Pantalla
    </div>

    <!-- Mapa de asientos -->
    <div class="grid gap-2">
      <div v-for="[row, seats] in rows" :key="row" class="flex gap-2 items-center">
        <span class="w-6 text-center font-bold">{{ row }}</span>
        <div class="flex gap-2">
          <button
            v-for="seat in seats"
            :key="`${seat.row}-${seat.number}`"
            class="w-8 h-8 rounded transition-all duration-200"
            :class="{
              'bg-gray-200 hover:bg-gray-300': !seat.isOccupied && !isSelected(seat),
              'bg-red-500 cursor-not-allowed': seat.isOccupied, //  Asiento ocupado
              'bg-green-500': isSelected(seat), //  Asiento seleccionado
              'ring-2 ring-yellow-500': seat.isVip, //  Asiento VIP
              'border-2 border-yellow-500': seat.isVip, // Contorno amarillo si es VIP
            }"
            :disabled="seat.isOccupied"
            @click="toggleSeat(seat)"
          >
            {{ seat.number }}
          </button>
        </div>
      </div>
    </div>

    <!-- Leyenda de colores -->
    <div class="flex gap-4 mt-4 text-sm">
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-gray-200 border"></div>
        <span>Disponible</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-red-500 border"></div>
        <span>Ocupado</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-green-500 border"></div>
        <span>Seleccionado</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 ring-2 ring-yellow-500 border"></div>
        <span>VIP</span>
      </div>
    </div>
  </div>
</template>