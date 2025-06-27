<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Análisis de Tráfico Web') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if(!$configExists)
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Configuración Incompleta</p>
                            <p>Para usar esta herramienta, necesitas el archivo de credenciales de Google `service-account-credentials.json` en la carpeta `storage/app/analytics/`.</p>
                        </div>
                    @elseif($projects->isEmpty())
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                            <p class="font-bold">No hay proyectos para analizar</p>
                            <p>No tienes proyectos con un ID de Propiedad de Google Analytics (GA4) configurado. <a href="{{ route('projects.create') }}" class="font-bold underline">Crea un proyecto nuevo</a> o edita uno existente para añadirlo.</p>
                        </div>
                    @else
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Selecciona un proyecto para analizar:</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($projects as $project)
                                <a href="{{ route('tools.web-traffic.show', $project) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $project->name }}</h5>
                                    <p class="font-normal text-gray-700 dark:text-gray-400 truncate">{{ $project->url }}</p>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
