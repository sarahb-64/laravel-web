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
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-4 py-8 sm:p-12">
                    <div class="flex items-center justify-between mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">Editar Proyecto</h1>
                        <div class="flex items-center gap-4">
                            <Link :href="route('projects.index')"
                                  class="text-blue-500 hover:text-blue-700 transition-colors duration-300">
                                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver
                            </Link>
                            <Link :href="route('portfolio.show', project.id)" 
                                  class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-300">
                                Ver en Portafolio
                            </Link>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" id="name" v-model="form.name"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   required>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea id="description" v-model="form.description"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                      required></textarea>
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Imagen</label>
                            <div class="mt-1 flex items-center">
                                <img v-if="project.image_url" :src="project.image_url" 
                                     class="w-32 h-32 object-cover rounded-lg mr-4">
                                <input type="file" id="image" @input="form.image = $event.target.files[0]"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700">Etiquetas</label>
                            <input type="text" id="tags" v-model="form.tags"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="Separadas por comas">
                        </div>

                        <div>
                            <label for="links" class="block text-sm font-medium text-gray-700">Enlaces</label>
                            <input type="text" id="links" v-model="form.links"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="Separados por comas">
                        </div>

                        <div>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-300">
                                Actualizar Proyecto
                            </button>
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
                    </form>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
    project: {
        type: Object,
        required: true
    }
})

const form = useForm({
    name: props.project.name,
    description: props.project.description,
    tags: props.project.tags ? props.project.tags.join(',') : '',
    links: props.project.links ? props.project.links.join(',') : '',
    image: null,
    skills: props.project.skills.map(skill => skill.id)
})

const submit = () => {
    form.put(route('projects.update', props.project.id), {
        preserveScroll: true,
        onSuccess: () => form.reset()
    })
}
</script>