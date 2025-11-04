@extends('layouts.dashboard')

@section('title', 'Gestión de Proveedores')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestión de Proveedores</h1>
            <p class="text-gray-600 mt-1">Administra y aprueba proveedores registrados en la plataforma</p>
        </div>
        
        <!-- Estadísticas rápidas -->
        <div class="flex space-x-4">
            <div class="bg-blue-100 px-4 py-2 rounded-lg">
                <span class="text-blue-800 font-semibold">{{ $suppliers->total() }}</span>
                <span class="text-blue-600 text-sm ml-1">Total</span>
            </div>
            <div class="bg-yellow-100 px-4 py-2 rounded-lg">
                <span class="text-yellow-800 font-semibold">{{ $suppliers->where('status', 'pending')->count() }}</span>
                <span class="text-yellow-600 text-sm ml-1">Pendientes</span>
            </div>
            <div class="bg-green-100 px-4 py-2 rounded-lg">
                <span class="text-green-800 font-semibold">{{ $suppliers->where('status', 'approved')->count() }}</span>
                <span class="text-green-600 text-sm ml-1">Aprobados</span>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.suppliers.index') }}" class="flex flex-wrap gap-4 items-end">
            <!-- Búsqueda -->
            <div class="flex-1 min-w-64">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar proveedor</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nombre o email del proveedor..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Filtro por estado -->
            <div class="min-w-48">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select id="status" 
                        name="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Todos los estados</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendientes</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprobados</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rechazados</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="flex space-x-2">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Buscar
                </button>
                <a href="{{ route('admin.suppliers.index') }}" 
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de proveedores -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($suppliers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Proveedor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoría
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Registro
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($suppliers as $supplier)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                {{ strtoupper(substr($supplier->user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $supplier->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $supplier->user->email }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $supplier->user->phone }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $supplier->category->name ?? 'Sin categoría' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $supplier->location }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($supplier->status)
                                    @case('pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Pendiente
                                        </span>
                                        @break
                                    @case('approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Aprobado
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Rechazado
                                        </span>
                                        @break
                                    @case('inactive')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Inactivo
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $supplier->created_at->format('d/m/Y') }}
                                <div class="text-xs text-gray-400">
                                    {{ $supplier->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Ver detalles -->
                                    <a href="{{ route('admin.suppliers.show', $supplier) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors"
                                       title="Ver detalles">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    @if($supplier->status === 'pending')
                                        <!-- Aprobar -->
                                        <form method="POST" action="{{ route('admin.suppliers.approve', $supplier) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-green-600 hover:text-green-900 transition-colors"
                                                    title="Aprobar proveedor"
                                                    onclick="return confirm('¿Estás seguro de que quieres aprobar este proveedor?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Rechazar -->
                                        <button type="button" 
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Rechazar proveedor"
                                                onclick="openRejectModal({{ $supplier->id }}, '{{ $supplier->user->name }}')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    @endif

                                    @if($supplier->status === 'approved')
                                        <!-- Dar de baja -->
                                        <button type="button" 
                                                class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                                title="Dar de baja"
                                                onclick="openDeactivateModal({{ $supplier->id }}, '{{ $supplier->user->name }}')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                            </svg>
                                        </button>
                                    @endif

                                    @if($supplier->status === 'inactive')
                                        <!-- Reactivar -->
                                        <form method="POST" action="{{ route('admin.suppliers.reactivate', $supplier) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-blue-600 hover:text-blue-900 transition-colors"
                                                    title="Reactivar proveedor"
                                                    onclick="return confirm('¿Estás seguro de que quieres reactivar este proveedor?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $suppliers->links() }}
            </div>
        @else
            <!-- Estado vacío -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay proveedores</h3>
                <p class="mt-1 text-sm text-gray-500">
                    No se encontraron proveedores con los filtros aplicados.
                </p>
            </div>
        @endif
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
                <textarea id="rejectReason" 
                          name="reason" 
                          rows="3" 
                          required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Explica el motivo del rechazo..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeRejectModal()"
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
            ¿Estás seguro de que quieres dar de baja a <span id="deactivateSupplierName" class="font-semibold"></span>?
        </p>
        
        <form id="deactivateForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="deactivateReason" class="block text-sm font-medium text-gray-700 mb-2">
                    Motivo de la baja *
                </label>
                <textarea id="deactivateReason" 
                          name="reason" 
                          rows="3" 
                          required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                          placeholder="Explica el motivo de la baja..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="closeDeactivateModal()"
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
