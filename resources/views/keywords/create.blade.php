extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Agregar Nueva Palabra Clave</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('keywords.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="keyword" class="form-label">Palabra Clave:</label>
            <input type="text" name="keyword" id="keyword" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descripción:</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('keywords.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
