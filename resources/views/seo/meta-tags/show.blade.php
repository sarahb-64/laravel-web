<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Análisis de Meta Tags para:') }} <span class="font-bold text-indigo-600">{{ $project->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Form to trigger analysis -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <p class="text-sm text-gray-600 mb-1">Analizando URL:</p>
                <p class="text-lg font-medium text-indigo-700 mb-4">{{ $project->url }}</p>
                <form action="{{ route('tools.seo.meta-tags.analyze', $project) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Analizar Meta Tags Ahora
                    </button>
                </form>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Results -->
            @if ($result)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current Meta Tags -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Metaetiquetas Actuales</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                                <div class="p-3 bg-gray-50 rounded-md border border-gray-200 min-h-[3rem]">{{ $result['current']['title'] ?: '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                <div class="p-3 bg-gray-50 rounded-md border border-gray-200 min-h-[4rem]">{{ $result['current']['description'] ?: '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Suggested Meta Tags -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-800">Sugerencias de la IA</h3>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">IA</span>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Título Sugerido</label>
                                <div id="suggestedTitle" class="p-3 bg-blue-50 rounded-md border border-blue-200 min-h-[3rem]">{{ $result['suggested']['title'] ?: '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción Sugerida</label>
                                <div id="suggestedDescription" class="p-3 bg-blue-50 rounded-md border border-blue-200 min-h-[4rem]">{{ $result['suggested']['description'] ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button onclick="copyToClipboard()" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Copiar Sugerencias</button>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="mt-8">
                 <a href="{{ route('tools.seo.meta-tags.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Volver a la lista de proyectos
                </a>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard() {
            const title = document.getElementById('suggestedTitle').textContent;
            const description = document.getElementById('suggestedDescription').textContent;
            
            const metaTags = `<title>${title}</title>\n<meta name="description" content="${description}">`;
            
            navigator.clipboard.writeText(metaTags).then(() => {
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = '¡Copiado!';
                button.classList.add('bg-green-600');
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                }, 2000);
            });
        }
    </script>
    @endpush
</x-app-layout>
