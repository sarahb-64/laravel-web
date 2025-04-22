<template>
    <div class="min-h-screen bg-gray-100">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Portafolio</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="project in projects" :key="project.id" 
                     class="group relative overflow-hidden rounded-lg bg-white shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="aspect-w-16 aspect-h-9 mb-4">
                            <img :src="project.image" :alt="project.name" class="object-cover rounded-lg">
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ project.name }}</h3>
                        <p class="text-gray-600 mb-4">{{ project.description }}</p>
                        <div class="flex items-center justify-between">
                            <Link :href="route('projects.show', project.id)" 
                                  class="inline-flex items-center text-blue-500 hover:text-blue-700 transition-colors duration-300">
                                Ver más
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </Link>
                            <div class="flex space-x-2">
                                <span v-for="tech in project.technologies" :key="tech" 
                                      class="px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full">
                                    {{ tech }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
    projects: Array
})
</script>