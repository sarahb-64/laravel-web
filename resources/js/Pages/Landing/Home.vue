<template>
    <AppLayout>
      <!-- Hero Section - Más compacto -->
      <div class="py-12 sm:py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
            Raúl Aránega
          </h1>
          <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
            Desarrollador web especializado en soluciones digitales efectivas
          </p>
          <div class="flex flex-col sm:flex-row justify-center gap-4">
            <Link :href="route('contact')" 
                class="px-5 py-2.5 text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 transition-colors">
                Contacto
            </Link>
            <Link :href="route('portfolio')" 
                class="px-5 py-2.5 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                Portafolio
            </Link>
          </div>
        </div>
      </div>
  
      <!-- Skills Section - Más simple -->
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-8 text-center">Habilidades</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div v-for="skill in skills" :key="skill.id" 
              class="p-4 border border-gray-100 rounded-lg hover:shadow-sm transition-shadow">
            <div class="text-blue-500 text-2xl mb-2">{{ skill.icon }}</div>
            <h3 class="font-medium text-gray-900">{{ skill.name }}</h3>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
              <div :style="{ width: skill.percentage + '%' }" 
                  class="bg-blue-500 h-1.5 rounded-full"></div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Projects Preview - Versión más simple -->
      <div class="bg-gray-50 py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-8">
            <h2 class="text-2xl font-semibold text-gray-900">Proyectos Recientes</h2>
            <p class="mt-2 text-gray-600 max-w-2xl mx-auto">
              Algunos de mis trabajos más recientes
            </p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="project in projects" :key="project.id" 
                class="bg-white rounded-lg overflow-hidden border border-gray-100 hover:shadow-sm transition-shadow">
              <div class="p-5">
                <h3 class="font-medium text-gray-900 mb-2">{{ project.name }}</h3>
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ project.description }}</p>
                <Link :href="route('projects.show', project.id)" 
                    class="text-sm font-medium text-blue-600 hover:text-blue-500 inline-flex items-center">
                  Ver proyecto
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                  </svg>
                </Link>
              </div>
            </div>
          </div>
          <div class="mt-8 text-center">
            <Link :href="route('portfolio')" 
                class="text-sm font-medium text-gray-600 hover:text-gray-900 inline-flex items-center">
              Ver todos los proyectos
              <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </Link>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { Head, Link } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  
  // Datos de ejemplo - reemplazar con datos reales
  const skills = [
    { id: 1, name: 'Laravel', icon: '🧑‍💻', percentage: 90 },
    { id: 2, name: 'Vue.js', icon: '🖥️', percentage: 85 },
    { id: 3, name: 'Diseño UI/UX', icon: '🎨', percentage: 75 },
    { id: 4, name: 'SEO', icon: '🔍', percentage: 80 }
  ];
  
  const projects = [
    { 
      id: 1, 
      name: 'Sistema de Gestión', 
      description: 'Plataforma integral para gestión empresarial con panel de administración y reportes en tiempo real.' 
    },
    { 
      id: 2, 
      name: 'E-commerce', 
      description: 'Tienda en línea con pasarela de pago y sistema de gestión de inventario.' 
    }
  ];
  </script>