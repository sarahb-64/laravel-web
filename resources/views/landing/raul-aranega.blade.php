<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raúl Aránega Segura - Desarrollador Web y Consultor SEO</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero-bg { background-color: #1a202c; }
        .section-title { border-bottom: 3px solid #4a5568; padding-bottom: 8px; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="text-xl font-bold text-gray-800">Raúl Aránega</div>
            <div class="hidden md:flex items-center space-x-6">
                <a href="#about" class="text-gray-600 hover:text-blue-500 transition duration-300">Sobre Mí</a>
                <a href="#skills" class="text-gray-600 hover:text-blue-500 transition duration-300">Habilidades</a>
                <a href="#services" class="text-gray-600 hover:text-blue-500 transition duration-300">Servicios</a>
                <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Acceder</a>
            </div>
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-800 focus:outline-none">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white">
            <a href="#about" class="block py-2 px-4 text-sm hover:bg-gray-200">Sobre Mí</a>
            <a href="#skills" class="block py-2 px-4 text-sm hover:bg-gray-200">Habilidades</a>
            <a href="#services" class="block py-2 px-4 text-sm hover:bg-gray-200">Servicios</a>
            <a href="{{ route('login') }}" class="block py-2 px-4 text-sm hover:bg-gray-200">Acceder</a>
        </div>
    </nav>

    <!-- Header -->
    <header class="hero-bg text-white shadow-lg">
        <div class="container mx-auto px-6 py-12 text-center">
            <img src="https://i.pravatar.cc/150?u=raul" alt="Foto de Raúl Aránega Segura" class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-gray-400">
            <h1 class="text-4xl font-bold">Raúl Aránega Segura</h1>
            <p class="text-xl mt-2">Desarrollador Web Full-Stack & Consultor SEO</p>
            <div class="mt-6">
                <a href="#services" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Mis Servicios</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-10">

        <!-- About Me Section -->
        <section id="about" class="mb-12">
            <h2 class="text-3xl font-bold mb-6 section-title">Sobre Mí</h2>
            <p class="text-lg leading-relaxed">
                Soy un apasionado desarrollador web con más de 5 años de experiencia en la creación de soluciones digitales robustas y escalables. Mi especialidad es combinar el desarrollo técnico con una profunda comprensión del SEO para construir productos que no solo funcionan bien, sino que también alcanzan una alta visibilidad en los motores de búsqueda.
            </p>
        </section>

        <!-- Skills Section -->
        <section id="skills" class="mb-12">
            <h2 class="text-3xl font-bold mb-6 section-title">Habilidades Técnicas</h2>
            <div class="flex flex-wrap justify-center gap-4">
                <span class="bg-gray-300 text-gray-800 text-sm font-semibold mr-2 px-3 py-1 rounded-full">PHP</span>
                <span class="bg-gray-300 text-gray-800 text-sm font-semibold mr-2 px-3 py-1 rounded-full">Laravel</span>
                <span class="bg-gray-300 text-gray-800 text-sm font-semibold mr-2 px-3 py-1 rounded-full">JavaScript</span>
                <span class="bg-gray-300 text-gray-800 text-sm font-semibold mr-2 px-3 py-1 rounded-full">Vue.js</span>
                <span class="bg-gray-300 text-gray-800 text-sm font-semibold mr-2 px-3 py-1 rounded-full">MySQL</span>
                <span class="bg-gray-300 text-gray-800 text-sm font-semibold mr-2 px-3 py-1 rounded-full">SEO Técnico</span>
                 <span class="bg-gray-300 text-gray-800 text-sm font-semibold mr-2 px-3 py-1 rounded-full">Google Analytics</span>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="mb-12">
            <h2 class="text-3xl font-bold mb-6 section-title">Servicios</h2>
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <i class="fas fa-code text-4xl text-blue-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Desarrollo Web a Medida</h3>
                    <p>Creación de aplicaciones web personalizadas con Laravel y Vue.js.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <i class="fas fa-search-dollar text-4xl text-blue-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Consultoría SEO</h3>
                    <p>Auditorías y estrategias para mejorar tu posicionamiento orgánico.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <i class="fas fa-chart-line text-4xl text-blue-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Optimización de Rendimiento</h3>
                    <p>Mejora de la velocidad y la experiencia de usuario de tu sitio web.</p>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} Raúl Aránega Segura. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            var menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>

</body>
</html>
