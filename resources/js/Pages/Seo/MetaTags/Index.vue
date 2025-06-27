<template>
    <AppLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Analizador de Meta Tags
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Selecciona un proyecto para analizar</h3>
            
            <div v-if="projects.length > 0" class="space-y-4">
              <div v-for="project in projects" :key="project.id" 
                   class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer transition-colors duration-200"
                   @click="selectProject(project)">
                <div class="font-medium text-gray-900">{{ project.name }}</div>
                <div class="text-sm text-gray-500">{{ project.url }}</div>
              </div>
            </div>
            
            <div v-else class="text-center py-8">
              <p class="text-gray-500 mb-4">No hay proyectos disponibles para analizar.</p>
              <p class="text-sm text-gray-500">Asegúrate de tener al menos un proyecto con una URL configurada.</p>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  import { router } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  
  const props = defineProps({
    projects: {
      type: Array,
      required: true,
      default: () => []
    }
  });
  
  const selectProject = (project) => {
    if (project?.id) {
      router.visit(route('seo.meta-tags.show', project.id));
    }
  };
  </script>