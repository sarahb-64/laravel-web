

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><?php echo e(__('OpenGraph Analyzer')); ?></div>

                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <form action="<?php echo e(route('seo.open-graph.analyze')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="input-group">
                                <input type="url" name="url" class="form-control" placeholder="Enter URL to analyze" required>
                                <button type="submit" class="btn btn-primary">Analyze</button>
                            </div>
                        </form>
                    </div>

                    <?php if(isset($results) || isset($suggestions)): ?>
                        <div class="mt-4">
                            <h4>Analysis Results</h4>
                            
                            <?php if(isset($results)): ?>
                                <div class="card mb-4">
                                    <div class="card-header">Current OpenGraph Tags</div>
                                    <div class="card-body">
                                        <pre><?php echo e(json_encode($results, JSON_PRETTY_PRINT)); ?></pre>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($suggestions)): ?>
                                <div class="card">
                                    <div class="card-header">AI Suggestions</div>
                                    <div class="card-body">
                                        <pre><?php echo e(json_encode($suggestions, JSON_PRETTY_PRINT)); ?></pre>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\sarah\OneDrive\Escritorio\Practicas APPYWEB\Laravel Project\laravel-web\resources\views/seo/open-graph.blade.php ENDPATH**/ ?>