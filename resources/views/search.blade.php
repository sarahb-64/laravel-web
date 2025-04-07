@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('trackPosition') }}">
            @csrf
            <div class="mb-3">
                <label for="keyword" class="form-label">Palabra clave</label>
                <input type="text" class="form-control" id="keyword" name="keyword" required>
            </div>
            <button type="submit" class="btn btn-primary">Consultar posición</button>
        </form>

        @if (session('status'))
            <div class="alert alert-success mt-4">
                {{ session('status') }}
            </div>
        @endif

        @if (isset($position))
            <div class="alert alert-info mt-4">
                La palabra clave "{{ $keyword }}" está en la posición {{ $position }} en Google.
            </div>
        @endif
    </div>
@endsection