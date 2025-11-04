<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Festivando - Tu Evento Soñado</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Header -->
        <header class="bg-purple-900 text-white py-4 px-6">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-white rounded flex items-center justify-center">
                        <span class="text-purple-900 font-bold text-sm">F</span>
                    </div>
                    <span class="text-xl font-bold">FESTIVANDO</span>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-8 text-sm">
                    <a href="#" class="hover:text-purple-200">Inicio</a>
                    <a href="#" class="hover:text-purple-200">XV Años</a>
                    <a href="#" class="hover:text-purple-200">Cumpleaños</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-lg text-sm font-medium transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-purple-200 text-sm">
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-lg text-sm font-medium transition">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-purple-100 to-pink-100 py-20">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div>
                        <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                            Tu Evento Soñado,
                            <span class="text-orange-500">al Alcance de tus Manos</span>
                        </h1>
                        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                            Encuentra proveedores expertos y haz tu evento perfecto con la mejor plataforma P2P para fiestas, bodas y más.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                                Buscar proveedores
                            </a>
                        </div>
                    </div>

                    <!-- Right Content - Phone Mockup -->
                    <div class="relative">
                        <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm mx-auto">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                </div>
                                <div class="space-y-3">
                                    <div class="bg-gray-100 rounded-lg p-4">
                                        <div class="w-8 h-8 bg-purple-500 rounded-lg mb-2"></div>
                                        <div class="text-sm font-medium">Pastelería Premium</div>
                                    </div>
                                    <div class="bg-gray-100 rounded-lg p-4">
                                        <div class="w-8 h-8 bg-pink-500 rounded-lg mb-2"></div>
                                        <div class="text-sm font-medium">Decoración Floral</div>
                                    </div>
                                    <div class="bg-gray-100 rounded-lg p-4">
                                        <div class="w-8 h-8 bg-blue-500 rounded-lg mb-2"></div>
                                        <div class="text-sm font-medium">Fotografía</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-12">Explora por Categoría</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center group cursor-pointer">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A2.704 2.704 0 004.5 16c-.523 0-1.046-.151-1.5-.454M6.75 6.75L12 3l5.25 3.75M12 21l-8.25-8.25L12 3l8.25 9.75L12 21z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Bodas</h3>
                    </div>
                    <div class="text-center group cursor-pointer">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                            <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">XV Años</h3>
                    </div>
                    <div class="text-center group cursor-pointer">
                        <div class="w-20 h-20 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-pink-200 transition-colors">
                            <svg class="w-10 h-10 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1a3 3 0 000-6h-1m4 6V4a3 3 0 013-3m-3 14a3 3 0 01-3-3m-3 3a3 3 0 01-3-3m3-3h3m-3 0h-3"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Fiestas</h3>
                    </div>
                    <div class="text-center group cursor-pointer">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Música</h3>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-purple-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Logo and Description -->
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 bg-white rounded flex items-center justify-center">
                                <span class="text-purple-900 font-bold text-sm">F</span>
                            </div>
                            <span class="text-xl font-bold">FESTIVANDO</span>
                        </div>
                        <p class="text-purple-200 mb-4">Tu evento, tu momento especial</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-purple-200 hover:text-white">Facebook</a>
                            <a href="#" class="text-purple-200 hover:text-white">Twitter</a>
                            <a href="#" class="text-purple-200 hover:text-white">Instagram</a>
                        </div>
                    </div>

                    <!-- Links -->
                    <div>
                        <h3 class="font-semibold mb-4">Enlaces</h3>
                        <ul class="space-y-2 text-purple-200">
                            <li><a href="#" class="hover:text-white">Proveedores</a></li>
                            <li><a href="#" class="hover:text-white">Contacto</a></li>
                            <li><a href="#" class="hover:text-white">¿Cómo funciona?</a></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3 class="font-semibold mb-4">Contacto</h3>
                        <div class="space-y-2 text-purple-200">
                            <p>¿Tienes dudas o quieres colaborar con nosotros? ¡Escríbenos!</p>
                            <p>Email: contacto@festivando.com</p>
                            <p>Teléfono: +52 55 1234 5678</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-purple-800 mt-8 pt-8 text-center text-purple-200">
                    <p>&copy; {{ date('Y') }} Festivando. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
