<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <div class="bg-secondary-800 text-white flex flex-col transition-all duration-300"
            :class="sidebarOpen ? 'w-64' : 'w-16'">
            <!-- Toggle Button (Top) -->
            <div class="p-4 flex justify-end" x-show="!sidebarOpen" x-transition>
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-lg hover:bg-secondary-700 transition-colors">
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <!-- Logo/Brand -->
            <div class="p-6" x-show="sidebarOpen" x-transition>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-primary-400">Festivando</div>
                        <div class="text-sm text-gray-400">Panel de Proveedores</div>
                    </div>
                    <!-- Toggle Button (Expanded) -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-lg hover:bg-secondary-700 transition-colors">
                        <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Logo Collapsed -->
            <div class="px-4 pb-6" x-show="!sidebarOpen" x-transition>
                <div class="flex justify-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <span class="text-xl font-bold text-white">F</span>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4" x-data="{
                openMenus: {
                    pedidos: false,
                    servicios: false,
                    gestion: false,
                    usuarios: false
                }
            }">
                <div class="space-y-2">
                    <!-- Dashboard Principal (solo expandido) - Oculto para admin -->
                    @if (auth()->user()->role != 'admin')
                        <div x-show="sidebarOpen" x-transition>
                            <a href="{{ auth()->user()->role === 'supplier' ? route('supplier.dashboard') : route('dashboard') }}"
                                class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') || request()->routeIs('supplier.dashboard') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-secondary-700' }} rounded-lg">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 5v6m4-6v6m4-6v6"></path>
                                </svg>
                                Dashboard
                            </a>
                        </div>
                    @endif

                    <!-- Gestión de Pedidos - Solo para proveedores -->
                    @if (auth()->user()->role === 'supplier')
                        <div class="space-y-1" x-show="sidebarOpen" x-transition>
                            <button @click="openMenus.pedidos = !openMenus.pedidos"
                                class="flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:bg-secondary-700 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    Mis Pedidos
                                </div>
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openMenus.pedidos }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openMenus.pedidos" x-transition class="ml-6 space-y-1">
                                <a href="{{ route('supplier.pending-orders') }}"
                                    class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('supplier.pending-orders') ? 'text-blue-400 bg-slate-700' : 'text-gray-400 hover:text-white hover:bg-secondary-700' }} rounded-lg">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></span>
                                    Pedidos Pendientes
                                </a>
                                <a href="{{ route('supplier.completed-orders') }}"
                                    class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('supplier.completed-orders') ? 'text-blue-400 bg-slate-700' : 'text-gray-400 hover:text-white hover:bg-secondary-700' }} rounded-lg">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                                    Pedidos Realizados
                                </a>

                            </div>
                        </div>
                    @endif

                    <!-- Gestión de Pedidos - Solo para clientes -->
                    @if (auth()->user()->role === 'client')
                        <div class="space-y-1" x-show="sidebarOpen" x-transition>
                            <button @click="openMenus.pedidos = !openMenus.pedidos"
                                class="flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:bg-secondary-700 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    Mis Pedidos
                                </div>
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openMenus.pedidos }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openMenus.pedidos" x-transition class="ml-6 space-y-1">
                                <a href="#"
                                    class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-secondary-700 rounded-lg">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></span>
                                    En Proceso
                                </a>
                                <a href="#"
                                    class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-secondary-700 rounded-lg">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                                    Completados
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Chats - Solo para clientes -->
                    @if (auth()->user()->role === 'client')
                        <div x-show="sidebarOpen" x-transition>
                            <a href="{{ route('chat.index') }}"
                                class="flex items-center px-4 py-3 {{ request()->routeIs('chat.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-secondary-700' }} rounded-lg">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                Chats
                            </a>
                        </div>
                    @endif

                    <!-- Iconos colapsados - Oculto para admin -->
                    @if (auth()->user()->role != 'admin')
                        <div x-show="!sidebarOpen" x-transition class="space-y-3">
                            <!-- Dashboard -->
                            <div class="relative group">
                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center justify-center w-10 h-10 mx-auto {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-secondary-700 hover:text-white' }} rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5v6m4-6v6m4-6v6"></path>
                                    </svg>
                                </a>
                                <!-- Tooltip -->
                                <div
                                    class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                    Dashboard
                                </div>
                            </div>

                            <!-- Pedidos -->
                            @if (auth()->user()->role !== 'supplier')
                                <div class="relative group">
                                    <a href="#"
                                        class="flex items-center justify-center w-10 h-10 mx-auto text-gray-300 hover:bg-secondary-700 hover:text-white rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </a>
                                    <!-- Tooltip -->
                                    <div
                                        class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                        Gestión de Pedidos
                                    </div>
                                </div>
                            @endif

                            @if (auth()->user()->role === 'supplier')
                                <!-- Servicios -->
                                <div class="relative group">
                                    <a href="{{ route('supplier.services.index') }}"
                                        class="flex items-center justify-center w-10 h-10 mx-auto {{ request()->routeIs('supplier.services.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-secondary-700 hover:text-white' }} rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                    </a>
                                    <!-- Tooltip -->
                                    <div
                                        class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                        Mis Servicios
                                    </div>
                                </div>

                                <!-- Perfil (Reemplaza Gestión) -->
                                <div class="relative group">
                                    <a href="{{ route('profile.edit') }}"
                                        class="flex items-center justify-center w-10 h-10 mx-auto {{ request()->routeIs('profile.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-secondary-700 hover:text-white' }} rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </a>
                                    <!-- Tooltip -->
                                    <div
                                        class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                        Mi Perfil
                                    </div>
                                </div>
                            @endif

                            <!-- Perfil -->
                            @if (auth()->user()->role !== 'supplier')
                                <div class="relative group">
                                    <a href="{{ route('profile.edit') }}"
                                        class="flex items-center justify-center w-10 h-10 mx-auto {{ request()->routeIs('profile.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-secondary-700 hover:text-white' }} rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </a>
                                    <!-- Tooltip -->
                                    <div
                                        class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                        Mi Perfil
                                    </div>
                                </div>
                            @endif

                            <!-- Separador -->
                            <div class="border-t border-slate-700 mx-2"></div>

                            <!-- Chat -->
                            <div class="relative group">
                                <a href="{{ route('chat.index') }}"
                                    class="flex items-center justify-center w-10 h-10 mx-auto {{ request()->routeIs('chat.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-secondary-700 hover:text-white' }} rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    <!-- Notification Badge -->
                                    <span
                                        class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full flex items-center justify-center">
                                        <span class="text-xs text-white font-bold">2</span>
                                    </span>
                                </a>
                                <!-- Tooltip -->
                                <div
                                    class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                    Chats (2 nuevos)
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Iconos colapsados para Admin -->
                    @if (auth()->user()->role == 'admin')
                        <div x-show="!sidebarOpen" x-transition class="space-y-3">
                            <!-- Dashboard Admin -->
                            <div class="relative group">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center justify-center w-10 h-10 mx-auto {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-secondary-700 hover:text-white' }} rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                </a>
                                <!-- Tooltip -->
                                <div
                                    class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                    Dashboard Admin
                                </div>
                            </div>

                            <!-- Ganancias -->
                            {{-- <div class="relative group">
                            <a href="#" class="flex items-center justify-center w-10 h-10 mx-auto text-gray-300 hover:bg-secondary-700 hover:text-white rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </a>
                            <!-- Tooltip -->
                            <div class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                Ganancias
                            </div>
                        </div> --}}

                            <!-- Usuarios -->
                            {{-- <div class="relative group">
                            <a href="#" class="flex items-center justify-center w-10 h-10 mx-auto text-gray-300 hover:bg-secondary-700 hover:text-white rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </a>
                            <!-- Tooltip -->
                            <div class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                Clientes Totales
                            </div>
                        </div> --}}

                            <!-- Proveedores -->
                            <div class="relative group">
                                <a href="{{ route('admin.suppliers.index') }}"
                                    class="flex items-center justify-center w-10 h-10 mx-auto text-gray-300 hover:bg-secondary-700 hover:text-white rounded-lg transition-colors">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </a>
                                <!-- Tooltip -->
                                <div
                                    class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                    Proveedores Totales
                                </div>
                            </div>

                            <!-- Aprobación -->
                            <div class="relative group">
                                <a href="#"
                                    class="flex items-center justify-center w-10 h-10 mx-auto text-gray-300 hover:bg-secondary-700 hover:text-white rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </a>
                                <!-- Tooltip -->
                                <div
                                    class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                    Aprobación Usuarios
                                </div>
                            </div>

                            <!-- Chats de Soporte -->
                            <div class="relative group">
                                <a href="#"
                                    class="flex items-center justify-center w-10 h-10 mx-auto text-gray-300 hover:bg-secondary-700 hover:text-white rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    <!-- Notification Badge -->
                                    <span
                                        class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full flex items-center justify-center">
                                        <span class="text-xs text-white font-bold">3</span>
                                    </span>
                                </a>
                                <!-- Tooltip -->
                                <div
                                    class="absolute left-full ml-2 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                                    Chats de Soporte (3)
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Mis Servicios - Solo para proveedores -->
                    @if (auth()->user()->role === 'supplier')
                        <div class="space-y-1" x-show="sidebarOpen" x-transition>
                            <button @click="openMenus.servicios = !openMenus.servicios"
                                class="flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:bg-secondary-700 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                    Mis Servicios
                                </div>
                                <svg class="w-4 h-4 transition-transform"
                                    :class="{ 'rotate-180': openMenus.servicios }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openMenus.servicios" x-transition class="ml-6 space-y-1">
                                <a href="{{ route('supplier.services.index') }}"
                                    class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('supplier.services.*') ? 'text-blue-400 bg-slate-700' : 'text-gray-400 hover:text-white hover:bg-secondary-700' }} rounded-lg">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    </svg>
                                    Ver Mis Servicios
                                </a>
                                <a href="{{ route('supplier.services.create') }}"
                                    class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('supplier.services.create') ? 'text-blue-400 bg-slate-700' : 'text-gray-400 hover:text-white hover:bg-secondary-700' }} rounded-lg">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Agregar Nuevo Servicio
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Gestión General - Oculto para admin -->
                    @if (auth()->user()->role != 'admin')
                        <div class="space-y-1" x-show="sidebarOpen" x-transition>
                            <button @click="openMenus.gestion = !openMenus.gestion"
                                class="flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:bg-secondary-700 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Gestión
                                </div>
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openMenus.gestion }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openMenus.gestion" x-transition class="ml-6 space-y-1">
                                <a href="{{ route('supplier.reviews.index') }}"
                                    class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('supplier.reviews.index') ? 'text-blue-400 bg-slate-700' : 'text-gray-400 hover:text-white hover:bg-secondary-700' }} rounded-lg">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>
                                    Ver Reseñas
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('profile.*') ? 'text-blue-400 bg-slate-700' : 'text-gray-400 hover:text-white hover:bg-secondary-700' }} rounded-lg">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Mi Perfil
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Chats - Solo para proveedores -->
                    @if (auth()->user()->role === 'supplier')
                        <div x-show="sidebarOpen" x-transition>
                            <a href="{{ route('chat.index') }}"
                                class="flex items-center px-4 py-3 {{ request()->routeIs('chat.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-secondary-700' }} rounded-lg">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                Chats
                            </a>
                        </div>
                    @endif

                    <!-- Opciones de Administrador -->
                    @if (auth()->user()->role == 'admin')
                        <div class="space-y-1" x-show="sidebarOpen" x-transition>
                            <div class="px-4 py-2 text-sm font-medium text-gray-400 uppercase tracking-wider">
                                Administración
                            </div>

                            <!-- Dashboard Admin -->
                            <a href="{{ route('admin.dashboard') }}"
                                class="flex items-center px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-secondary-700' }} rounded-lg">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Dashboard Admin
                            </a>

                            <!-- Estadísticas -->
                            {{-- <div class="ml-6 space-y-1">
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-secondary-700 rounded-lg">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Ganancias
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-secondary-700 rounded-lg">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                Clientes Totales
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-secondary-700 rounded-lg">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                </svg>
                                Proveedores Totales
                            </a>
                        </div> --}}

                            <a href="{{ route('admin.suppliers.index') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-secondary-700 rounded-lg">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Ver Proveedores Registrados
                            </a>

                            <!-- Gestión de Usuarios -->
                            {{-- <button @click="openMenus.usuarios = !openMenus.usuarios" class="flex items-center justify-between w-full px-4 py-3 text-gray-300 hover:bg-secondary-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Aprobación Usuarios
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': openMenus.usuarios}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.usuarios" x-transition class="ml-6 space-y-1">
                            <a href="{{ route('admin.suppliers.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-secondary-700 rounded-lg">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver Proveedores Registrados
                            </a>
                            <div class="ml-6 space-y-1">
                                <span class="block px-4 py-1 text-xs text-gray-500 uppercase tracking-wider">Acciones:</span>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-green-400 hover:text-green-300 hover:bg-secondary-700 rounded-lg">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Aprobar
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-secondary-700 rounded-lg">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Rechazar
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-yellow-400 hover:text-yellow-300 hover:bg-secondary-700 rounded-lg">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                    Dar de Baja
                                </a>
                            </div>
                        </div> --}}

                            <!-- Chats de Soporte -->
                            <a href="#"
                                class="flex items-center px-4 py-3 text-gray-300 hover:bg-secondary-700 rounded-lg">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                <div class="flex-1">
                                    Chats de Soporte
                                </div>
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">3</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Chats Section -->
                {{-- <div class="mt-8" x-show="sidebarOpen" x-transition>
                    <div class="px-4 py-2 text-sm font-medium text-gray-400 uppercase tracking-wider">
                        Chats Activos
                    </div>
                    <div class="mt-2 space-y-1">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-secondary-700 rounded-lg">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-semibold mr-3">
                                MG
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-300 truncate">María González</div>
                                <div class="text-xs text-gray-500 truncate">Boda 15 de Noviembre</div>
                            </div>
                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-secondary-700 rounded-lg">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-semibold mr-3">
                                CR
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-300 truncate">Carlos Rodríguez</div>
                                <div class="text-xs text-gray-500 truncate">Cumpleaños infantil</div>
                            </div>
                        </a>
                    </div>
                </div> --}}
            </nav>

        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Hola, {{ Auth::user()->name }}</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notification Icon -->
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-5-5 5-5h-5m-6 10v-2a6 6 0 00-6-6H2a6 6 0 006 6v2a2 2 0 002 2h2a2 2 0 002-2z">
                                </path>
                            </svg>
                        </button>
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-3 text-gray-700 hover:text-gray-900">
                                <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                                <div
                                    class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Mi Perfil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Configuración
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 p-6 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
