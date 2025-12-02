@extends('layouts.dashboard')

@section('title', 'Detalles del Proveedor')

@section('content')
    <div class="p-6">
        <!-- Header con navegación -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.suppliers.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $supplier->user->name }}</h1>
                    <p class="text-gray-600">Detalles del proveedor</p>
                </div>
            </div>

            <!-- Estado actual -->
            <div class="flex items-center space-x-4">
                @switch($supplier->status)
                    @case('pending')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Pendiente de Aprobación
                        </span>
                    @break

                    @case('approved')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Aprobado
                        </span>
                    @break

                    @case('rejected')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Rechazado
                        </span>
                    @break

                    @case('inactive')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Inactivo
                        </span>
                    @break
                @endswitch
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información del usuario -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información Personal</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre completo</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->user->phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de registro</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información del negocio -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Negocio</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categoría</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->category->name ?? 'Sin categoría' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->location }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rango de precios</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->price_range }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado del perfil</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($supplier->status) }}</p>
                        </div>
                    </div>

                    @if ($supplier->description)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Documentos -->
                @if ($supplier->documents && count($supplier->documents) > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Documentos</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($supplier->documents as $document)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $document['name'] ?? 'Documento' }}</p>
                                            <p class="text-xs text-gray-500">{{ $document['type'] ?? 'PDF' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ $document['path'] ?? '' }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800 text-sm">
                                            Ver documento
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Motivos de rechazo o baja -->
                @if ($supplier->rejection_reason)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-red-900 mb-4">Motivo del Rechazo</h2>
                        <p class="text-sm text-red-800">{{ $supplier->rejection_reason }}</p>
                    </div>
                @endif

                @if ($supplier->deactivation_reason)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-yellow-900 mb-4">Motivo de la Baja</h2>
                        <p class="text-sm text-yellow-800">{{ $supplier->deactivation_reason }}</p>
                    </div>
                @endif
            </div>

            <!-- Panel lateral de acciones -->
            <div class="space-y-6">
                <!-- Foto de identificación -->
                @if ($supplier->identification_photo)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Identificación</h3>
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="/storage/{{ $supplier->identification_photo }}"
                                alt="Identificación de {{ $supplier->user->name }}"
                                class="w-full h-48 object-cover rounded-lg border border-gray-200">
                        </div>
                    </div>
                @endif

                <!-- Acciones -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        @if ($supplier->status === 'pending')
                            <!-- Aprobar -->
                            <form method="POST" action="{{ route('admin.suppliers.approve', $supplier) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors"
                                    onclick="return confirm('¿Estás seguro de que quieres aprobar este proveedor?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Aprobar Proveedor
                                </button>
                            </form>

                            <!-- Rechazar -->
                            <button type="button"
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                                onclick="openRejectModal({{ $supplier->id }}, '{{ $supplier->user->name }}')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Rechazar Proveedor
                            </button>
                        @endif

                        @if ($supplier->status === 'approved')
                            <!-- Dar de baja -->
                            <button type="button"
                                class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors"
                                onclick="openDeactivateModal({{ $supplier->id }}, '{{ $supplier->user->name }}')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                                Dar de Baja
                            </button>
                        @endif

                        @if ($supplier->status === 'inactive')
                            <!-- Reactivar -->
                            <form method="POST" action="{{ route('admin.suppliers.reactivate', $supplier) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                    onclick="return confirm('¿Estás seguro de que quieres reactivar este proveedor?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Reactivar Proveedor
                                </button>
                            </form>
                        @endif

                        <!-- Contactar -->
                        <a href="mailto:{{ $supplier->user->email }}"
                            class="w-full flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Contactar por Email
                        </a>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Adicional</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email verificado:</span>
                            <span class="text-gray-900">
                                {{ $supplier->user->email_verified_at ? 'Sí' : 'No' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fecha de registro:</span>
                            <span class="text-gray-900">{{ $supplier->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Última actualización:</span>
                            <span class="text-gray-900">{{ $supplier->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para rechazar proveedor -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rechazar Proveedor</h3>
            <p class="text-sm text-gray-600 mb-4">
                ¿Estás seguro de que quieres rechazar a <span id="rejectSupplierName" class="font-semibold"></span>?
            </p>

            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="rejectReason" class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo del rechazo *
                    </label>
                    <textarea id="rejectReason" name="reason" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Explica el motivo del rechazo..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Rechazar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para dar de baja proveedor -->
    <div id="deactivateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Dar de Baja Proveedor</h3>
            <p class="text-sm text-gray-600 mb-4">
                ¿Estás seguro de que quieres dar de baja a <span id="deactivateSupplierName"
                    class="font-semibold"></span>?
            </p>

            <form id="deactivateForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="deactivateReason" class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo de la baja *
                    </label>
                    <textarea id="deactivateReason" name="reason" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                        placeholder="Explica el motivo de la baja..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeactivateModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                        Dar de Baja
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(supplierId, supplierName) {
            document.getElementById('rejectSupplierName').textContent = supplierName;
            document.getElementById('rejectForm').action = `/admin/suppliers/${supplierId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
            document.getElementById('rejectReason').value = '';
        }

        function openDeactivateModal(supplierId, supplierName) {
            document.getElementById('deactivateSupplierName').textContent = supplierName;
            document.getElementById('deactivateForm').action = `/admin/suppliers/${supplierId}/deactivate`;
            document.getElementById('deactivateModal').classList.remove('hidden');
            document.getElementById('deactivateModal').classList.add('flex');
        }

        function closeDeactivateModal() {
            document.getElementById('deactivateModal').classList.add('hidden');
            document.getElementById('deactivateModal').classList.remove('flex');
            document.getElementById('deactivateReason').value = '';
        }

        // Cerrar modales al hacer clic fuera
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });

        document.getElementById('deactivateModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeactivateModal();
            }
        });
    </script>
@endsection
