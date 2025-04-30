<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis SEO - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Análisis SEO</h1>

            <!-- Formulario de Análisis -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form id="seoAnalysisForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">URL del sitio web</label>
                        <input type="url" id="url" name="url" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="https://ejemplo.com" required>
                    </div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Iniciar Análisis SEO
                    </button>
                </form>
            </div>

            <!-- Estado del Análisis -->
            <div id="analysisStatus" class="bg-white rounded-lg shadow-md p-6 mb-8 hidden">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Estado del Análisis</h2>
                    <div id="statusIndicator" class="w-8 h-8 rounded-full bg-yellow-400"></div>
                </div>
                <div id="statusMessage" class="text-gray-600"></div>
            </div>

            <!-- Resultados del Análisis -->
            <div id="analysisResults" class="bg-white rounded-lg shadow-md p-6 hidden">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Resultados del Análisis SEO</h2>
                
                <!-- Resumen General -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500">Puntuación SEO</h3>
                        <p id="seoScore" class="text-2xl font-semibold text-indigo-600">-</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500">Palabras Clave</h3>
                        <p id="keywords" class="text-xl font-semibold">-</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500">Velocidad de Carga</h3>
                        <p id="loadSpeed" class="text-xl font-semibold">-</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500">Móvil Friendly</h3>
                        <p id="mobileFriendly" class="text-xl font-semibold">-</p>
                    </div>
                </div>

                <!-- Detalles del Análisis -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Título de la Página</h3>
                        <p id="pageTitle" class="text-gray-600">-</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Meta Descripción</h3>
                        <p id="metaDescription" class="text-gray-600">-</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Recomendaciones</h3>
                        <div id="recommendations" class="space-y-2">
                            <!-- Las recomendaciones se cargarán dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('seoAnalysisForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const url = document.getElementById('url').value;
            const statusDiv = document.getElementById('analysisStatus');
            const resultsDiv = document.getElementById('analysisResults');
            
            // Mostrar estado de análisis
            statusDiv.classList.remove('hidden');
            resultsDiv.classList.add('hidden');
            document.getElementById('statusIndicator').classList.remove('bg-red-400', 'bg-green-400');
            document.getElementById('statusIndicator').classList.add('bg-yellow-400');
            document.getElementById('statusMessage').textContent = 'Iniciando análisis...';
            
            try {
                const response = await fetch('/api/seo/analyze', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ url })
                });
                
                const data = await response.json();
                
                if (response.status === 200) {
                    // Establecer intervalo para verificar el estado
                    const checkStatus = async () => {
                        const statusResponse = await fetch(`/api/seo/status/${data.analysis_id}`);
                        const statusData = await statusResponse.json();
                        
                        if (statusData.status === 'completed') {
                            // Actualizar interfaz con resultados
                            updateResults(statusData);
                            clearInterval(interval);
                        } else if (statusData.status === 'failed') {
                            document.getElementById('statusIndicator').classList.remove('bg-yellow-400');
                            document.getElementById('statusIndicator').classList.add('bg-red-400');
                            document.getElementById('statusMessage').textContent = 'Análisis fallido';
                            clearInterval(interval);
                        } else {
                            document.getElementById('statusMessage').textContent = 'Análisis en progreso...';
                        }
                    };
                    
                    const interval = setInterval(checkStatus, 5000);
                } else if (response.status === 409) {
                    document.getElementById('statusIndicator').classList.remove('bg-yellow-400');
                    document.getElementById('statusIndicator').classList.add('bg-red-400');
                    document.getElementById('statusMessage').textContent = 'Análisis ya existente para esta URL';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('statusIndicator').classList.remove('bg-yellow-400');
                document.getElementById('statusIndicator').classList.add('bg-red-400');
                document.getElementById('statusMessage').textContent = 'Error al iniciar el análisis';
            }
        });

        function updateResults(data) {
            const statusDiv = document.getElementById('analysisStatus');
            const resultsDiv = document.getElementById('analysisResults');
            
            // Actualizar estado
            statusDiv.classList.add('hidden');
            resultsDiv.classList.remove('hidden');
            document.getElementById('statusIndicator').classList.remove('bg-yellow-400');
            document.getElementById('statusIndicator').classList.add('bg-green-400');
            document.getElementById('statusMessage').textContent = 'Análisis completado';
            
            // Actualizar resultados
            document.getElementById('seoScore').textContent = data.seo_score || '-';
            document.getElementById('keywords').textContent = data.keywords?.join(', ') || '-';
            document.getElementById('loadSpeed').textContent = data.load_speed || '-';
            document.getElementById('mobileFriendly').textContent = data.mobile_friendly ? 'Sí' : 'No';
            document.getElementById('pageTitle').textContent = data.page_title || '-';
            document.getElementById('metaDescription').textContent = data.meta_description || '-';
            
            // Actualizar recomendaciones
            const recommendationsDiv = document.getElementById('recommendations');
            recommendationsDiv.innerHTML = data.recommendations?.map(rec => `
                <div class="bg-yellow-50 p-3 rounded-md">
                    <p class="text-sm text-gray-700">${rec}</p>
                </div>
            `).join('') || '<p class="text-gray-500">No se encontraron recomendaciones específicas.</p>';
        }
    </script>
</body>
</html>