@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">AI Writer</h1>
        
        <form id="aiWriterForm" class="space-y-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="topic">
                    Tema
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       id="topic" name="topic" type="text" required>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="tone">
                    Ton
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        id="tone" name="tone" required>
                    <option value="">Selecciona un tono</option>
                    <option value="formal">Formal</option>
                    <option value="informal">Informal</option>
                    <option value="amigable">Amigable</option>
                    <option value="profesional">Profesional</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="length">
                    Longitud del contenido (palabras)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       id="length" name="length" type="number" min="100" max="2000" value="500" required>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Generar Contenido
            </button>
        </form>

        <div id="loading" class="hidden mt-4">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                <span class="ml-2">Generando contenido...</span>
            </div>
        </div>

        <div id="result" class="mt-6 hidden">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Contenido Generado</h2>
                <div id="content" class="prose max-w-none"></div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.getElementById('aiWriterForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('result').classList.add('hidden');
    
    try {
        const response = await fetch('/ai-writer/generate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('content').innerHTML = data.content;
            document.getElementById('result').classList.remove('hidden');
        } else {
            alert('Error: ' + data.error);
        }
    } catch (error) {
        alert('Error al generar el contenido. Por favor, intenta nuevamente.');
    } finally {
        document.getElementById('loading').classList.add('hidden');
    }
});
</script>
@endsection