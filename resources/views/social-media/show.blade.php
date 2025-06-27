<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asistente de Redes Sociales para:') }} <span class="font-bold text-indigo-600">{{ $project->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Post Generation Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form action="{{ route('tools.social-media.generate', $project) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="topic" class="block text-sm font-medium text-gray-700">Tema Principal de los Posts *</label>
                        <input type="text" name="topic" id="topic" value="{{ old('topic', $topic ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Ej: Lanzamiento de nuestro nuevo producto ecológico">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="platform" class="block text-sm font-medium text-gray-700">Plataforma *</label>
                            <select name="platform" id="platform" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="twitter" {{ (isset($platform) && $platform == 'twitter') ? 'selected' : '' }}>Twitter</option>
                                <option value="facebook" {{ (isset($platform) && $platform == 'facebook') ? 'selected' : '' }}>Facebook</option>
                                <option value="linkedin" {{ (isset($platform) && $platform == 'linkedin') ? 'selected' : '' }}>LinkedIn</option>
                            </select>
                        </div>
                        <div>
                            <label for="tone" class="block text-sm font-medium text-gray-700">Tono *</label>
                            <select name="tone" id="tone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="profesional" {{ (isset($tone) && $tone == 'profesional') ? 'selected' : '' }}>Profesional</option>
                                <option value="amigable" {{ (isset($tone) && $tone == 'amigable') ? 'selected' : '' }}>Amigable</option>
                                <option value="informativo" {{ (isset($tone) && $tone == 'informativo') ? 'selected' : '' }}>Informativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Generar Posts</button>
                    </div>
                </form>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Generated Posts -->
            @if ($posts)
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Posts Generados</h3>
                <div class="space-y-4">
                    @foreach ($posts as $post)
                        <div class="bg-white rounded-lg shadow-md p-4 post-card">
                            <p class="text-gray-800 whitespace-pre-wrap">{{ $post['post_text'] }}</p>
                            <button onclick="copyPost(this)" data-post-text="{{ $post['post_text'] }}" class="mt-3 text-sm text-blue-500 hover:underline">Copiar Post</button>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-8">
                 <a href="{{ route('tools.social-media.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Volver a la lista de proyectos</a>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function copyPost(button) {
            const postText = button.dataset.postText;
            navigator.clipboard.writeText(postText).then(() => {
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
