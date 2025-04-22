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
                        <Link :href="route('seo.dashboard')" class="px-3 py-2 text-gray-700 hover:text-gray-900">SEO Dashboard</Link>
                        <Link href="/seo/keywords/test" class="px-3 py-2 text-gray-700 hover:text-gray-900">Test Keywords</Link>
                        <Link :href="route('rank-tracker.index')" class="px-3 py-2 text-gray-700 hover:text-gray-900">Rank Tracker</Link>
                        <Link :href="route('seo.keywords.index')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">SEO Keywords</Link>
                       
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

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <slot />
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