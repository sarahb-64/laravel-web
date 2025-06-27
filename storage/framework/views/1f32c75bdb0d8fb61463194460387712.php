<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        <link rel="stylesheet" href="<?php echo e(asset('build/assets/main-RSBOSKzq.css')); ?>">
        <script src="<?php echo e(asset('build/assets/main-M4XWvx60.js')); ?>" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <?php if(isset($header)): ?>
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            <?php echo e($header['title']); ?>

                        </h2>
                        <p class="text-gray-600"><?php echo e($header['description']); ?></p>
                    <?php else: ?>
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            <?php echo e(__('Dashboard')); ?>

                        </h2>
                    <?php endif; ?>
                </div>
            </header>

            <!-- Page Content -->
            <main>
            <?php echo $__env->yieldContent('content'); ?>  <!-- Esto permitirá que la vista 'search' renderice su contenido aquí -->
            </main>
        </div>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html>
<?php /**PATH C:\Users\sarah\OneDrive\Escritorio\Practicas APPYWEB\Laravel Project\laravel-web\resources\views/layouts/app.blade.php ENDPATH**/ ?>