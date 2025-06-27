@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">SEO Analysis Tool</h1>
        
        <div class="mb-6">
            <form id="seoAnalysisForm" class="space-y-4">
                @csrf
                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700">Website URL</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="url" name="url" id="url" 
                               class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" 
                               placeholder="https://example.com" required>
                        <button type="submit" 
                                class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Analyze
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div id="loadingIndicator" class="hidden text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
            <p class="mt-2 text-sm text-gray-600">Analyzing website, please wait...</p>
        </div>

        <div id="results" class="hidden mt-8 space-y-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="text-lg font-medium mb-4">Analysis Results</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-900">Page Information</h3>
                        <dl class="mt-2 space-y-2">
                            <div class="bg-white px-4 py-2 rounded">
                                <dt class="text-sm font-medium text-gray-500">Title</dt>
                                <dd id="resultTitle" class="mt-1 text-sm text-gray-900"></dd>
                            </div>
                            <div class="bg-white px-4 py-2 rounded">
                                <dt class="text-sm font-medium text-gray-500">Meta Description</dt>
                                <dd id="resultDescription" class="mt-1 text-sm text-gray-900"></dd>
                            </div>
                            <div class="bg-white px-4 py-2 rounded">
                                <dt class="text-sm font-medium text-gray-500">Meta Keywords</dt>
                                <dd id="resultKeywords" class="mt-1 text-sm text-gray-900"></dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900">SEO Recommendations</h3>
                        <ul id="suggestionsList" class="mt-2 space-y-2">
                            <!-- Suggestions will be added here by JavaScript -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('seoAnalysisForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const url = document.getElementById('url').value;
    const loadingIndicator = document.getElementById('loadingIndicator');
    const results = document.getElementById('results');
    
    // Show loading, hide results
    loadingIndicator.classList.remove('hidden');
    results.classList.add('hidden');
    
    // Make AJAX request
    fetch('{{ route('seo.analysis.analyze') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ url: url })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the UI with results
            document.getElementById('resultTitle').textContent = data.data.title || 'Not found';
            document.getElementById('resultDescription').textContent = data.data.meta_description || 'Not found';
            document.getElementById('resultKeywords').textContent = data.data.meta_keywords || 'Not found';
            
            // Update suggestions
            const suggestionsList = document.getElementById('suggestionsList');
            suggestionsList.innerHTML = '';
            
            if (data.data.suggestions && data.data.suggestions.length > 0) {
                data.data.suggestions.forEach(suggestion => {
                    const li = document.createElement('li');
                    li.className = 'flex items-start';
                    li.innerHTML = `
                        <svg class="h-5 w-5 text-yellow-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm text-gray-700">${suggestion}</span>
                    `;
                    suggestionsList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.className = 'text-sm text-green-600';
                li.textContent = 'Great! No major issues found.';
                suggestionsList.appendChild(li);
            }
            
            results.classList.remove('hidden');
        } else {
            alert('Error: ' + (data.message || 'Failed to analyze website'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while analyzing the website. Please try again.');
    })
    .finally(() => {
        loadingIndicator.classList.add('hidden');
    });
});
</script>
@endpush
@endsection
