@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">AnswerThePublic - Generador de Preguntas</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="keyword">Palabra clave:</label>
                        <input type="text" class="form-control" id="keyword" placeholder="Ingrese una palabra clave">
                    </div>
                    <div class="mt-4">
                        <h4>Sugerencias relacionadas:</h4>
                        <div id="suggestions" class="mt-2">
                            <!-- Suggestions will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const keywordInput = document.getElementById('keyword');
    const suggestionsDiv = document.getElementById('suggestions');

    keywordInput.addEventListener('input', function(e) {
        const keyword = e.target.value;
        if (keyword.length >= 2) {
            fetch(`/api/answer-the-public/suggestions?keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        suggestionsDiv.innerHTML = data.data.map(suggestion => 
                            `<div class="alert alert-info mb-1">${suggestion}</div>`
                        ).join('');
                    }
                });
        } else {
            suggestionsDiv.innerHTML = '';
        }
    });
});
</script>
@endsection
@endsection