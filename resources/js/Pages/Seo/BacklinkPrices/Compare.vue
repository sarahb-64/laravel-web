<!-- resources/js/Pages/Seo/BacklinkPrices/Compare.vue -->
<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    prices: {
        type: Array,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

const form = useForm({
    type: props.filters.type || 'dofollow',
    min_price: props.filters.min_price || '',
    max_price: props.filters.max_price || '',
    min_da: props.filters.min_da || '',
    min_pa: props.filters.min_pa || '',
    min_traffic: props.filters.min_traffic || '',
    language: props.filters.language || ''
})

const submit = () => {
    form.get(route('seo.backlink-prices.compare'), {
        preserveScroll: true,
        preserveState: true
    })
}
</script>

<template>
    <Head title="Comparar Precios de Backlinks" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Comparar Precios de Backlinks</h2>

                    <!-- Filtros -->
                    <form @submit.prevent="submit" class="mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                            </div>

                            <div>
                                <InputLabel for="min_price" value="Precio Mínimo" />
                                <TextInput
                                    id="min_price"
                                    v-model="form.min_price"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full"
                                    :class="{ 'border-red-500': form.errors.min_price }"
                                />
                            </div>

                            <div>
                                <InputLabel for="max_price" value="Precio Máximo" />
                                <TextInput
                                    id="max_price"
                                    v-model="form.max_price"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full"
                                    :class="{ 'border-red-500': form.errors.max_price }"
                                />
                            </div>

                            <div>
                                <InputLabel for="min_da" value="DA Mínimo" />
                                <TextInput
                                    id="min_da"
                                    v-model="form.min_da"
                                    type="number"
                                    min="0"
                                    max="100"
                                    class="mt-1 block w-full"
                                    :class="{ 'border-red-500': form.errors.min_da }"
                                />
                            </div>

                            <div>
                                <InputLabel for="min_pa" value="PA Mínimo" />
                                <TextInput
                                    id="min_pa"
                                    v-model="form.min_pa"
                                    type="number"
                                    min="0"
                                    max="100"
                                    class="mt-1 block w-full"
                                    :class="{ 'border-red-500': form.errors.min_pa }"
                                />
                            </div>

                            <div>
                                <InputLabel for="min_traffic" value="Tráfico Mínimo" />
                                <TextInput
                                    id="min_traffic"
                                    v-model="form.min_traffic"
                                    type="number"
                                    class="mt-1 block w-full"
                                    :class="{ 'border-red-500': form.errors.min_traffic }"
                                />
                            </div>

                            <div class="col-span-2">
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
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <PrimaryButton :disabled="form.processing">
                                <span v-show="!form.processing">Filtrar</span>
                                <span v-show="form.processing">Filtrando...</span>
                            </PrimaryButton>
                        </div>
                    </form>

                    <!-- Tabla de resultados -->
                    <div v-if="prices.length > 0" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dominio
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Precio
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        DA
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        PA
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tráfico
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Idioma
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Descripción
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="price in prices" :key="price.id" :class="price.type === 'dofollow' ? 'bg-green-50' : 'bg-red-50'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a :href="price.domain" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            {{ price.domain }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="price.type === 'dofollow' 
                                            ? 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800' 
                                            : 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800'">
                                            {{ price.type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        ${{ price.price }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ price.da }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ price.pa }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ price.traffic }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ price.language }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ price.description }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mensaje cuando no hay resultados -->
                    <div v-else class="text-center py-8">
                        <p class="text-gray-500">No se encontraron resultados que coincidan con los filtros seleccionados.</p>
                    </div>

                    <!-- Enlace para agregar nuevo precio -->
                    <div class="mt-8">
                        <Link :href="route('seo.backlink-prices.index')" class="text-blue-600 hover:text-blue-800">
                            Agregar Nuevo Precio
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>