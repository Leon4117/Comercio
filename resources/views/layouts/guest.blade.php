<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/4 bg-gradient-to-br from-slate-800 to-slate-900 relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative z-10 flex flex-col justify-center items-center text-white p-12">
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-bold text-blue-400 mb-4">Festivando</h1>
                    <p class="text-gray-400 max-w-md">
                        Administra tus pedidos, servicios y conecta con clientes de manera eficiente.
                    </p>
                </div>

                <!-- Decorative Elements -->
                <div class="absolute top-20 left-20 w-32 h-32 bg-blue-500 rounded-full opacity-10"></div>
                <div class="absolute bottom-20 right-20 w-24 h-24 bg-purple-500 rounded-full opacity-10"></div>
                <div class="absolute top-1/2 left-10 w-16 h-16 bg-green-500 rounded-full opacity-10"></div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-3/4 flex items-center justify-center p-8 bg-gray-50">
            <div class="w-full max-w-5xl">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Festivando</h1>
                </div>

                <!-- Login Card -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    @if (isset($slot))
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endif
                </div>

                <!-- Footer -->
                <div class="text-center mt-6 text-sm text-gray-500">
                    <p>&copy; {{ date('Y') }} Festivando. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
