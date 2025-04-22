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
                        <Link :href="route('projects.index')" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Volver
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Crear Nuevo Proyecto</h1>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nombre
                            </label>
                            <input type="text" id="name" v-model="form.name" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Descripción
                            </label>
                            <textarea id="description" v-model="form.description" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      required></textarea>
                        </div>

                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700">
                                Tags (separados por comas)
                            </label>
                            <input type="text" id="tags" v-model="form.tags" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="links" class="block text-sm font-medium text-gray-700">
                                Enlaces (separados por comas)
                            </label>
                            <input type="text" id="links" v-model="form.links" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">
                                Imagen
                            </label>
                            <input type="file" id="image" @input="form.image = $event.target.files[0]"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="skills" class="block text-sm font-medium text-gray-700">
                                Habilidades
                            </label>
                            <select id="skills" v-model="form.skills" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    multiple>
                                <option v-for="skill in skills" :key="skill.id" :value="skill.id">
                                    {{ skill.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.skills" class="text-sm text-red-600 mt-1">
                                {{ form.errors.skills }}
                            </div>
                        </div>

                        <div>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Crear Proyecto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    description: '',
    tags: '',
    links: '',
    image: null,
    skills: []
})

const submit = () => {
    form.post(route('projects.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset()
    })
}
</script>