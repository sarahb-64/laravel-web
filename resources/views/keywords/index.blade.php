@extends('layouts.app')

@section('content')
    <h1>Planificador de Palabras Clave</h1>
    <a href="{{ route('keywords.create') }}" class="btn btn-primary">Agregar Palabra Clave</a>
    <table class="table">
        <thead>
            <tr>
                <th>Palabra Clave</th>
                <th>Prioridad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($keywords as $keyword)
                <tr>
                    <td>{{ $keyword->keyword }}</td>
                    <td>{{ $keyword->priority }}</td>
                    <td>
                        <a href="{{ route('keywords.edit', $keyword->id) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('keywords.destroy', $keyword->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection