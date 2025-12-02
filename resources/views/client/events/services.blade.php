@extends('layouts.dashboard')
{{-- Force view cache clear --}}

@section('title', 'Servicios para ' . $event->name)

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Buscar Proveedores</h1>
                        <p class="text-gray-600 mt-1">Selecciona las categorías de servicio que necesitas para tu evento</p>
                        <div class="mt-2 text-sm text-gray-500">
                            <strong>Evento:</strong> {{ $event->name }} - {{ $event->event_date->format('d/m/Y') }}
                        </div>
                    </div>
                    <a href="{{ route('client.dashboard') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al Dashboard
                    </a>
                </div>
            </div>

            <!-- Selección de Categorías -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-8">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($categories as $category)
                        <div class="category-card border border-gray-200 rounded-lg p-6 hover:border-secondary-300 hover:bg-secondary-50 cursor-pointer transition-colors"
                            data-category="{{ $category->id }}">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Buscar Proveedores y Cotizar
                    </button>
                    <p class="text-sm text-gray-500 mt-2">Selecciona al menos una categoría para continuar</p>
                </div>
            </div>

            <!-- Proveedores Disponibles -->
            <div id="suppliersContainer" class="hidden">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Proveedores Disponibles</h2>

                <div id="suppliersList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Los proveedores se cargarán aquí dinámicamente -->
                </div>
            </div>

            <!-- Modal de Servicios del Proveedor -->
            <div id="supplierModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white mb-10">
                    <div class="mt-3">
                        <!-- El contenido del proveedor se cargará aquí -->
                        <div id="supplierModalContent"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación -->
        <div id="confirmationModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[60]">
            <div class="relative top-1/4 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Confirmar Solicitud</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">¿Deseas solicitar este servicio para tu evento?</p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button id="confirmBtn"
                            class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Solicitar
                        </button>
                        <button onclick="closeConfirmationModal()"
                            class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Container -->
        <div id="toastContainer" class="fixed bottom-5 right-5 z-[70] flex flex-col space-y-2"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="categories[]"]');
            const searchButton = document.getElementById('searchButton');
            const suppliersContainer = document.getElementById('suppliersContainer');
            const suppliersList = document.getElementById('suppliersList');
            const supplierModal = document.getElementById('supplierModal');
            const supplierModalContent = document.getElementById('supplierModalContent');
            const confirmationModal = document.getElementById('confirmationModal');
            const confirmBtn = document.getElementById('confirmBtn');
            const toastContainer = document.getElementById('toastContainer');
            let serviceToRequest = null;

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
                    showToast('Por favor selecciona al menos una categoría', 'error');
                    return;
                }

                // Mostrar loading
                searchButton.disabled = true;
                searchButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Buscando proveedores...
        `;

                // Hacer petición AJAX para obtener proveedores
                fetch(`/client/events/{{ $event->id }}/search-services`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            categories: selectedCategories
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        displaySuppliers(data.suppliers);
                        suppliersContainer.classList.remove('hidden');

                        // Scroll suave hacia los resultados
                        suppliersContainer.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Error al buscar proveedores. Por favor intenta de nuevo.', 'error');
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

            function displaySuppliers(suppliers) {
                if (suppliers.length === 0) {
                    suppliersList.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No se encontraron proveedores</h3>
                    <p class="mt-2 text-sm text-gray-500">No hay proveedores disponibles para las categorías seleccionadas.</p>
                </div>
            `;
                    return;
                }

                const suppliersHTML = suppliers.map(supplier => `
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer" onclick="showSupplierDetails(${supplier.id}, ${JSON.stringify(supplier).replace(/"/g, '&quot;')})">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">${supplier.user.name}</h3>
                        <p class="text-sm text-gray-600">${supplier.category}</p>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-700">${supplier.average_rating || 'N/A'}</span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">${supplier.description || 'Sin descripción'}</p>
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>📍 ${supplier.location || 'No especificado'}</span>
                    <span class="text-green-600 font-medium">${supplier.price_range || 'Consultar'}</span>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <span class="text-sm text-gray-600">${supplier.services.length} servicio(s) disponible(s)</span>
                </div>
            </div>
        `).join('');

                suppliersList.innerHTML = suppliersHTML;
            }

            // Función para obtener detalles actualizados del proveedor
            window.fetchSupplierDetails = function(supplierId) {
                fetch(`/client/suppliers/${supplierId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.supplier) {
                            showSupplierDetails(supplierId, data.supplier);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching supplier details:', error);
                    });
            };

            // Función global para mostrar detalles del proveedor
            window.showSupplierDetails = function(supplierId, supplierData) {
                const supplier = typeof supplierData === 'string' ? JSON.parse(supplierData) : supplierData;

                const modalHTML = `
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">${supplier.user.name}</h2>
                    <p class="text-gray-600">${supplier.category}</p>
                </div>
                <button onclick="closeSupplierModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-gray-700 mb-2">${supplier.description || 'Sin descripción'}</p>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span>📍 ${supplier.location || 'No especificado'}</span>
                    <span>💰 ${supplier.price_range || 'Consultar'}</span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        ${supplier.average_rating || 'N/A'}
                    </span>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-900 mb-4">Servicios Disponibles</h3>
            <div class="space-y-4 max-h-96 overflow-y-auto mb-8">
                ${supplier.services.map(service => `
                                                        <div class="bg-white border border-gray-200 rounded-lg p-4">
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
                                                                        <div class="flex-1">
                                                                            <h4 class="text-lg font-medium text-gray-900">${service.name}</h4>
                                                                            <p class="text-sm text-gray-500 mt-1">${service.description.substring(0, 100)}...</p>
                                                                            <div class="flex items-center space-x-4 mt-2 text-sm">
                                                                                <span class="text-green-600 font-medium">$${parseFloat(service.base_price).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>
                                                                                ${service.urgent_available ?
                                                                                    `<span class="text-red-600 text-xs">+$${parseFloat(service.urgent_price_extra || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})} urgente</span>` :
                                                                                    ''
                                                                                }
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="ml-4">
                                                                    <button onclick="requestService(${service.id})"
                                                                            class="px-4 py-2 bg-secondary-600 text-white text-sm rounded-lg hover:bg-secondary-700 transition-colors">
                                                                        Solicitar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    `).join('')}
            </div>

            <!-- Reviews Section -->
            <div class="border-t border-gray-200 pt-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Reseñas y Calificaciones</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <!-- Rating Summary -->
                    <div class="bg-yellow-50 rounded-xl p-6 text-center">
                        <div class="text-5xl font-bold text-yellow-500 mb-2">${supplier.average_rating || '0.0'}</div>
                        <div class="flex justify-center mb-2">
                            ${[1, 2, 3, 4, 5].map(star => `
                                            <svg class="w-6 h-6 ${star <= Math.round(supplier.average_rating || 0) ? 'text-yellow-400' : 'text-gray-300'}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        `).join('')}
                        </div>
                        <p class="text-sm text-gray-600">Basado en ${supplier.reviews_count} opiniones</p>
                    </div>

                    <!-- Rating Distribution -->
                    <div class="col-span-2 space-y-2">
                        ${[5, 4, 3, 2, 1].map(star => {
                            const count = supplier.rating_counts ? supplier.rating_counts[star] : 0;
                            const percentage = supplier.reviews_count > 0 ? (count / supplier.reviews_count) * 100 : 0;
                            return `
                                            <div class="flex items-center text-sm">
                                                <span class="w-4 text-gray-600">${star}</span>
                                                <svg class="w-4 h-4 text-yellow-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden ml-2">
                                                    <div class="h-full bg-indigo-500 rounded-full" style="width: ${percentage}%"></div>
                                                </div>
                                                <span class="w-10 text-right text-gray-500 ml-2">${count}</span>
                                            </div>
                                        `;
                        }).join('')}
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="space-y-6 mb-8 max-h-96 overflow-y-auto">
                    ${supplier.reviews && supplier.reviews.length > 0 ? supplier.reviews.map(review => `
                                    <div class="border-b border-gray-100 pb-6 last:border-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                                    ${review.user_name.charAt(0)}
                                                </div>
                                                <div class="ml-3">
                                                    <h4 class="text-sm font-bold text-gray-900">${review.user_name}</h4>
                                                    <div class="flex items-center">
                                                        ${[1, 2, 3, 4, 5].map(star => `
                                                <svg class="w-3 h-3 ${star <= review.rating ? 'text-yellow-400' : 'text-gray-300'}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            `).join('')}
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-500">${review.time_ago}</span>
                                        </div>
                                        <p class="text-gray-600 text-sm pl-13">${review.comment || ''}</p>
                                    </div>
                                `).join('') : '<p class="text-gray-500 text-center py-4">Aún no hay reseñas para este proveedor.</p>'}
                </div>

                <!-- Leave Review Form -->
                ${supplier.can_review ? `
                                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                    <h4 class="text-lg font-bold text-indigo-600 mb-4">¿Ya usaste este servicio? ¡Deja tu Reseña!</h4>
                                    <form id="reviewForm" onsubmit="submitReview(event, ${supplier.id})">
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tu Puntuación</label>
                                            <div class="flex items-center space-x-1">
                                                ${[1, 2, 3, 4, 5].map(star => `
                                        <button type="button" onclick="setRating(${star})" class="focus:outline-none transition-transform hover:scale-110">
                                            <svg id="star-${star}" class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        </button>
                                    `).join('')}
                                            </div>
                                            <input type="hidden" id="ratingInput" name="rating" value="0">
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tu Comentario</label>
                                            <textarea name="comment" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Escribe tu opinión honesta aquí..."></textarea>
                                        </div>
                                        <button type="submit" class="w-full bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                                            Enviar Reseña
                                        </button>
                                    </form>
                                </div>
                            ` : ''}
            </div>

            <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-200">
                <a href="/chat/start/${supplier.user.id}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    💬 Chat con el proveedor
                </a>
                <button onclick="closeSupplierModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cerrar
                </button>
            </div>
        `;

                supplierModalContent.innerHTML = modalHTML;
                supplierModal.classList.remove('hidden');
            };

            // Función global para cerrar modal
            window.closeSupplierModal = function() {
                supplierModal.classList.add('hidden');
            };

            // Cerrar modal al hacer clic fuera
            supplierModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeSupplierModal();
                }
            });

            // Función global para solicitar servicio
            // Funciones para el Modal de Confirmación
            window.closeConfirmationModal = function() {
                confirmationModal.classList.add('hidden');
                serviceToRequest = null;
            };

            window.requestService = function(serviceId) {
                serviceToRequest = serviceId;
                confirmationModal.classList.remove('hidden');
            };

            confirmBtn.addEventListener('click', function() {
                if (!serviceToRequest) return;

                const serviceId = serviceToRequest;
                // Deshabilitar botón para evitar doble click
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = 'Solicitando...';

                fetch('/client/request-service', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
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
                            showToast('Servicio solicitado exitosamente', 'success');
                            closeConfirmationModal();
                            closeSupplierModal();
                            // Esperar un momento antes de redirigir para que se vea el toast
                            setTimeout(() => {
                                window.location.href = '{{ route('client.dashboard') }}';
                            }, 1500);
                        } else {
                            showToast(data.message || 'Error al solicitar el servicio', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Error al solicitar el servicio', 'error');
                    })
                    .finally(() => {
                        confirmBtn.disabled = false;
                        confirmBtn.innerHTML = 'Solicitar';
                    });
            });

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

            // Funciones para reseñas
            window.setRating = function(rating) {
                document.getElementById('ratingInput').value = rating;
                for (let i = 1; i <= 5; i++) {
                    const star = document.getElementById(`star-${i}`);
                    if (i <= rating) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                }
            };

            window.submitReview = function(event, supplierId) {
                event.preventDefault();
                const form = event.target;
                const formData = new FormData(form);
                const rating = formData.get('rating');
                const comment = formData.get('comment');

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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
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
                            // Actualizar el contenido del modal para mostrar la nueva reseña
                            fetchSupplierDetails(supplierId);
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
        });
    </script>
@endsection
