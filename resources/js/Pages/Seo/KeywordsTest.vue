<template>
  <div>
    <h2 class="text-2xl font-bold mb-4">Buscar Palabras Clave (DataForSEO)</h2>
    <form @submit.prevent="fetchKeywords" class="mb-4">
      <input v-model="keyword" class="border px-2 py-1 mr-2" placeholder="Palabra clave" />
      <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">Buscar</button>
    </form>
    <div v-if="result">
      <pre>{{ result }}</pre>
    </div>
    <div v-if="error" class="text-red-600">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const keyword = ref('')
const result = ref(null)
const error = ref(null)

const fetchKeywords = () => {
  error.value = null
  result.value = null
  axios.post('/seo/keywords/dataforseo', { keywords: [keyword.value] })
    .then(res => result.value = res.data)
    .catch(err => error.value = err.response?.data?.error || 'Error desconocido')
}
</script>