<template>
    <AppLayout title="Dashboard">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Projects Card -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">Proyectos</h3>
                                <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Nuevo Proyecto
                                </a>
                            </div>
                            
                            <div class="mt-4 space-y-4">
                                <div v-for="project in projects" :key="project.id" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ project.name }}</h4>
                                        <p class="text-sm text-gray-500">{{ project.description }}</p>
                                    </div>
                                    <a href="#" class="text-primary-600 hover:text-primary-900">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks Card -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Tareas Pendientes</h3>
                            <div class="mt-4 space-y-4">
                                <div v-for="task in tasks" :key="task.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ task.title }}</h4>
                                        <p class="text-sm text-gray-500">{{ task.project.name }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span :class="task.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : task.status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'" class="px-2 py-1 rounded-full text-xs font-medium">
                                            {{ task.status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Card -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Actividad Reciente</h3>
                            <div class="mt-4 space-y-4">
                                <div v-for="activity in activities" :key="activity.id" class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ activity.description }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ activity.created_at }}
                                        </p>
                                    </div>
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
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Mock data - Replace with actual API calls
const projects = [
    {
        id: 1,
        name: 'Proyecto 1',
        description: 'Descripción del proyecto 1'
    },
    {
        id: 2,
        name: 'Proyecto 2',
        description: 'Descripción del proyecto 2'
    }
];

const tasks = [
    {
        id: 1,
        title: 'Tarea 1',
        project: { id: 1, name: 'Proyecto 1' },
        status: 'pending'
    },
    {
        id: 2,
        title: 'Tarea 2',
        project: { id: 2, name: 'Proyecto 2' },
        status: 'in_progress'
    }
];

const activities = [
    {
        id: 1,
        description: 'Actividad 1 realizada',
        created_at: 'Hace 1 hora'
    },
    {
        id: 2,
        description: 'Actividad 2 realizada',
        created_at: 'Hace 2 horas'
    }
];
</script>