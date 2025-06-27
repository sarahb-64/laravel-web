<template>
  <AppLayout title="Generador de Open Graph">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Generador de Etiquetas Open Graph
        </h2>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Analizar un proyecto existente</h3>
            <div v-if="projects.length > 0" class="space-y-4">
              <div v-for="project in projects" :key="project.id" class="border rounded-lg p-4 hover:bg-gray-50">
                <Link 
                  :href="route('seo.open-graph.show', project)" 
                  class="flex justify-between items-center"
                >
                  <div>
                    <p class="font-medium text-indigo-600">{{ project.name }}</p>
                    <p class="text-sm text-gray-500">{{ project.url }}</p>
                  </div>
                  <ChevronRightIcon class="h-5 w-5 text-gray-400" />
                </Link>
              </div>
            </div>
            <p v-else class="text-gray-500">
              No tienes proyectos con URLs configuradas. 
              <Link :href="route('seo.projects.create')" class="text-indigo-600 hover:text-indigo-500">
                Crea un proyecto
              </Link> 
              para comenzar.
            </p>
          </div>

          <div class="border-t pt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">O analiza cualquier URL</h3>
            <form @submit.prevent="analyzeUrl" class="space-y-4">
              <div class="flex items-center space-x-4">
                <div class="flex-1">
                  <input
                    v-model="url"
                    type="url"
                    required
                    placeholder="https://ejemplo.com"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  />
                </div>
                <button
                  type="submit"
                  :disabled="loading"
                  class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="!loading">Analizar</span>
                  <svg v-else class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </button>
              </div>
              <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ChevronRightIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  projects: {
    type: Array,
    required: true
  }
});

const url = ref('');
const loading = ref(false);
const error = ref('');

const analyzeUrl = async () => {
  if (!url.value) return;
  
  try {
    loading.value = true;
    error.value = '';
    
    const response = await axios.post(route('seo.open-graph.analyze-url'), {
      url: url.value
    });
    
    // Redirect to the show page with the analysis result
    router.visit(route('seo.open-graph.show', { project: 'custom' }), {
      method: 'get',
      data: {
        analysis: response.data
      },
      preserveState: true,
      replace: true
    });
  } catch (e) {
    console.error('Error analyzing URL:', e);
    error.value = e.response?.data?.message || 'Ocurrió un error al analizar la URL. Por favor, inténtalo de nuevo.';
  } finally {
    loading.value = false;
  }
};
</script>
