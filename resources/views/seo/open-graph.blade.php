@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('OpenGraph Analyzer') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <form action="{{ route('seo.open-graph.analyze') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="url" name="url" class="form-control" placeholder="Enter URL to analyze" required>
                                <button type="submit" class="btn btn-primary">Analyze</button>
                            </div>
                        </form>
                    </div>

                    @if(isset($results) || isset($suggestions))
                        <div class="mt-4">
                            <h4>Analysis Results</h4>
                            
                            @if(isset($results))
                                <div class="card mb-4">
                                    <div class="card-header">Current OpenGraph Tags</div>
                                    <div class="card-body">
                                        <pre>{{ json_encode($results, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @endif

                            @if(isset($suggestions))
                                <div class="card">
                                    <div class="card-header">AI Suggestions</div>
                                    <div class="card-body">
                                        <pre>{{ json_encode($suggestions, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
