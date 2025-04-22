<script setup>
import { ref } from 'vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import { Link as InertiaLink } from '@inertiajs/vue3'

const form = useForm({
    domain: '',
    type: 'dofollow',
    price: '',
    da: '',
    pa: '',
    traffic: '',
    language: '',
    description: ''
})

const submit = () => {
    form.post(route('seo.backlink-prices.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        },
        onError: () => {
            console.log('Error al enviar los datos')
        }
    })
}
</script>

<template>
    <Head title="Precios de Backlinks" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Agregar Precio de Backlink</h2>
                    
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="domain" value="Dominio" />
                            <TextInput
                                id="domain"
                                v-model="form.domain"
                                type="url"
                                class="mt-1 block w-full"
                                placeholder="[https://ejemplo.com](https://ejemplo.com)"
                                :class="{ 'border-red-500': form.errors.domain }"
                            />
                            <p v-if="form.errors.domain" class="mt-2 text-sm text-red-600">
                                {{ form.errors.domain }}
                            </p>
                        </div>

                        <div>
                            <InputLabel for="type" value="Tipo de Backlink" />
                            <SelectInput
                                id="type"
                                v-model="form.type"
                                class="mt-1 block w-full"
                                :class="{ 'border-red-500': form.errors.type }"
                            >
                                <option value="dofollow">Dofollow</option>
                                <option value="nofollow">Nofollow</option>
                            </SelectInput>
                            <p v-if="form.errors.type" class="mt-2 text-sm text-red-600">
                                {{ form.errors.type }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="price" value="Precio" />
                                <TextInput
                                    id="price"
                                    v-model="form.price"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full"
                                    :class="{ 'border-red-500': form.errors.price }"
                                />
                                <p v-if="form.errors.price" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.price }}
                                </p>
                            </div>

                            <div>
                                <InputLabel for="language" value="Idioma" />
                                <SelectInput
                                    id="language"
                                    v-model="form.language"
                                    class="mt-1 block w-full"
                                    :class="{ 'border-red-500': form.errors.language }"
                                >
                                    <option value="">Cualquiera</option>
                                    <option value="es">Español</option>
                                    <option value="en">Inglés</option>
                                    <option value="pt">Portugués</option>
                                </SelectInput>
                                <p v-if="form.errors.language" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.language }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <InputLabel for="da" value="DA (Domain Authority)" />
                                <TextInput
                                    id="da"
                                    v-model="form.da"
                                    type="number"
                                    min="0"
                                    max="100"
                                    class="mt-1 block w-full"
                                    :class="{ 'border-red-500': form.errors.da }"
                                />
                                <p v-if="form.errors.da" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.da }}
                                </p>
                            </div>

                            <div>
                                <InputLabel for="pa" value="PA (Page Authority)" />
                                <TextInput
                                    id="pa"
                                    v-model="form.pa"
                                    type="number"
                                    min="0"
                                    max="100"
                                    class="mt-1 block w-full"
                                    :class="{ 'border-red-500': form.errors.pa }"
                                />
                                <p v-if="form.errors.pa" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.pa }}
                                </p>
                            </div>

                            <div>
                                <InputLabel for="traffic" value="Tráfico Mensual" />
                                <TextInput
                                    id="traffic"
                                    v-model="form.traffic"
                                    type="number"
                                    class="mt-1 block w-full"
                                    :class="{ 'border-red-500': form.errors.traffic }"
                                />
                                <p v-if="form.errors.traffic" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.traffic }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <InputLabel for="description" value="Descripción" />
                            <textarea
                                id="description"
                                v-model="form.description"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="3"
                                :class="{ 'border-red-500': form.errors.description }"
                            ></textarea>
                            <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="form.processing">
                                <span v-show="!form.processing">Guardar</span>
                                <span v-show="form.processing">Guardando...</span>
                            </PrimaryButton>
                        </div>
                    </form>

                    <div class="mt-8">
                        <Link :href="route('seo.backlink-prices.compare')" class="text-blue-600 hover:text-blue-800">
                            Comparar Precios
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>