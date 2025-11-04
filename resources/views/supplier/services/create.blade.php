@extends('layouts.dashboard')

@section('title', 'Publicar Nuevo Servicio')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <span class="text-blue-600">📋</span> Publicar Nuevo Servicio
                </h1>
                <p class="text-gray-600 mt-1">Ingresa la información para que los clientes en San Luis Potosí puedan encontrarte.</p>
            </div>
            <a href="{{ route('supplier.services.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Mis Servicios
            </a>
        </div>
    </div>

    <form action="{{ route('supplier.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Información Esencial -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Información Esencial</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Nombre del Servicio/Paquete -->
                <div class="lg:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Servicio/Paquete
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                           placeholder="Ej: Pastel de Boda Clásico (3 pisos)">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categoría del Servicio -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Categoría del Servicio
                    </label>
                    <select id="category" 
                            name="category" 
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        <option value="">Selecciona una Categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Precio Base -->
                <div>
                    <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Precio Base (MXN)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" 
                               id="base_price" 
                               name="base_price" 
                               value="{{ old('base_price') }}"
                               step="0.01" 
                               min="0"
                               required
                               class="block w-full pl-7 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="1250.00">
                    </div>
                    @error('base_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción Detallada -->
                <div class="lg:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción Detallada (Incluye lo que cubre el precio)
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              required
                              class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                              placeholder="Describe qué incluye tu paquete, materiales, tiempo de entrega, y cualquier restricción.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Logística y Urgencia -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Logística y Urgencia</h2>
            
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Venta de Urgencia</h3>
                        <div class="mt-2">
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           id="urgent_available" 
                                           name="urgent_available" 
                                           value="1"
                                           {{ old('urgent_available') ? 'checked' : '' }}
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-red-700">
                                        Permite a los clientes cotizar este servicio con poco tiempo de anticipación, aplicando un costo mayor.
                                    </span>
                                </label>
                            </div>
                            
                            <div id="urgent_price_section" class="mt-4 {{ old('urgent_available') ? '' : 'hidden' }}">
                                <label for="urgent_price_extra" class="block text-sm font-medium text-red-700 mb-2">
                                    Costo Extra por Urgencia (MXN)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-red-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" 
                                           id="urgent_price_extra" 
                                           name="urgent_price_extra" 
                                           value="{{ old('urgent_price_extra') }}"
                                           step="0.01" 
                                           min="0"
                                           class="block w-full pl-7 pr-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200"
                                           placeholder="300.00">
                                </div>
                                @error('urgent_price_extra')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Portafolio Visual -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Portafolio Visual</h2>
            
            <!-- Imagen Principal -->
            <div class="mb-6">
                <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Imagen Principal del Servicio
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="main_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Subir imagen principal</span>
                                <input id="main_image" name="main_image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">o arrastra y suelta</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 5MB. La calidad es clave!</p>
                    </div>
                </div>
                @error('main_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fotos de Referencia -->
            <div>
                <label for="portfolio_images" class="block text-sm font-medium text-gray-700 mb-2">
                    Subir Fotos de Referencia (Máx. 5)
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="portfolio_images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Sube tus archivos</span>
                                <input id="portfolio_images" name="portfolio_images[]" type="file" class="sr-only" accept="image/*" multiple>
                            </label>
                            <p class="pl-1">o arrastra y suelta</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB. ¡Tu calidad es clave!</p>
                    </div>
                </div>
                @error('portfolio_images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('supplier.services.index') }}" 
               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Publicar Servicio
            </button>
        </div>
    </form>
</div>

<script>
// Toggle urgent price section
document.getElementById('urgent_available').addEventListener('change', function() {
    const urgentSection = document.getElementById('urgent_price_section');
    if (this.checked) {
        urgentSection.classList.remove('hidden');
        document.getElementById('urgent_price_extra').required = true;
    } else {
        urgentSection.classList.add('hidden');
        document.getElementById('urgent_price_extra').required = false;
        document.getElementById('urgent_price_extra').value = '';
    }
});

// File upload preview
document.getElementById('main_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // You can add image preview logic here
        console.log('Main image selected:', file.name);
    }
});

document.getElementById('portfolio_images').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    if (files.length > 5) {
        alert('Máximo 5 imágenes permitidas');
        this.value = '';
        return;
    }
    files.forEach(file => {
        console.log('Portfolio image selected:', file.name);
    });
});
</script>
@endsection
