@extends('layouts.dashboard')

@section('title', 'Ver Servicio - ' . $service->name)

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $service->name }}</h1>
                <p class="text-gray-600 mt-1">Detalles completos del servicio</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('supplier.services.edit', $service) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar Servicio
                </a>
                <a href="{{ route('supplier.services.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Mis Servicios
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Columna Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Imagen Principal -->
            @if($service->main_image)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Imagen Principal</h2>
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                    <img src="{{ $service->getMainImageUrl() }}" 
                         alt="{{ $service->name }}" 
                         class="w-full h-64 object-cover rounded-lg">
                </div>
            </div>
            @endif

            <!-- Descripción -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Descripción del Servicio</h2>
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $service->description }}</p>
                </div>
            </div>

            <!-- Portafolio de Imágenes -->
            @if($service->portfolio_images && count($service->portfolio_images) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Portafolio Visual</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($service->getPortfolioImagesUrls() as $imageUrl)
                    <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden cursor-pointer hover:opacity-75 transition-opacity"
                         onclick="openImageModal('{{ $imageUrl }}')">
                        <img src="{{ $imageUrl }}" 
                             alt="Imagen del portafolio" 
                             class="w-full h-32 object-cover rounded-lg">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Información del Servicio -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Servicio</h2>
                
                <div class="space-y-4">
                    <!-- Precio Base -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Precio Base:</span>
                        <span class="text-lg font-bold text-green-600">${{ number_format($service->base_price, 2) }}</span>
                    </div>

                    <!-- Estado -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Estado:</span>
                        @if($service->active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Activo
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Inactivo
                            </span>
                        @endif
                    </div>

                    <!-- Urgencia -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Urgencia:</span>
                        @if($service->urgent_available)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Disponible
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                No disponible
                            </span>
                        @endif
                    </div>

                    @if($service->urgent_available && $service->urgent_price_extra)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Costo Extra Urgente:</span>
                        <span class="text-sm font-bold text-red-600">+${{ number_format($service->urgent_price_extra, 2) }}</span>
                    </div>
                    @endif

                    <!-- Precio Total con Urgencia -->
                    @if($service->urgent_available && $service->urgent_price_extra)
                    <div class="flex justify-between items-center py-2 bg-red-50 px-3 rounded-lg">
                        <span class="text-sm font-medium text-red-700">Precio con Urgencia:</span>
                        <span class="text-lg font-bold text-red-700">${{ number_format($service->getTotalPrice(true), 2) }}</span>
                    </div>
                    @endif

                    <!-- Fecha de Creación -->
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-600">Creado:</span>
                        <span class="text-sm text-gray-500">{{ $service->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <!-- Última Actualización -->
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-600">Actualizado:</span>
                        <span class="text-sm text-gray-500">{{ $service->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Acciones</h2>
                
                <div class="space-y-3">
                    <!-- Editar -->
                    <a href="{{ route('supplier.services.edit', $service) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Servicio
                    </a>

                    <!-- Activar/Desactivar -->
                    <form action="{{ route('supplier.services.toggle-active', $service) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 {{ $service->active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition-colors">
                            @if($service->active)
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                </svg>
                                Desactivar Servicio
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Activar Servicio
                            @endif
                        </button>
                    </form>

                    <!-- Eliminar -->
                    <form action="{{ route('supplier.services.destroy', $service) }}" method="POST" 
                          onsubmit="return confirm('¿Estás seguro de eliminar este servicio? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar Servicio
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver imágenes -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" 
                class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="Imagen ampliada" class="max-w-full max-h-full object-contain rounded-lg">
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Cerrar modal al hacer clic fuera de la imagen
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
@endsection
