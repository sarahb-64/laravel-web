<template>
    <Head title="Bienvenido a APPYWEB" />
    
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="relative bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block">Bienvenido a</span>
                                <span class="block text-primary-600">APPYWEB</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                La plataforma de gestión de proyectos y tareas que te ayudará a organizar tu trabajo de manera eficiente.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    <Link :href="route('login')" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 md:py-4 md:text-lg md:px-10">
                                        Iniciar sesión
                                    </Link>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <Link :href="route('register')" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 md:py-4 md:text-lg md:px-10">
                                        Registrarse
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2102&q=80" alt="">
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-primary-600 font-semibold tracking-wide uppercase">Características</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Todo lo que necesitas para gestionar tus proyectos
                    </p>
                </div>

                <div class="mt-10">
                    <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                        <div v-for="feature in features" :key="feature.name" class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                                    <component :is="feature.icon" class="h-6 w-6" aria-hidden="true" />
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">{{ feature.name }}</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                {{ feature.description }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-primary-600 font-semibold tracking-wide uppercase">Testimonios</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Lo que dicen nuestros usuarios
                    </p>
                </div>

                <div class="mt-10">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <div v-for="testimonial in testimonials" :key="testimonial.name" class="bg-white p-6 rounded-lg shadow">
                            <blockquote class="text-gray-500">
                                <p class="text-lg">
                                    "{{ testimonial.quote }}"
                                </p>
                            </blockquote>
                            <footer class="mt-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full" :src="testimonial.image" :alt="testimonial.name">
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ testimonial.name }}</div>
                                        <div class="text-gray-500">{{ testimonial.role }}</div>
                                    </div>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Section -->
        <div class="bg-primary-600 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                        ¿Listo para empezar?
                    </h2>
                    <p class="mt-4 text-lg text-primary-100">
                        Regístrate ahora y comienza a gestionar tus proyectos de manera eficiente.
                    </p>
                    <div class="mt-8 flex justify-center">
                        <Link :href="route('register')" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-700 hover:bg-primary-800">
                            Comenzar ahora
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ChartBarIcon, ShieldCheckIcon, UsersIcon, GlobeAltIcon } from '@heroicons/vue/24/outline';

const features = [
    {
        name: 'Gestión de Proyectos',
        description: 'Crea, organiza y gestiona tus proyectos de manera eficiente con herramientas intuitivas.',
        icon: ChartBarIcon,
    },
    {
        name: 'Colaboración en Equipo',
        description: 'Trabaja en equipo con funcionalidades de asignación de tareas y seguimiento de progreso.',
        icon: UsersIcon,
    },
    {
        name: 'Seguridad',
        description: 'Tu información está protegida con medidas de seguridad de última generación.',
        icon: ShieldCheckIcon,
    },
    {
        name: 'Accesible desde cualquier lugar',
        description: 'Accede a tu información desde cualquier dispositivo con conexión a internet.',
        icon: GlobeAltIcon,
    },
];

const testimonials = [
    {
        name: 'Juan Pérez',
        role: 'Gerente de Proyectos',
        quote: 'APPYWEB ha revolucionado la forma en que gestionamos nuestros proyectos. La interfaz es intuitiva y las herramientas son muy potentes.',
        image: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    },
    {
        name: 'María García',
        role: 'Desarrolladora',
        quote: 'La colaboración en equipo nunca había sido tan fácil. Puedo ver el progreso de mis tareas en tiempo real y mantenerme organizada.',
        image: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    },
    {
        name: 'Carlos Rodríguez',
        role: 'Diseñador',
        quote: 'La plataforma es muy visual y fácil de usar. Me ayuda a mantenerme enfocado en mis tareas y entregar proyectos a tiempo.',
        image: 'https://images.unsplash.com/photo-1573497014508-93a7f4a1e7c5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    },
];
</script>
