<template>
    <AppLayout title="Portfolio">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Mi Portfolio</h1>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="project in projects" :key="project.id" class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="aspect-w-16 aspect-h-9 mb-4">
                                <img :src="project.image" :alt="project.name" class="object-cover rounded-lg">
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ project.name }}</h2>
                            <p class="text-gray-600 mb-4">{{ project.description }}</p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span v-for="technology in project.technologies" :key="technology" 
                                    class="px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800">
                                    {{ technology }}
                                </span>
                            </div>
                            <Link :href="route('portfolio.show', project.id)" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Ver Proyecto
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
defineProps({
  title: String
});

import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>