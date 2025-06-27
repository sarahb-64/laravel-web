<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asistente de Redes Sociales con IA') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if($projects->isEmpty())
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                            <p class="font-bold">No tienes proyectos</p>
                            <p>Para usar esta herramienta, primero necesitas un proyecto. <a href="{{ route('projects.create') }}" class="font-bold underline">Crea un proyecto nuevo</a>.</p>
                        </div>
                    @else
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Selecciona un proyecto para generar posts para redes sociales:</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($projects as $project)
                                <a href="{{ route('tools.social-media.show', $project) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $project->name }}</h5>
                                    <p class="font-normal text-gray-700 dark:text-gray-400 truncate">{{ $project->description }}</p>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
