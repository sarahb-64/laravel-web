<template>
    <AppLayout title="Dashboard">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Projects Section -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">Proyectos</h3>
                                <Link :href="route('projects.create')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Nuevo Proyecto
                                </Link>
                            </div>
                            
                            <div class="mt-4 space-y-4">
                                <div v-for="project in $page.props.projects" :key="project.id" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ project.name }}</h4>
                                        <p class="text-sm text-gray-500">{{ project.description }}</p>
                                    </div>
                                    <Link :href="route('projects.show', project.id)" class="text-primary-600 hover:text-primary-900">
                                        Ver detalles
                                    </Link>
                                </div>
                                <div v-if="$page.props.projects.length === 0" class="text-center py-4 text-gray-500">
                                    No hay proyectos aún
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks Section -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Pending Tasks</h3>
                            <div class="mt-4 space-y-4">
                                <div v-for="task in $page.props.tasks" :key="task.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ task.title }}</h4>
                                        <p class="text-sm text-gray-500">{{ task.project.name }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span :class="{
                                            'bg-yellow-100 text-yellow-800': task.status === 'pending',
                                            'bg-blue-100 text-blue-800': task.status === 'in_progress',
                                            'bg-green-100 text-green-800': task.status === 'completed'
                                        }" class="px-2 py-1 rounded-full text-xs font-medium">
                                            {{ task.status === 'pending' ? 'Pending' : task.status === 'in_progress' ? 'In Progress' : 'Completed' }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="$page.props.tasks.length === 0" class="text-center py-4 text-gray-500">
                                    No hay tarea pendiente
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Section -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                            <div class="mt-4 space-y-4">
                                <div v-for="activity in $page.props.activities" :key="activity.id" class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ activity.description }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ activity.created_at }}
                                        </p>
                                    </div>
                                </div>
                                <div v-if="$page.props.activities.length === 0" class="text-center py-4 text-gray-500">
                                    No hay actividad reciente
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
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>