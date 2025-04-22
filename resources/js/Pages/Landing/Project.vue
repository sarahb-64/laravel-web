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
                        <Link :href="route('portfolio')" class="text-blue-500 hover:text-blue-700">
                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver
                        </Link>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="relative">
                            <img :src="project.image_url" :alt="project.name" 
                                 class="w-full h-64 object-cover rounded-lg">
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent h-1/3"></div>
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ project.name }}</h1>
                            <p class="text-gray-600 mb-6">{{ project.description }}</p>

                            <div class="flex flex-wrap gap-2 mb-6">
                                <span v-for="tag in project.tags" :key="tag" 
                                      class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                                    {{ tag }}
                                </span>
                            </div>

                            <div class="flex gap-4">
                                <a v-for="link in project.links" :key="link.type" :href="link.url" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-300">
                                    {{ link.type }}
                                </a>
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
    project: {
        type: Object,
        required: true
    }
})
</script>