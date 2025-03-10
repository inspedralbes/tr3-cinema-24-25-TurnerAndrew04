<script setup lang="ts">
import { ref } from 'vue'

const selectedDate = ref('')
const report = ref(null)

const generateReport = async () => {
  // TODO: Implement report generation logic
}
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold mb-8">Informes</h2>
    
    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Data</label>
        <input
          v-model="selectedDate"
          type="date"
          class="px-3 py-2 border rounded"
          @change="generateReport"
        >
      </div>
      
      <div v-if="report" class="mt-8">
        <h3 class="text-xl font-bold mb-4">Resum de la sessió</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div>
            <h4 class="font-bold mb-2">Entrades venudes</h4>
            <p>Normal: {{ report?.normalTickets }}</p>
            <p>VIP: {{ report?.vipTickets }}</p>
            <p class="font-bold mt-2">Total: {{ report?.totalTickets }}</p>
          </div>
          
          <div>
            <h4 class="font-bold mb-2">Recaptació</h4>
            <p>Normal: {{ report?.normalRevenue }}€</p>
            <p>VIP: {{ report?.vipRevenue }}€</p>
            <p class="font-bold mt-2">Total: {{ report?.totalRevenue }}€</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>