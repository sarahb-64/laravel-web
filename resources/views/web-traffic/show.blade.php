<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Análisis de Tráfico para:') }} <span class="font-bold text-indigo-600">{{ $project->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($error)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ $error }}</p>
                </div>
            @endif

            @if ($analyticsData)
                <!-- Metrics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-gray-500 text-sm font-medium">Visitas (Sesiones)</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ number_format($analyticsData['totalVisitors']) }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-gray-500 text-sm font-medium">Usuarios Únicos</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ number_format($analyticsData['totalUsers']) }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-gray-500 text-sm font-medium">Páginas más visitadas</h3>
                        <ul class="text-sm text-gray-800">
                            @forelse($analyticsData['mostVisitedPages'] as $page)
                                <li>{{ $page['pageTitle'] }}: {{ number_format($page['screenPageViews']) }}</li>
                            @empty
                                <li>No data</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Chart -->
                <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-700">Sesiones en los últimos 30 días</h3>
                    <div style="height: 400px;">
                        <canvas id="trafficChart"></canvas>
                    </div>
                </div>
            @endif

            <div class="mt-6">
                 <a href="{{ route('tools.web-traffic.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Volver a la lista de proyectos
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        @if ($analyticsData && isset($analyticsData['chartData']))
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ctx = document.getElementById('trafficChart');
                    if (ctx) {
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: @json($analyticsData['chartLabels']),
                                datasets: [{
                                    label: 'Sesiones',
                                    data: @json($analyticsData['chartData']),
                                    fill: true,
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgb(75, 192, 192)',
                                    tension: 0.2
                                }]
                            },
                            options: {
                                scales: {
                                    y: { beginAtZero: true }
                                },
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    }
                });
            </script>
        @endif
    @endpush
</x-app-layout>
