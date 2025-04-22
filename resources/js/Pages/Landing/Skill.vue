<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <Link :href="route('home')" class="text-xl font-bold text-gray-800">
                                Mi Portafolio
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-4 py-8 sm:p-12">
                    <div class="flex items-center mb-8">
                        <Link :href="route('home')" class="text-blue-500 hover:text-blue-700">
                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver
                        </Link>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ skill.name }}</h1>
                    <div class="text-gray-600 mb-8">{{ skill.description }}</div>

                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div :style="{ width: skill.percentage + '%' }" 
                             class="bg-blue-500 h-2.5 rounded-full transition-all duration-300">
                        </div>
                    </div>

                    <div class="mt-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Proyectos relacionados</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div v-for="project in skill.projects" :key="project.id"
                                 class="group relative overflow-hidden rounded-lg bg-gray-50 p-6 hover:bg-gray-100 transition-all duration-300 shadow-sm hover:shadow-lg">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ project.name }}</h3>
                                    <p class="text-gray-600 mb-4">{{ project.description }}</p>
                                    <Link :href="route('portfolio.show', project.id)" 
                                          class="inline-flex items-center text-blue-500 hover:text-blue-700 transition-colors duration-300">
                                        Ver proyecto
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
    skill: {
        type: Object,
        required: true
    }
})
</script>