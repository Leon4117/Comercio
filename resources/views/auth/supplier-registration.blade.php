<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Completa tu Perfil de Proveedor</h2>
        <p class="text-gray-600">Información adicional para verificar tu cuenta</p>

        <!-- User Type Indicator -->
        <div class="mt-4 inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6">
                </path>
            </svg>
            Registro como Proveedor
        </div>
    </div>

    <form method="POST" action="{{ route('supplier.complete-registration') }}" enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        <!-- Categoría -->
        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                Categoría de Servicio *
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <select id="category_id" name="category_id" required
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-gray-50 focus:bg-white">
                    <option value="">Selecciona una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
        </div>

        <!-- Ubicación -->
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                Ubicación *
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <input id="location" type="text" name="location" value="San Luis Potosí" readonly
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                    placeholder="San Luis Potosí">
            </div>
            <x-input-error :messages="$errors->get('location')" class="mt-2" />
        </div>

        <!-- Rango de Precio -->
        <div>
            <label for="price_range" class="block text-sm font-medium text-gray-700 mb-2">
                Rango de Precio *
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                        </path>
                    </svg>
                </div>
                <select id="price_range" name="price_range" required
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-gray-50 focus:bg-white">
                    <option value="">Selecciona un rango</option>
                    <option value="$500 - $2,000" {{ old('price_range') == '$500 - $2,000' ? 'selected' : '' }}>$500 -
                        $2,000</option>
                    <option value="$2,000 - $5,000" {{ old('price_range') == '$2,000 - $5,000' ? 'selected' : '' }}>
                        $2,000 - $5,000</option>
                    <option value="$5,000 - $10,000" {{ old('price_range') == '$5,000 - $10,000' ? 'selected' : '' }}>
                        $5,000 - $10,000</option>
                    <option value="$10,000 - $25,000" {{ old('price_range') == '$10,000 - $25,000' ? 'selected' : '' }}>
                        $10,000 - $25,000</option>
                    <option value="$25,000+" {{ old('price_range') == '$25,000+' ? 'selected' : '' }}>$25,000+</option>
                </select>
            </div>
            <x-input-error :messages="$errors->get('price_range')" class="mt-2" />
        </div>

        <!-- Descripción -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Descripción de tus Servicios
            </label>
            <textarea id="description" name="description" rows="4"
                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-gray-50 focus:bg-white"
                placeholder="Describe brevemente los servicios que ofreces...">{{ old('description') }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <!-- Papelería -->
        <div>
            <label for="documents" class="block text-sm font-medium text-gray-700 mb-2">
                Papelería *
            </label>
            <div id="documents-dropzone"
                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors cursor-pointer">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                        viewBox="0 0 48 48">
                        <path
                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600 justify-center">
                        <label for="documents"
                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                            <span>Subir archivos</span>
                            <input id="documents" name="documents[]" type="file" class="sr-only" multiple
                                accept=".pdf,.jpg,.jpeg,.png" required>
                        </label>
                        <p class="pl-1">o arrastra y suelta</p>
                    </div>
                    <p class="text-xs text-gray-500">PDF, PNG, JPG hasta 10MB cada uno</p>
                    <div id="documents-preview" class="mt-2 text-sm text-gray-700 font-medium"></div>
                </div>
            </div>
            <x-input-error :messages="$errors->get('documents')" class="mt-2" />
        </div>

        <!-- Identificación Oficial -->
        <div>
            <label for="identification_photo" class="block text-sm font-medium text-gray-700 mb-2">
                Foto de Identificación Oficial *
            </label>
            <div id="identification-dropzone"
                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors cursor-pointer">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                        viewBox="0 0 48 48">
                        <path d="M8 14a6 6 0 016-6h20a6 6 0 016 6v20a6 6 0 01-6 6H14a6 6 0 01-6-6V14z" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M24 18a4 4 0 100 8 4 4 0 000-8z" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M16 32l4-4 8 8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600 justify-center">
                        <label for="identification_photo"
                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                            <span>Subir identificación</span>
                            <input id="identification_photo" name="identification_photo" type="file"
                                class="sr-only" required accept=".jpg,.jpeg,.png">
                        </label>
                        <p class="pl-1">o arrastra y suelta</p>
                    </div>
                    <p class="text-xs text-gray-500">INE, Pasaporte, Cédula - PNG, JPG hasta 5MB</p>
                    <div id="identification-preview" class="mt-2 text-sm text-gray-700 font-medium"></div>
                </div>
            </div>
            <x-input-error :messages="$errors->get('identification_photo')" class="mt-2" />
        </div>

        <script>
            function setupFileUpload(inputId, dropzoneId, previewId) {
                const input = document.getElementById(inputId);
                const dropzone = document.getElementById(dropzoneId);
                const preview = document.getElementById(previewId);

                function updatePreview(files) {
                    if (files.length > 0) {
                        const fileNames = Array.from(files).map(file => file.name).join(', ');
                        preview.textContent = `Seleccionado: ${fileNames}`;
                        dropzone.classList.add('border-blue-500', 'bg-blue-50');
                    } else {
                        preview.textContent = '';
                        dropzone.classList.remove('border-blue-500', 'bg-blue-50');
                    }
                }

                // Click on dropzone triggers input click
                dropzone.addEventListener('click', (e) => {
                    // Don't trigger if click came from label or input itself
                    if (e.target === input || e.target.closest('label[for="' + inputId + '"]')) {
                        return;
                    }
                    input.click();
                });

                input.addEventListener('change', (e) => {
                    updatePreview(e.target.files);
                });

                // Drag and Drop events
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropzone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, unhighlight, false);
                });

                function highlight(e) {
                    dropzone.classList.add('border-blue-500', 'bg-blue-50');
                }

                function unhighlight(e) {
                    dropzone.classList.remove('border-blue-500', 'bg-blue-50');
                }

                dropzone.addEventListener('drop', (e) => {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    input.files = files;
                    updatePreview(files);
                    highlight(e); // Keep highlighted if file is selected
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                setupFileUpload('documents', 'documents-dropzone', 'documents-preview');
                setupFileUpload('identification_photo', 'identification-dropzone', 'identification-preview');
            });
        </script>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Completar Registro
            </button>
        </div>

        <!-- Back Link -->
    </form>
    <div class="text-center pt-4 border-t border-gray-200">
        <p class="text-sm text-gray-600">
            ¿Te equivocaste de registro?
        <form action="{{ route('logout') }}" method="POST" novalidate>
            @csrf
            <button type="submit" class="font-medium text-blue-600 hover:text-blue-800" formnovalidate>
                Cerrar sesión
            </button>
        </form>
        </p>
    </div>
</x-guest-layout>
