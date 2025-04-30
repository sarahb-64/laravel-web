<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-6"><?php echo e($header['title']); ?></h2>
                    <p class="text-gray-600 mb-8"><?php echo e($header['description']); ?></p>
                    
                    <form id="campaignForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Campaign ID</label>
                            <input type="text" name="campaign_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Analyze Campaign
                        </button>
                    </form>

                    <div id="analysisResults" class="mt-8 hidden">
                        <div class="mt-4">
                            <h3 class="text-lg font-medium">Campaign Analysis</h3>
                            <div id="metrics" class="mt-4 space-y-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>

<script>
document.getElementById('campaignForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const response = await fetch('/mail/grader/analyze', {
        method: 'POST',
        body: formData
    });
    
    const data = await response.json();
    
    if (data.success) {
        document.getElementById('analysisResults').classList.remove('hidden');
        displayAnalysis(data.data);
    } else {
        alert('Error analyzing campaign: ' + data.error);
    }
});

function displayAnalysis(data) {
    const metricsDiv = document.getElementById('metrics');
    metricsDiv.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-lg shadow">
                <h4 class="text-lg font-medium">Open Rate</h4>
                <p class="text-2xl font-bold">${(data.analysis.open_rate.value * 100).toFixed(2)}%</p>
                <p class="text-sm text-gray-600">${data.analysis.open_rate.rating}</p>
                <ul class="mt-2 list-disc list-inside text-sm text-gray-600">
                    ${data.analysis.open_rate.suggestions.map(s => `<li>${s}</li>`).join('')}
                </ul>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow">
                <h4 class="text-lg font-medium">Click Rate</h4>
                <p class="text-2xl font-bold">${(data.analysis.click_rate.value * 100).toFixed(2)}%</p>
                <p class="text-sm text-gray-600">${data.analysis.click_rate.rating}</p>
                <ul class="mt-2 list-disc list-inside text-sm text-gray-600">
                    ${data.analysis.click_rate.suggestions.map(s => `<li>${s}</li>`).join('')}
                </ul>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow">
                <h4 class="text-lg font-medium">Unsubscribe Rate</h4>
                <p class="text-2xl font-bold">${(data.analysis.unsubscribe_rate.value * 100).toFixed(2)}%</p>
                <p class="text-sm text-gray-600">${data.analysis.unsubscribe_rate.rating}</p>
                <ul class="mt-2 list-disc list-inside text-sm text-gray-600">
                    ${data.analysis.unsubscribe_rate.suggestions.map(s => `<li>${s}</li>`).join('')}
                </ul>
            </div>
        </div>
    `;
}
</script><?php /**PATH C:\Users\sarah\OneDrive\Escritorio\Practicas APPYWEB\Laravel Project\laravel-web\resources\views/mail/grader/index.blade.php ENDPATH**/ ?>