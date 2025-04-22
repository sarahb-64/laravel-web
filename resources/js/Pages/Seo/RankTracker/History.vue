<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Historial de Posiciones SEO</h1>

    <div class="mb-6">
      <div class="flex gap-4">
        <input 
          v-model="filters.keyword" 
          type="text" 
          placeholder="Filtrar por keyword..." 
          class="px-4 py-2 border rounded"
        />
        <input 
          v-model="filters.domain" 
          type="text" 
          placeholder="Filtrar por dominio..." 
          class="px-4 py-2 border rounded"
        />
        <button 
          @click="applyFilters" 
          class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Filtrar
        </button>
      </div>
    </div>

    <div v-if="loading" class="text-center py-8">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
    </div>

    <div v-else-if="error" class="text-red-600 text-center py-8">
      {{ error }}
    </div>

    <div v-else-if="results.length === 0" class="text-center py-8">
      No se encontraron resultados
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="keyword in uniqueKeywords" 
        :key="keyword" 
        class="bg-white rounded-lg shadow p-6"
      >
        <h3 class="text-xl font-semibold mb-4">{{ keyword }}</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead>
              <tr>
                <th class="px-4 py-2">Fecha</th>
                <th class="px-4 py-2">Posición</th>
                <th class="px-4 py-2">Dominio</th>
                <th class="px-4 py-2">Volumen</th>
                <th class="px-4 py-2">Competencia</th>
                <th class="px-4 py-2">CPC</th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="result in filteredResults(keyword)" 
                :key="result.id"
                :class="{
                  'bg-green-50': result.position < 10,
                  'bg-yellow-50': result.position >= 10 && result.position < 30,
                  'bg-red-50': result.position >= 30
                }"
              >
                <td class="px-4 py-2">{{ formatDate(result.created_at) }}</td>
                <td class="px-4 py-2">{{ result.position }}</td>
                <td class="px-4 py-2">{{ result.domain }}</td>
                <td class="px-4 py-2">{{ result.search_volume }}</td>
                <td class="px-4 py-2">{{ result.competition }}</td>
                <td class="px-4 py-2">{{ result.cpc }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { formatDate } from '@/Composables/useDate'

const props = defineProps({
  results: Array
})

const filters = ref({
  keyword: '',
  domain: ''
})

const loading = ref(false)
const error = ref(null)

const applyFilters = async () => {
  try {
    loading.value = true
    const response = await axios.get(route('rank-tracker.history'), {
      params: filters.value
    })
    props.results = response.data
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al cargar datos'
  } finally {
    loading.value = false
  }
}

const uniqueKeywords = computed(() => {
  return [...new Set(props.results.map(r => r.keyword.keyword))]
})

const filteredResults = (keyword) => {
  return props.results
    .filter(r => r.keyword.keyword === keyword)
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
}
</script>