<script setup lang="ts">
import { computed } from 'vue'
import type { Seat } from '../types'
import { useSessionStore } from '../stores/session'

const props = defineProps<{
  seats: Seat[]
}>()

const sessionStore = useSessionStore()

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

const isSelected = (seat: Seat) => {
  return sessionStore.selectedSeats.some(s => 
    s.row === seat.row && s.number === seat.number
  )
}

const toggleSeat = (seat: Seat) => {
  if (seat.isOccupied) return
  
  if (isSelected(seat)) {
    sessionStore.unselectSeat(seat)
  } else {
    try {
      sessionStore.selectSeat(seat)
    } catch (error) {
      alert(error)
    }
  }
}
</script>

<template>
  <div class="flex flex-col items-center gap-4">
    <div class="w-full h-8 bg-gray-300 rounded text-center leading-8 mb-8">
      Pantalla
    </div>
    
    <div class="grid gap-2">
      <div v-for="[row, seats] in rows" :key="row" class="flex gap-2">
        <span class="w-6 text-center">{{ row }}</span>
        <div class="flex gap-2">
          <button
            v-for="seat in seats"
            :key="`${seat.row}-${seat.number}`"
            class="w-8 h-8 rounded"
            :class="{
              'bg-gray-200 hover:bg-gray-300': !seat.isOccupied && !isSelected(seat),
              'bg-red-500': seat.isOccupied,
              'bg-green-500': isSelected(seat),
              'ring-2 ring-yellow-500': seat.isVip
            }"
            :disabled="seat.isOccupied"
            @click="toggleSeat(seat)"
          >
            {{ seat.number }}
          </button>
        </div>
      </div>
    </div>
    
    <div class="flex gap-4 mt-4">
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-gray-200"></div>
        <span>Disponible</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-red-500"></div>
        <span>Ocupat</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-green-500"></div>
        <span>Seleccionat</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 ring-2 ring-yellow-500"></div>
        <span>VIP</span>
      </div>
    </div>
  </div>
</template>