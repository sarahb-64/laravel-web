<template>
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
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-4 py-8 sm:p-12">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8">Nuestros Servicios</h1>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const open = ref(false)

const logout = () => {
    router.post(route('logout'))
}
</script>