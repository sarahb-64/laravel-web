<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('build/assets/main-RSBOSKzq.css') }}">
        <script src="{{ asset('build/assets/main-M4XWvx60.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @isset($header)
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ $header['title'] }}
                        </h2>
                        <p class="text-gray-600">{{ $header['description'] }}</p>
                    @else
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('Dashboard') }}
                        </h2>
                    @endisset
                </div>
            </header>

            <!-- Page Content -->
            <main>
            @yield('content')  <!-- Esto permitirá que la vista 'search' renderice su contenido aquí -->
            </main>
        </div>
    </body>
</html>
