<template>
  <div>
    <h1>Administrar Keywords</h1>

    <div class="mt-6">
      <form @submit.prevent="submitKeywords" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Keywords (una por línea)</label>
            <textarea 
              v-model="form.keywords" 
              rows="4" 
              class="w-full p-2 border rounded"
              placeholder="Ejemplo: seo, marketing digital, posicionamiento web"
            ></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Dominio</label>
            <input 
              v-model="form.domain" 
              type="url" 
              class="w-full p-2 border rounded"
              placeholder="[https://ejemplo.com](https://ejemplo.com)"
            >
          </div>
        </div>
        <button 
          type="submit" 
          class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          :disabled="loading"
        >
          {{ loading ? 'Procesando...' : 'Consultar Datos' }}
        </button>
      </form>
    </div>

    <div v-if="message" class="bg-green-100 text-green-800 p-4 rounded mb-4">
      {{ message }}
    </div>

    <div v-if="keywords.length > 0" class="overflow-x-auto">
      <table class="min-w-full bg-white">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keyword</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volumen</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Competencia</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPC</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posición Actual</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="keyword in keywords" :key="keyword.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ keyword.keyword }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ keyword.search_volume }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ keyword.competition }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ keyword.cpc }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              {{ keyword.rankResults[0]?.position || 'N/A' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <Link 
                :href="route('seo.keywords.show', keyword.id)"
                class="text-indigo-600 hover:text-indigo-900 mr-4"
              >
                Ver detalles
              </Link>
              <button 
                @click="deleteKeyword(keyword.id)"
                class="text-red-600 hover:text-red-900"
              >
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="keywords.length === 0" class="text-center py-8">
      No hay keywords registradas
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  keywords: Array,
  message: String
})

const form = useForm({
  keywords: '',
  domain: ''
})

const loading = ref(false)

const submitKeywords = async () => {
  loading.value = true
  try {
    await form.post(route('seo.keywords.store'), {
      onSuccess: () => form.reset()
    })
  } finally {
    loading.value = false
  }
}

const deleteKeyword = async (id) => {
  if (confirm('¿Estás seguro de que quieres eliminar esta keyword?')) {
    await form.delete(route('seo.keywords.destroy', id))
  }
}
</script>