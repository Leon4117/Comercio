@extends('layouts.dashboard')

@section('title', 'Organiza tu Evento')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                <span class="text-2xl">📅</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Organiza tu Evento</h1>
            <p class="text-gray-600">Dinos qué, cuándo y dónde. Empezaremos a buscar los mejores servicios.</p>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <form action="{{ route('client.events.store') }}" method="POST" id="eventForm">
                @csrf

                <!-- Detalles Principales -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Detalles Principales</h2>
                    
                    <!-- Nombre del Evento -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Evento
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition duration-200"
                               placeholder="Ej: XV Años de María, Bautizo de Juan">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Fecha del Evento -->
                        <div>
                            <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha del Evento
                            </label>
                            <input type="date" 
                                   id="event_date" 
                                   name="event_date" 
                                   value="{{ old('event_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   required
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition duration-200">
                            @error('event_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ubicación -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Ubicación
                            </label>
                            <input type="text" 
                                   id="location" 
                                   name="location" 
                                   value="{{ old('location', 'San Luis Potosí') }}"
                                   required
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition duration-200"
                                   placeholder="San Luis Potosí">
                            <p class="mt-1 text-xs text-gray-500">Inicialmente solo disponible en esta ciudad.</p>
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- Presupuesto Estimado -->
                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">
                                Presupuesto Estimado (Opcional)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       id="budget" 
                                       name="budget" 
                                       value="{{ old('budget') }}"
                                       step="100" 
                                       min="0"
                                       class="block w-full pl-7 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition duration-200"
                                       placeholder="5000">
                            </div>
                            @error('budget')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Número de Invitados -->
                        <div>
                            <label for="guests_count" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Invitados (Opcional)
                            </label>
                            <input type="number" 
                                   id="guests_count" 
                                   name="guests_count" 
                                   value="{{ old('guests_count') }}"
                                   min="1"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition duration-200"
                                   placeholder="50">
                            @error('guests_count')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Detalles Adicionales -->
                <div class="mb-8">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Detalles Adicionales
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition duration-200"
                              placeholder="Ej: Número de invitados, temática, o cualquier solicitud especial.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Servicios Necesarios -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Servicios Necesarios</h3>
                    <p class="text-sm text-gray-600 mb-4">Selecciona los tipos de servicio que deseas cotizar o buscar para este evento:</p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($categories as $category)
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-secondary-300 hover:bg-secondary-50 cursor-pointer transition-colors">
                            <input type="checkbox" 
                                   name="categories[]" 
                                   value="{{ $category->id }}"
                                   class="h-4 w-4 text-secondary-600 focus:ring-secondary-500 border-gray-300 rounded">
                            <div class="ml-3 flex items-center">
                                @switch($category->name)
                                    @case('Pastelería')
                                        <span class="text-pink-500 mr-2">🧁</span>
                                        @break
                                    @case('Decoración')
                                        <span class="text-green-500 mr-2">🏠</span>
                                        @break
                                    @case('Florería')
                                        <span class="text-red-500 mr-2">🌸</span>
                                        @break
                                    @case('Fotografía/Video')
                                        <span class="text-blue-500 mr-2">📸</span>
                                        @break
                                    @case('Mobiliario')
                                        <span class="text-gray-500 mr-2">🪑</span>
                                        @break
                                    @case('Entretenimiento')
                                        <span class="text-yellow-500 mr-2">🎭</span>
                                        @break
                                    @default
                                        <span class="text-gray-500 mr-2">⭐</span>
                                @endswitch
                                <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('client.dashboard') }}" 
                       class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 text-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-primary-500 text-white rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buscar Proveedores y Cotizar
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validación de ubicación
document.getElementById('location').addEventListener('input', function(e) {
    const value = e.target.value.toLowerCase();
    if (!value.includes('san luis potosí') && !value.includes('slp')) {
        e.target.setCustomValidity('Por ahora solo estamos disponibles en San Luis Potosí');
    } else {
        e.target.setCustomValidity('');
    }
});

// Validación de fecha
document.getElementById('event_date').addEventListener('change', function(e) {
    const selectedDate = new Date(e.target.value);
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    if (selectedDate < tomorrow) {
        e.target.setCustomValidity('La fecha del evento debe ser al menos mañana');
    } else {
        e.target.setCustomValidity('');
    }
});

// Mejorar UX del formulario
document.getElementById('eventForm').addEventListener('submit', function(e) {
    const submitButton = e.target.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.innerHTML = `
        <span class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Creando evento...
        </span>
    `;
});
</script>
@endsection
