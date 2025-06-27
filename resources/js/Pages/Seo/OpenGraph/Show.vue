<template>
  <AppLayout :title="project?.name || 'Análisis de Open Graph'">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ project?.name || 'Análisis de Open Graph' }}
        </h2>
        <div v-if="project">
          <Link 
            :href="route('seo.open-graph.index')" 
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition"
          >
            Volver a la lista
          </Link>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <!-- URL Input -->
          <div class="mb-8">
            <label for="url" class="block text-sm font-medium text-gray-700 mb-2">URL a analizar</label>
            <div class="flex space-x-4">
              <div class="flex-1">
                <input
                  v-model="analysisUrl"
                  type="url"
                  id="url"
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="https://ejemplo.com"
                />
              </div>
              <button
                @click="analyzeUrl"
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
          </div>

          <!-- Preview Card -->
          <div v-if="result" class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Vista previa en redes sociales</h3>
            <div class="border rounded-lg overflow-hidden max-w-2xl">
              <div v-if="result.current['og:image']" class="h-48 bg-gray-100 overflow-hidden">
                <img 
                  :src="result.current['og:image']" 
                  alt="Preview image"
                  class="w-full h-full object-cover"
                />
              </div>
              <div v-else class="h-48 bg-gray-100 flex items-center justify-center">
                <span class="text-gray-400">Sin imagen de vista previa</span>
              </div>
              <div class="p-4">
                <p v-if="result.current['og:type']" class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                  {{ result.current['og:type'] }}
                </p>
                <h4 class="text-lg font-medium text-gray-900 mb-1 line-clamp-2">
                  {{ result.current['og:title'] || 'Sin título' }}
                </h4>
                <p class="text-sm text-gray-500 line-clamp-2">
                  {{ result.current['og:description'] || 'Sin descripción' }}
                </p>
                <p class="text-xs text-gray-400 mt-2 truncate">
                  {{ result.url }}
                </p>
              </div>
            </div>
          </div>

          <!-- Current Tags -->
          <div v-if="result" class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Etiquetas Open Graph actuales</h3>
            <div class="bg-gray-50 p-4 rounded-lg overflow-hidden">
              <pre class="text-xs text-gray-800 overflow-x-auto"><code v-html="formattedCurrentTags"></code></pre>
            </div>
          </div>

          <!-- Suggested Tags -->
          <div v-if="result?.suggested">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Sugerencias de etiquetas Open Graph</h3>
            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título (og:title)</label>
                <div class="flex items-center space-x-2">
                  <input
                    v-model="result.suggested.title"
                    type="text"
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  />
                  <span class="text-xs text-gray-500">{{ result.suggested.title?.length || 0 }}/60</span>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción (og:description)</label>
                <div class="flex flex-col space-y-2">
                  <textarea
                    v-model="result.suggested.description"
                    rows="3"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  ></textarea>
                  <div class="flex justify-between">
                    <span class="text-xs text-gray-500">{{ result.suggested.description?.length || 0 }}/200</span>
                    <span class="text-xs text-gray-500">{{ result.suggested.image_recommendation }}</span>
                  </div>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo (og:type)</label>
                <select 
                  v-model="result.suggested.type"
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                >
                  <option value="website">Website</option>
                  <option value="article">Article</option>
                  <option value="book">Book</option>
                  <option value="profile">Profile</option>
                  <option value="music.song">Music - Song</option>
                  <option value="music.album">Music - Album</option>
                  <option value="music.playlist">Music - Playlist</option>
                  <option value="music.radio_station">Music - Radio Station</option>
                  <option value="video.movie">Video - Movie</option>
                  <option value="video.episode">Video - Episode</option>
                  <option value="video.tv_show">Video - TV Show</option>
                  <option value="video.other">Video - Other</option>
                </select>
              </div>

              <div class="pt-4 border-t">
                <h4 class="text-md font-medium text-gray-900 mb-3">Código HTML generado</h4>
                <div class="bg-gray-800 rounded-lg p-4">
                  <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-mono text-gray-400">Open Graph Meta Tags</span>
                    <button 
                      @click="copyToClipboard"
                      class="text-xs text-indigo-400 hover:text-indigo-300"
                    >
                      Copiar al portapapeles
                    </button>
                  </div>
                  <pre class="text-xs text-green-400 overflow-x-auto"><code ref="codeBlock">{{ generatedCode }}</code></pre>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CheckIcon, DocumentDuplicateIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  project: {
    type: Object,
    default: null
  },
  result: {
    type: Object,
    default: null
  },
  analysis: {
    type: Object,
    default: null
  }
});

const analysisUrl = ref(props.project?.url || '');
const loading = ref(false);
const error = ref('');
const result = ref(props.result || props.analysis || null);
const codeBlock = ref(null);
const copied = ref(false);

// Set initial URL from project or route query
onMounted(() => {
  if (props.analysis) {
    result.value = props.analysis;
    analysisUrl.value = props.analysis.url;
  }
});

const analyzeUrl = async () => {
  if (!analysisUrl.value) return;
  
  try {
    loading.value = true;
    error.value = '';
    
    const response = await axios.post(route('seo.open-graph.analyze-url'), {
      url: analysisUrl.value
    });
    
    result.value = response.data;
  } catch (e) {
    console.error('Error analyzing URL:', e);
    error.value = e.response?.data?.message || 'Ocurrió un error al analizar la URL. Por favor, inténtalo de nuevo.';
  } finally {
    loading.value = false;
  }
};

const formattedCurrentTags = computed(() => {
  if (!result.value?.current) return '';
  
  const tags = [];
  for (const [key, value] of Object.entries(result.value.current)) {
    if (value) {
      tags.push(`&lt;meta property="og:${key}" content="${value}" /&gt;`);
    }
  }
  
  return tags.join('\n');
});

const generatedCode = computed(() => {
  if (!result.value?.suggested) return '';
  
  const { title, description, type } = result.value.suggested;
  const url = result.value.url;
  
  return `<!-- Open Graph Meta Tags -->
<meta property="og:title" content="${title}" />
<meta property="og:description" content="${description}" />
<meta property="og:type" content="${type}" />
<meta property="og:url" content="${url}" />
<meta property="og:site_name" content="${document.title}" />
<!-- Agrega la etiqueta og:image con la URL de tu imagen -->
<!-- <meta property="og:image" content="https://ejemplo.com/imagen.jpg" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:image:alt" content="Descripción de la imagen" /> -->`;
});

const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(codeBlock.value.textContent);
    copied.value = true;
    setTimeout(() => {
      copied.value = false;
    }, 2000);
  } catch (err) {
    console.error('Failed to copy text: ', err);
  }
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
