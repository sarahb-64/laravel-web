@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">¡Error!</strong>
        <span class="block sm:inline">Por favor, corrige los siguientes errores:</span>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 gap-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Proyecto</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('name', $project->name ?? '') }}" required>
    </div>

    <div>
        <label for="url" class="block text-sm font-medium text-gray-700">URL del Sitio Web</label>
        <input type="url" name="url" id="url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('url', $project->url ?? '') }}" placeholder="https://ejemplo.com" required>
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('description', $project->description ?? '') }}</textarea>
    </div>

    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
        <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('start_date', isset($project->start_date) ? $project->start_date->format('Y-m-d') : '') }}" required>
    </div>

    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha de Finalización (Opcional)</label>
        <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('end_date', isset($project->end_date) ? $project->end_date->format('Y-m-d') : '') }}">
    </div>

    <div>
        <label for="analytics_property_id" class="block text-sm font-medium text-gray-700">ID de Propiedad de Google Analytics (GA4) (Opcional)</label>
        <input type="text" name="analytics_property_id" id="analytics_property_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('analytics_property_id', $project->analytics_property_id ?? '') }}" placeholder="G-XXXXXXXXXX">
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            <option value="active" {{ (old('status', $project->status ?? '') == 'active') ? 'selected' : '' }}>Activo</option>
            <option value="in_progress" {{ (old('status', $project->status ?? '') == 'in_progress') ? 'selected' : '' }}>En Progreso</option>
            <option value="completed" {{ (old('status', $project->status ?? '') == 'completed') ? 'selected' : '' }}>Completado</option>
        </select>
    </div>
</div>
