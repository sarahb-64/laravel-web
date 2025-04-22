<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-100">
            <!-- Navigation -->
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

                        <div class="flex items-center">
                            <Link :href="route('projects.index')" class="px-3 py-2 text-gray-700 hover:text-gray-900">Mis Proyectos</Link>
                            <Link :href="route('services')" class="px-3 py-2 text-gray-700 hover:text-gray-900">Servicios</Link>
                            <Link :href="route('portfolio')" class="px-3 py-2 text-gray-700 hover:text-gray-900">Portafolio</Link>
                            <Link :href="route('contact')" class="px-3 py-2 text-gray-700 hover:text-gray-900">Contacto</Link>

                            <!-- Authenticated -->
                            <template v-if="$page.props.auth.user">
                                <div class="ml-3 relative">
                                    <div>
                                        <button @click="open = !open" type="button" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <img class="h-8 w-8 rounded-full" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                        </button>
                                    </div>

                                    <div v-show="open" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                        <Link :href="route('profile.show')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-0">
                                            Perfil
                                        </Link>
                                        <Link :href="route('projects.index')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-1">
                                            Mis Proyectos
                                        </Link>
                                        <form @submit.prevent="logout" class="w-full">
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-2">
                                                Cerrar Sesión
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </template>

                            <!-- Guest -->
                            <template v-else>
                                <Link :href="route('login')" class="px-3 py-2 text-gray-700 hover:text-gray-900">Iniciar Sesión</Link>
                                <Link :href="route('register')" class="px-3 py-2 text-gray-700 hover:text-gray-900">Registrarse</Link>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Hero Section -->
                <div class="relative overflow-hidden bg-white rounded-lg shadow-xl mb-12">
                    <div class="px-4 py-12 sm:p-16">
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            Hola, soy Sarah
                        </h1>
                        <p class="text-xl text-gray-600 mb-8">
                            Desarrolladora web apasionada por crear experiencias digitales únicas
                        </p>
                        <div class="flex space-x-4">
                            <Link :href="route('contact')" 
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-300">
                                Contactame
                            </Link>
                            <Link :href="route('portfolio')" 
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-300">
                                Ver Portafolio
                            </Link>
                        </div>
                    </div>
                    <div class="absolute -bottom-12 right-0 w-48 h-48 bg-blue-50 rounded-full blur-3xl"></div>
                </div>

                <!-- Skills Section -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-12">
                    <div class="px-4 py-8 sm:p-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-8">Mis Habilidades</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <Link v-for="skill in skills" :key="skill.id" 
                                :href="route('skills.show', skill.id)" 
                                class="group relative overflow-hidden rounded-lg bg-gray-50 p-6 hover:bg-gray-100 transition-all duration-300 shadow-sm hover:shadow-lg">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative">
                                    <div class="text-2xl text-blue-500 mb-3">{{ skill.icon }}</div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ skill.name }}</h3>
                                    <p class="text-gray-600 mb-4">{{ skill.description }}</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div :style="{ width: skill.percentage + '%' }" 
                                            class="bg-blue-500 h-2.5 rounded-full transition-all duration-300"></div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Services Section -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-12">
                    <div class="px-4 py-8 sm:p-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-8">Mis Servicios</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <Link v-for="service in services" :key="service.id" 
                                :href="route('services.show', service.id)" 
                                class="group relative overflow-hidden rounded-lg bg-gray-50 p-6 hover:bg-gray-100 transition-all duration-300 shadow-sm hover:shadow-lg">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative">
                                    <div class="text-3xl text-blue-500 mb-4">{{ service.icon }}</div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ service.name }}</h3>
                                    <p class="text-gray-600 mb-4">{{ service.description }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">{{ service.price }}</span>
                                        <div class="flex items-center">
                                            Ver más
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Projects Section -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-8 sm:p-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-8">Proyectos Recientes</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div v-for="project in projects" :key="project.id" 
                                class="group relative overflow-hidden rounded-lg bg-gray-50 p-6 hover:bg-gray-100 transition-all duration-300 shadow-sm hover:shadow-lg cursor-pointer">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative">
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
                                        <span class="text-sm text-gray-500">{{ project.created_at }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>