<template>
  <div>
    <h1>Detalles de Keyword: {{ keyword.keyword }}</h1>

    <div class="mt-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div>
          <h3 class="text-lg font-medium mb-2">Información General</h3>
          <div class="space-y-2">
            <div>
              <span class="font-medium">Volumen de Búsqueda:</span>
              {{ keyword.search_volume }}
            </div>
            <div>
              <span class="font-medium">Competencia:</span>
              {{ keyword.competition }}
            </div>
            <div>
              <span class="font-medium">CPC:</span>
              {{ keyword.cpc }}
            </div>
          </div>
        </div>
        <div>
          <h3 class="text-lg font-medium mb-2">Información del Dominio</h3>
          <div class="space-y-2">
            <div>
              <span class="font-medium">Dominio:</span>
              {{ keyword.domain }}
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium mb-4">Historial de Posiciones</h3>
        <div v-if="keyword.rankResults.length > 0">
          <div class="overflow-x-auto">
            <table class="min-w-full">
              <thead>
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posición</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor de Búsqueda</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="result in keyword.rankResults" :key="result.id">
                  <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(result.created_at) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ result.position }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ result.search_engine }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ result.location }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-else class="text-center py-8">
          No hay datos de posiciones disponibles
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  keyword: Object
})

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>