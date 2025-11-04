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
                            @if($event->getDaysUntilEvent() >= 0)
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
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
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
            @if($events->count() > 0)
                @php $firstEvent = $events->first(); @endphp
                <div id="event-details" data-event-id="{{ $firstEvent->id }}">
                    <!-- Header del Evento -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $firstEvent->name }}</h1>
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $firstEvent->event_date->format('l, d \d\e F Y') }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $firstEvent->location }}
                                    </span>
                                    @if($firstEvent->budget)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        ${{ number_format($firstEvent->budget, 2) }} MXN
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($firstEvent->getDaysUntilEvent() >= 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        🔵 Evento Pendiente
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        Evento Pasado
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Servicios Solicitados -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Servicios Solicitados</h2>
                                <a href="{{ route('client.events.services', $firstEvent) }}" 
                                   class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    + Agregar Servicios
                                </a>
                            </div>
                        </div>

                        <div class="p-6">
                            @forelse($firstEvent->eventServices as $eventService)
                            <div class="border border-gray-200 rounded-lg p-4 mb-4 last:mb-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <!-- Información del Servicio -->
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                @if($eventService->service->main_image)
                                                    <img src="{{ $eventService->service->getMainImageUrl() }}" 
                                                         alt="{{ $eventService->service->name }}" 
                                                         class="w-16 h-16 rounded-lg object-cover">
                                                @else
                                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-medium text-gray-900">{{ $eventService->service->name }}</h3>
                                                <p class="text-sm text-gray-600 mb-2">
                                                    <strong>Proveedor:</strong> {{ $eventService->supplier->user->name }}
                                                </p>
                                                @if($eventService->quoted_price)
                                                    <p class="text-sm text-gray-600">
                                                        <strong>Cotizado:</strong> ${{ number_format($eventService->quoted_price, 2) }} MXN
                                                    </p>
                                                @endif
                                                @if($eventService->notes)
                                                    <p class="text-sm text-gray-500 mt-1">{{ $eventService->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Estado y Acciones -->
                                    <div class="flex flex-col items-end space-y-2">
                                        <!-- Estado -->
                                        @php $statusColor = $eventService->getStatusColor(); @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                            {{ $eventService->getStatusInSpanish() }}
                                        </span>

                                        @if($eventService->urgent)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                🔴 Urgente
                                            </span>
                                        @endif

                                        <!-- Acciones -->
                                        <div class="flex space-x-2">
                                            @if($eventService->status === 'delivered')
                                                <form action="{{ route('client.confirm-delivery', $eventService) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="text-green-600 hover:text-green-700 text-sm font-medium">
                                                        ✅ Confirmar Entrega
                                                    </button>
                                                </form>
                                            @endif

                                            @if(in_array($eventService->status, ['requested', 'quoted', 'confirmed']))
                                                <form action="{{ route('client.cancel-service', $eventService) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-700 text-sm font-medium"
                                                            onclick="return confirm('¿Estás seguro de cancelar este servicio?')">
                                                        ❌ Cancelar
                                                    </button>
                                                </form>
                                            @endif

                                            @if($eventService->chat_link)
                                                <a href="{{ $eventService->chat_link }}" 
                                                   target="_blank"
                                                   class="text-green-600 hover:text-green-700 text-sm font-medium">
                                                    💬 Chat
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No hay servicios solicitados</h3>
                                <p class="mt-2 text-sm text-gray-500">Comienza agregando servicios para tu evento.</p>
                                <div class="mt-6">
                                    <a href="{{ route('client.events.services', $firstEvent) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Agregar Servicios
                                    </a>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <!-- Estado vacío -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">¡Bienvenido a Festivando!</h3>
                    <p class="mt-2 text-sm text-gray-500">Comienza creando tu primer evento y solicita servicios de proveedores locales.</p>
                    <div class="mt-6">
                        <a href="{{ route('client.events.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Planear Primer Evento
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function showEvent(eventId) {
    // Aquí podrías implementar AJAX para cargar el evento específico
    // Por ahora, simplemente recargamos la página con el parámetro del evento
    window.location.href = "{{ route('client.dashboard') }}?event=" + eventId;
}

// Marcar el evento seleccionado si viene en la URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const selectedEvent = urlParams.get('event');
    
    if (selectedEvent) {
        // Aquí podrías cargar el evento específico via AJAX
        console.log('Selected event:', selectedEvent);
    }
});
</script>
@endsection
