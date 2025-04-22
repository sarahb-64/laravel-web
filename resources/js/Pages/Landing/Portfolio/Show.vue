<template>
    <AppLayout title="Proyecto">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-8">
                    <Link :href="route('portfolio')" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver al Portfolio
                    </Link>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="aspect-w-16 aspect-h-9 mb-8">
                            <img :src="project.image" :alt="project.name" class="object-cover rounded-lg">
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ project.name }}</h1>
                        <p class="text-gray-600 mb-8">{{ project.description }}</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Tecnologías</h2>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="technology in project.technologies" :key="technology" 
                                        class="px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800">
                                        {{ technology }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Links</h2>
                                <ul class="space-y-2">
                                    <li v-for="link in project.links" :key="link.url">
                                        <a :href="link.url" target="_blank" 
                                            class="text-primary-600 hover:text-primary-800">
                                            {{ link.name }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    project: {
        type: Object,
        required: true
    }
});
</script>