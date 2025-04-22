<template>
  <div>
    <h1>Rank Tracker</h1>

    <div class="mb-4">
      <h2>Agregar Nuevas Keywords</h2>
      <form @submit.prevent="addKeywords" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Keywords</label>
          <textarea v-model="form.keywords" class="mt-1 block w-full"></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium">Dominio</label>
          <input v-model="form.domain" class="mt-1 block w-full">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2">Consultar Posiciones</button>
      </form>
    </div>

    <div v-if="loading" class="text-center">Cargando...</div>
    <div v-if="error" class="text-red-600">{{ error }}</div>
    <div v-if="results">
      <pre>{{ results }}</pre>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  keywords: '',
  domain: ''
})

const loading = ref(false)
const error = ref(null)
const results = ref(null)

const addKeywords = async () => {
  loading.value = true
  error.value = null
  results.value = null

  try {
    const response = await form.post(route('rank-tracker.store'))
    results.value = response.data.saved
  } catch (err) {
    error.value = err.response?.data?.message || 'Error al consultar la API'
  } finally {
    loading.value = false
  }
}
</script>