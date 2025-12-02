@extends('layouts.dashboard')

@section('title', 'Mis Eventos')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <span class="text-blue-600">🎉</span> Mis Eventos
                    </h1>
                    <p class="text-gray-600 mt-1">Gestiona tus eventos y servicios contratados</p>
                </div>
                <a href="{{ route('client.events.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Planear Nuevo Evento
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar - Lista de Eventos -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">📅 Mis Eventos</h2>
                        <a href="{{ route('client.events.create') }}"
                            class="text-primary-500 hover:text-primary-600 text-sm font-medium">
                            + Nuevo
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($events as $event)
                            <div class="p-3 rounded-lg border {{ request('event') == $event->id ? 'border-secondary-500 bg-secondary-50' : 'border-gray-200 hover:border-gray-300' }} cursor-pointer transition-colors"
                                onclick="showEvent({{ $event->id }})">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $event->name }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $event->event_date->format('d/m/Y') }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $event->location }}
                                        </p>
                                    </div>
                                    @if ($event->getDaysUntilEvent() >= 0)
                                        <span class="text-xs bg-accent-100 text-accent-800 px-2 py-1 rounded-full">
                                            {{ $event->getDaysUntilEvent() }} días
                                        </span>
                                    @else
                                        <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded-full">
                                            Pasado
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-sm text-gray-500 mt-2">No tienes eventos creados</p>
                                <a href="{{ route('client.events.create') }}"
                                    class="text-orange-500 hover:text-orange-600 text-sm font-medium">
                                    Crear tu primer evento
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="lg:col-span-3">
                @if ($selectedEvent)
                    <div id="event-details" data-event-id="{{ $selectedEvent->id }}">
                        <!-- Header del Evento -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">{{ $selectedEvent->name }}</h1>
                                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            {{ $selectedEvent->event_date->format('l, d \d\e F Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $selectedEvent->location }}
                                        </span>
                                        @if ($selectedEvent->budget)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                    </path>
                                                </svg>
                                                ${{ number_format($selectedEvent->budget, 2) }} MXN
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if ($selectedEvent->getDaysUntilEvent() >= 0)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            🔵 Evento Pendiente
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            Evento Pasado
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Servicios Solicitados -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <h2 class="text-lg font-semibold text-gray-900">Servicios Solicitados</h2>

                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $currentStatus = request('status', 'all');
                                            $statuses = [
                                                'all' => 'Todos',
                                                'requested' => 'Solicitados',

                                                'confirmed' => 'Confirmados',
                                                'delivered' => 'Entregados',
                                                'completed' => 'Completados',
                                                'cancelled' => 'Cancelados',
                                            ];
                                        @endphp

                                        @foreach ($statuses as $key => $label)
                                            <button onclick="filterServices('{{ $key }}')"
                                                class="px-3 py-1 rounded-full text-xs font-medium transition-colors {{ $currentStatus == $key ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                {{ $label }}
                                            </button>
                                        @endforeach
                                    </div>

                                    @if ($selectedEvent->getDaysUntilEvent() >= 0)
                                        <a href="{{ route('client.events.services', $selectedEvent) }}"
                                            class="text-blue-600 hover:text-blue-700 text-sm font-medium whitespace-nowrap">
                                            + Agregar Servicios
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6">
                                @forelse($filteredServices as $eventService)
                                    <div class="border border-gray-200 rounded-lg p-4 mb-4 last:mb-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <!-- Información del Servicio -->
                                                <div class="flex items-start space-x-4">
                                                    <div class="flex-shrink-0">
                                                        @if ($eventService->service->main_image)
                                                            <img src="{{ $eventService->service->getMainImageUrl() }}"
                                                                alt="{{ $eventService->service->name }}"
                                                                class="w-16 h-16 rounded-lg object-cover">
                                                        @else
                                                            <div
                                                                class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                                <svg class="w-8 h-8 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h3 class="text-lg font-medium text-gray-900">
                                                            {{ $eventService->service->name }}</h3>
                                                        <p class="text-sm text-gray-600 mb-2">
                                                            <strong>Proveedor:</strong>
                                                            {{ $eventService->supplier->user->name }}
                                                        </p>
                                                        @if ($eventService->quoted_price)
                                                            <p class="text-sm text-gray-600">
                                                                <strong>Cotizado:</strong>
                                                                ${{ number_format($eventService->quoted_price, 2) }} MXN
                                                            </p>
                                                        @endif
                                                        @if ($eventService->notes)
                                                            <p class="text-sm text-gray-500 mt-1">
                                                                {{ $eventService->notes }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Estado y Acciones -->
                                            <div class="flex flex-col items-end space-y-2">
                                                <!-- Estado -->
                                                @php $statusColor = $eventService->getStatusColor(); @endphp
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                                    {{ $eventService->getStatusInSpanish() }}
                                                </span>

                                                @if ($eventService->urgent)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        🔴 Urgente
                                                    </span>
                                                @endif

                                                <!-- Acciones -->
                                                <div class="flex space-x-2">
                                                    @if ($eventService->status === 'delivered')
                                                        <form
                                                            action="{{ route('client.confirm-delivery', $eventService) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                class="text-green-600 hover:text-green-700 text-sm font-medium">
                                                                ✅ Confirmar Entrega
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if (in_array($eventService->status, ['requested', 'quoted']))
                                                        <form action="{{ route('client.cancel-service', $eventService) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="button"
                                                                class="text-red-600 hover:text-red-700 text-sm font-medium"
                                                                onclick="confirmCancellation(this)">
                                                                ❌ Cancelar
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Botón de chat -->
                                                    @if ($eventService->status !== 'cancelled')
                                                        <a href="{{ route('chat.start', $eventService->supplier->user) }}"
                                                            class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                                            💬 Chat
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">No hay servicios solicitados
                                        </h3>
                                        <p class="mt-2 text-sm text-gray-500">Comienza agregando servicios para tu evento.
                                        </p>
                                        <div class="mt-6">
                                            @if ($selectedEvent->getDaysUntilEvent() >= 0)
                                                <a href="{{ route('client.events.services', $selectedEvent) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Agregar Servicios
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Estado vacío -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">¡Bienvenido a Festivando!</h3>
                        <p class="mt-2 text-sm text-gray-500">Comienza creando tu primer evento y solicita servicios de
                            proveedores locales.</p>
                        <div class="mt-6">
                            <a href="{{ route('client.events.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Planear Primer Evento
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="confirmationModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[60]">
        <div class="relative top-1/4 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Confirmar Cancelación</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">¿Estás seguro de cancelar este servicio? Esta acción no se puede
                        deshacer.</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmBtn"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Sí, Cancelar
                    </button>
                    <button onclick="closeConfirmationModal()"
                        class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        No, Regresar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed bottom-5 right-5 z-[70] flex flex-col space-y-2"></div>
    </div>

    <!-- Modal de Reseña -->
    <div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[60]">
        <div class="relative top-1/4 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-indigo-600">¿Ya usaste este servicio? ¡Deja tu Reseña!</h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="dashboardReviewForm" onsubmit="submitDashboardReview(event)">
                <input type="hidden" id="reviewSupplierId" name="supplier_id">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tu Puntuación</label>
                    <div class="flex items-center space-x-1">
                        <button type="button" onclick="setDashboardRating(1)"
                            class="focus:outline-none transition-transform hover:scale-110">
                            <svg id="d-star-1" class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        </button>
                        <button type="button" onclick="setDashboardRating(2)"
                            class="focus:outline-none transition-transform hover:scale-110">
                            <svg id="d-star-2" class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        </button>
                        <button type="button" onclick="setDashboardRating(3)"
                            class="focus:outline-none transition-transform hover:scale-110">
                            <svg id="d-star-3" class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        </button>
                        <button type="button" onclick="setDashboardRating(4)"
                            class="focus:outline-none transition-transform hover:scale-110">
                            <svg id="d-star-4" class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        </button>
                        <button type="button" onclick="setDashboardRating(5)"
                            class="focus:outline-none transition-transform hover:scale-110">
                            <svg id="d-star-5" class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" id="dashboardRatingInput" name="rating" value="0">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tu Comentario</label>
                    <textarea name="comment" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Escribe tu opinión honesta aquí..."></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                    Enviar Reseña
                </button>
            </form>
        </div>
    </div>

    <script>
        function showEvent(eventId) {
            const url = new URL(window.location.href);
            url.searchParams.set('event', eventId);
            // Reset status when changing event
            url.searchParams.delete('status');
            window.location.href = url.toString();
        }

        function filterServices(status) {
            const url = new URL(window.location.href);
            url.searchParams.set('status', status);
            window.location.href = url.toString();
        }

        // Variables para el modal
        let formToSubmit = null;
        const confirmationModal = document.getElementById('confirmationModal');
        const confirmBtn = document.getElementById('confirmBtn');
        const toastContainer = document.getElementById('toastContainer');

        // Variables para el modal de reseña
        const reviewModal = document.getElementById('reviewModal');
        const reviewSupplierIdInput = document.getElementById('reviewSupplierId');

        // Función para abrir modal de cancelación
        window.confirmCancellation = function(button) {
            formToSubmit = button.closest('form');
            confirmationModal.classList.remove('hidden');
        };

        // Función para cerrar modal
        window.closeConfirmationModal = function() {
            confirmationModal.classList.add('hidden');
            formToSubmit = null;
        };

        // Confirmar acción
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
            });
        }

        // Cerrar modal al hacer clic fuera
        if (confirmationModal) {
            confirmationModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeConfirmationModal();
                }
            });
        }

        // Funciones para el modal de reseña
        window.openReviewModal = function(supplierId) {
            reviewSupplierIdInput.value = supplierId;
            reviewModal.classList.remove('hidden');
        };

        window.closeReviewModal = function() {
            reviewModal.classList.add('hidden');
        };

        window.setDashboardRating = function(rating) {
            document.getElementById('dashboardRatingInput').value = rating;
            for (let i = 1; i <= 5; i++) {
                const star = document.getElementById(`d-star-${i}`);
                if (i <= rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            }
        };

        window.submitDashboardReview = function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const rating = formData.get('rating');
            const comment = formData.get('comment');
            const supplierId = formData.get('supplier_id');

            if (rating == 0) {
                showToast('Por favor selecciona una puntuación', 'error');
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerText;
            submitBtn.disabled = true;
            submitBtn.innerText = 'Enviando...';

            fetch('{{ route('client.reviews.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        supplier_id: supplierId,
                        rating: rating,
                        comment: comment
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Reseña enviada exitosamente', 'success');
                        closeReviewModal();
                    } else {
                        showToast(data.message || 'Error al enviar la reseña', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error al enviar la reseña', 'error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = originalText;
                });
        };

        // Función para mostrar Toasts
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ?
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' :
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';

            toast.className =
                `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform transition-all duration-300 translate-y-10 opacity-0`;
            toast.innerHTML = `
                ${icon}
                <span class="font-medium">${message}</span>
            `;

            toastContainer.appendChild(toast);

            // Animar entrada
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
            });

            // Eliminar después de 3 segundos
            setTimeout(() => {
                toast.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const selectedEvent = urlParams.get('event');

            if (selectedEvent) {
                console.log('Selected event:', selectedEvent);
            }

            // Mostrar mensajes de sesión como toasts
            @if (session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
            @if (session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif

            // Check if we need to show review modal
            @if (session('review_supplier_id'))
                openReviewModal({{ session('review_supplier_id') }});
            @endif
        });
    </script>
@endsection
