@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Calculadora de Pruebas A/B</h1>
    
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('ab-test.calculate') }}" method="POST" class="bg-white p-8 rounded-lg shadow-md">
            @csrf
            
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Variante A</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Conversiónes</label>
                        <input type="number" name="variant_a_conversions" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Visitantes</label>
                        <input type="number" name="variant_a_visitors" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Variante B</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Conversiónes</label>
                        <input type="number" name="variant_b_conversions" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Visitantes</label>
                        <input type="number" name="variant_b_visitors" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Calcular
                </button>
            </div>
        </form>

        @if(isset($result))
        <div class="mt-8 bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Resultados</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="font-bold">Tasa de Conversión Variante A:</p>
                    <p>{{ number_format($result['a_rate'], 2) }}%</p>
                </div>
                <div>
                    <p class="font-bold">Tasa de Conversión Variante B:</p>
                    <p>{{ number_format($result['b_rate'], 2) }}%</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="font-bold">P-Value:</p>
                <p>{{ number_format($result['p_value'], 4) }}</p>
            </div>
            <div class="mt-4">
                <p class="font-bold">¿Resultado significativo?:</p>
                <p class="{{ $result['is_significant'] ? 'text-green-600' : 'text-red-600' }}">
                    {{ $result['is_significant'] ? 'Sí' : 'No' }}
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection