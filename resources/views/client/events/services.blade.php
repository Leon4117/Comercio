@extends('layouts.dashboard')

@section('title', 'Servicios para ' . $event->name)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Servicios Necesarios</h1>
                    <p class="text-gray-600 mt-1">Selecciona los tipos de servicio que deseas cotizar o buscar para este evento:</p>
                    <div class="mt-2 text-sm text-gray-500">
                        <strong>Evento:</strong> {{ $event->name }} - {{ $event->event_date->format('d/m/Y') }}
                    </div>
                </div>
                <a href="{{ route('client.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Selección de Categorías -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-8">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($categories as $category)
                <div class="category-card border border-gray-200 rounded-lg p-6 hover:border-secondary-300 hover:bg-secondary-50 cursor-pointer transition-colors"
                     data-category="{{ $category->id }}">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="categories[]" 
                               value="{{ $category->id }}"
                               class="h-5 w-5 text-secondary-600 focus:ring-secondary-500 border-gray-300 rounded">
                        <div class="ml-4 flex items-center">
                            @switch($category->name)
                                @case('Pastelería')
                                    <span class="text-3xl mr-3">🧁</span>
                                    @break
                                @case('Decoración')
                                    <span class="text-3xl mr-3">🏠</span>
                                    @break
                                @case('Florería')
                                    <span class="text-3xl mr-3">🌸</span>
                                    @break
                                @case('Fotografía/Video')
                                    <span class="text-3xl mr-3">📸</span>
                                    @break
                                @case('Mobiliario')
                                    <span class="text-3xl mr-3">🪑</span>
                                    @break
                                @case('Entretenimiento')
                                    <span class="text-3xl mr-3">🎭</span>
                                    @break
                                @default
                                    <span class="text-3xl mr-3">⭐</span>
                            @endswitch
                            <span class="text-lg font-medium text-gray-900">{{ $category->name }}</span>
                        </div>
                    </label>
                </div>
                @endforeach
            </div>

            <!-- Botón de Búsqueda -->
            <div class="mt-8 text-center">
                <button id="searchButton" 
                        class="inline-flex items-center px-8 py-4 bg-primary-500 text-white text-lg font-medium rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Buscar Proveedores y Cotizar
                </button>
                <p class="text-sm text-gray-500 mt-2">Selecciona al menos una categoría para continuar</p>
            </div>
        </div>

        <!-- Servicios Disponibles -->
        <div id="servicesContainer" class="hidden">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Servicios Disponibles</h2>
            
            <div id="servicesList" class="space-y-6">
                <!-- Los servicios se cargarán aquí dinámicamente -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="categories[]"]');
    const searchButton = document.getElementById('searchButton');
    const servicesContainer = document.getElementById('servicesContainer');
    const servicesList = document.getElementById('servicesList');

    // Habilitar/deshabilitar botón según selección
    function updateSearchButton() {
        const selectedCategories = Array.from(checkboxes).filter(cb => cb.checked);
        searchButton.disabled = selectedCategories.length === 0;
    }

    // Agregar event listeners a checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSearchButton);
    });

    // Inicializar estado del botón
    updateSearchButton();

    // Manejar búsqueda
    searchButton.addEventListener('click', function() {
        const selectedCategories = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        if (selectedCategories.length === 0) {
            alert('Por favor selecciona al menos una categoría');
            return;
        }

        // Mostrar loading
        searchButton.disabled = true;
        searchButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Buscando servicios...
        `;

        // Hacer petición AJAX para obtener servicios
        fetch(`/client/events/{{ $event->id }}/search-services`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                categories: selectedCategories
            })
        })
        .then(response => response.json())
        .then(data => {
            displayServices(data.services);
            servicesContainer.classList.remove('hidden');
            
            // Scroll suave hacia los resultados
            servicesContainer.scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al buscar servicios. Por favor intenta de nuevo.');
        })
        .finally(() => {
            // Restaurar botón
            searchButton.disabled = false;
            searchButton.innerHTML = `
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Buscar Proveedores y Cotizar
            `;
        });
    });

    function displayServices(services) {
        if (services.length === 0) {
            servicesList.innerHTML = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No se encontraron servicios</h3>
                    <p class="mt-2 text-sm text-gray-500">No hay servicios disponibles para las categorías seleccionadas.</p>
                </div>
            `;
            return;
        }

        const servicesHTML = services.map(service => `
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                ${service.main_image ? 
                                    `<img src="${service.main_image_url}" alt="${service.name}" class="w-20 h-20 rounded-lg object-cover">` :
                                    `<div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>`
                                }
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-medium text-gray-900">${service.name}</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    <strong>Proveedor:</strong> ${service.supplier.user.name}
                                </p>
                                <p class="text-sm text-gray-500 mb-3">${service.description.substring(0, 150)}...</p>
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="text-green-600 font-medium">$${parseFloat(service.base_price).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>
                                    ${service.urgent_available ? 
                                        `<span class="text-red-600 text-xs">+$${parseFloat(service.urgent_price_extra || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})} urgente</span>` : 
                                        ''
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2 ml-4">
                        <button onclick="requestService(${service.id})" 
                                class="px-4 py-2 bg-secondary-600 text-white text-sm rounded-lg hover:bg-secondary-700 transition-colors">
                            Solicitar Servicio
                        </button>
                        <a href="https://wa.me/${service.supplier.user.phone.replace(/[^0-9]/g, '')}" 
                           target="_blank"
                           class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors text-center">
                            💬 Chat
                        </a>
                    </div>
                </div>
            </div>
        `).join('');

        servicesList.innerHTML = servicesHTML;
    }

    // Función global para solicitar servicio
    window.requestService = function(serviceId) {
        if (confirm('¿Deseas solicitar este servicio para tu evento?')) {
            fetch('/client/request-service', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    event_id: {{ $event->id }},
                    service_id: serviceId,
                    urgent: false,
                    notes: ''
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Servicio solicitado exitosamente');
                    // Opcional: redirigir al dashboard
                    window.location.href = '{{ route("client.dashboard") }}';
                } else {
                    alert('Error al solicitar el servicio');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al solicitar el servicio');
            });
        }
    };
});
</script>
@endsection
