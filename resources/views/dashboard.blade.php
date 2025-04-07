@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Historial de Posiciones</h1>
    
    <div class="mb-4">
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="row">
                <div class="col-md-4">
                    <select name="keyword" class="form-select">
                        @foreach($keywords as $keyword)
                            <option value="{{ $keyword->id }}" {{ request('keyword') == $keyword->id ? 'selected' : '' }}>
                                {{ $keyword->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Ver Gráfico</button>
                </div>
            </div>
        </form>
    </div>

    @if($keyword)
        <div class="card">
            <div class="card-body">
                <canvas id="positionChart"></canvas>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="[https://cdn.jsdelivr.net/npm/chart.js"></script>](https://cdn.jsdelivr.net/npm/chart.js"></script>)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('positionChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [{
                    label: 'Posición',
                    data: {!! json_encode($positions) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection