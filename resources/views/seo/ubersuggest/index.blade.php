@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Keyword Research Tool</h1>
        
        <div class="mb-6">
            <form id="keywordForm" class="space-y-4">
                @csrf
                <div>
                    <label for="keyword" class="block text-sm font-medium text-gray-700">Keyword or Phrase</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" name="keyword" id="keyword" 
                               class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300" 
                               placeholder="Enter a keyword or phrase" required>
                        <button type="submit" 
                                class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Get Suggestions
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div id="loadingIndicator" class="hidden text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
            <p class="mt-2 text-sm text-gray-600">Finding keyword suggestions, please wait...</p>
        </div>

        <div id="results" class="hidden mt-8">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="text-lg font-medium mb-4">Keyword Suggestions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="keywordResults">
                    <!-- Keyword suggestions will be added here by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('keywordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const keyword = document.getElementById('keyword').value.trim();
    const loadingIndicator = document.getElementById('loadingIndicator');
    const results = document.getElementById('results');
    const keywordResults = document.getElementById('keywordResults');
    
    if (!keyword) return;
    
    // Show loading, hide results
    loadingIndicator.classList.remove('hidden');
    results.classList.add('hidden');
    keywordResults.innerHTML = '';
    
    // Make AJAX request
    fetch('{{ route('seo.ubersuggest.suggest') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ keyword: keyword })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data && data.data.length > 0) {
            // Add keyword cards to results
            data.data.forEach(keyword => {
                const keywordCard = document.createElement('div');
                keywordCard.className = 'bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:border-indigo-300 transition-colors';
                keywordCard.innerHTML = `
                    <div class="flex justify-between items-start">
                        <h3 class="font-medium text-gray-900">${keyword}</h3>
                        <button class="text-indigo-600 hover:text-indigo-900" 
                                onclick="copyToClipboard('${keyword.replace(/'/g, "\\'")}')"
                                title="Copy to clipboard">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                            </svg>
                        </button>
                    </div>
                `;
                keywordResults.appendChild(keywordCard);
            });
            
            results.classList.remove('hidden');
        } else {
            alert('No keyword suggestions found. Please try a different term.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching keyword suggestions. Please try again.');
    })
    .finally(() => {
        loadingIndicator.classList.add('hidden');
    });
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show a small toast or tooltip to indicate success
        const tooltip = document.createElement('div');
        tooltip.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg';
        tooltip.textContent = 'Copied to clipboard!';
        document.body.appendChild(tooltip);
        
        setTimeout(() => {
            tooltip.remove();
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}
</script>
@endpush

<style>
.keyword-card {
    transition: all 0.2s ease-in-out;
}

.keyword-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
</style>
@endsection
