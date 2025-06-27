<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generador de Anuncios para:') }} <span class="font-bold text-indigo-600">{{ $project->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Ad Generation Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form action="{{ route('tools.paid-ads.generate', $project) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Descripción del Producto/Servicio</h3>
                        <p class="mt-1 text-sm text-gray-600 bg-gray-50 p-3 rounded-md">{{ $project->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="keywords" class="block text-sm font-medium text-gray-700">Palabras Clave *</label>
                            <input type="text" name="keywords" id="keywords" value="{{ old('keywords', $keywords ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="platform" class="block text-sm font-medium text-gray-700">Plataforma *</label>
                            <select name="platform" id="platform" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="google" {{ (isset($platform) && $platform == 'google') ? 'selected' : '' }}>Google Ads</option>
                                <option value="facebook" {{ (isset($platform) && $platform == 'facebook') ? 'selected' : '' }}>Facebook Ads</option>
                                <option value="linkedin" {{ (isset($platform) && $platform == 'linkedin') ? 'selected' : '' }}>LinkedIn Ads</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Generar Anuncios</button>
                    </div>
                </form>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Generated Ads -->
            @if ($ads)
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Anuncios Generados</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($ads as $index => $ad)
                        <div class="bg-white rounded-lg shadow-md p-6 ad-card">
                            <h4 class="font-bold text-lg text-indigo-700">{{ $ad['headline'] }}</h4>
                            <p class="text-gray-600 mt-2">{{ $ad['body'] }}</p>
                            <button onclick="copyAd(this)" data-headline="{{ $ad['headline'] }}" data-body="{{ $ad['body'] }}" class="mt-4 text-sm text-blue-500 hover:underline">Copiar Anuncio</button>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-8">
                 <a href="{{ route('tools.paid-ads.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Volver a la lista de proyectos</a>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function copyAd(button) {
            const headline = button.dataset.headline;
            const body = button.dataset.body;
            const adText = `Titular: ${headline}\nCuerpo: ${body}`;
            
            navigator.clipboard.writeText(adText).then(() => {
                const originalText = button.textContent;
                button.textContent = '¡Copiado!';
                setTimeout(() => {
                    button.textContent = originalText;
                }, 2000);
            });
        }
    </script>
    @endpush
</x-app-layout>
