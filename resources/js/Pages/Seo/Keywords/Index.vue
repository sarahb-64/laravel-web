<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Gestión de Palabras Clave
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <!-- Your keywords management interface here -->
          <div v-if="keywords.data.length > 0">
            <table class="min-w-full divide-y divide-gray-200">
              <!-- Table headers -->
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Palabra Clave</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volumen</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dificultad</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
              </thead>
              <!-- Table body -->
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="keyword in keywords.data" :key="keyword.id">
                  <td class="px-6 py-4 whitespace-nowrap">{{ keyword.term }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ keyword.volume }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ keyword.difficulty }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <button class="text-indigo-600 hover:text-indigo-900">Ver detalles</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-center py-8">
            <p class="text-gray-500">No hay palabras clave registradas.</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { defineProps } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  keywords: {
    type: Object,
    required: true
  }
});
</script>