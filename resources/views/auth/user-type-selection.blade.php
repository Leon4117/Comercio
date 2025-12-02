<x-guest-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-full w-full space-y-8 px-4">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-blue-600 rounded-full flex items-center justify-center mb-8">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                        </path>
                    </svg>
                </div>
                <h2 class="text-5xl font-bold text-gray-900 mb-6">¡Únete a Festivando!</h2>
                <p class="text-2xl text-gray-600 max-w-3xl mx-auto">
                    Selecciona el tipo de cuenta que mejor se adapte a tus necesidades
                </p>
            </div>

            <!-- User Type Cards -->
            <div class="grid md:grid-cols-2 gap-16 mt-16 max-w-7xl mx-auto">
                <!-- Cliente Card -->
                <div class="group relative">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-pink-600 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200">
                    </div>
                    <div
                        class="relative bg-white rounded-2xl p-16 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <!-- Icon -->
                        <div
                            class="w-24 h-24 bg-gradient-to-r from-pink-500 to-purple-600 rounded-2xl flex items-center justify-center mb-8 mx-auto">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="text-center mb-10">
                            <h3 class="text-3xl font-bold text-gray-900 mb-6">Cliente</h3>
                            <p class="text-lg text-gray-600 mb-8">
                                Encuentra y contrata los mejores proveedores para tus eventos especiales
                            </p>

                            <!-- Features -->
                            <ul class="text-left space-y-4 mb-10">
                                <li class="flex items-center text-gray-700 text-lg">
                                    <svg class="w-6 h-6 text-green-500 mr-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Busca proveedores por categoría
                                </li>
                                <li class="flex items-center text-gray-700 text-lg">
                                    <svg class="w-6 h-6 text-green-500 mr-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Solicita cotizaciones personalizadas
                                </li>
                                <li class="flex items-center text-gray-700 text-lg">
                                    <svg class="w-6 h-6 text-green-500 mr-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Gestiona tus eventos fácilmente
                                </li>
                                <li class="flex items-center text-gray-700 text-lg">
                                    <svg class="w-6 h-6 text-green-500 mr-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Califica y reseña servicios
                                </li>
                            </ul>
                        </div>

                        <!-- Button -->
                        <a href="{{ route('register', ['type' => 'client']) }}"
                            class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white py-5 px-8 rounded-xl font-semibold text-xl hover:from-pink-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center group">
                            <svg class="w-6 h-6 mr-3 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            Registrarme como Cliente
                        </a>
                    </div>
                </div>

                <!-- Proveedor Card -->
                <div class="group relative">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200">
                    </div>
                    <div
                        class="relative bg-white rounded-2xl p-16 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <!-- Icon -->
                        <div
                            class="w-24 h-24 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-8 mx-auto">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6">
                                </path>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="text-center mb-10">
                            <h3 class="text-3xl font-bold text-gray-900 mb-6">Proveedor</h3>
                            <p class="text-lg text-gray-600 mb-8">
                                Ofrece tus servicios y haz crecer tu negocio conectando con clientes
                            </p>

                            <!-- Features -->
                            <ul class="text-left space-y-4 mb-10">
                                <li class="flex items-center text-gray-700 text-lg">
                                    <svg class="w-6 h-6 text-green-500 mr-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Crea tu perfil profesional
                                </li>
                                <li class="flex items-center text-gray-700 text-lg">
                                    <svg class="w-6 h-6 text-green-500 mr-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Recibe solicitudes de cotización
                                </li>
                                <li class="flex items-center text-gray-700 text-lg">
                                    <svg class="w-6 h-6 text-green-500 mr-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Gestiona tus pedidos y calendario
                                </li>
                                <li class="flex items-center text-gray-700 text-lg">
                                    <svg class="w-6 h-6 text-green-500 mr-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Construye tu reputación online
                                </li>
                            </ul>
                        </div>

                        <!-- Button -->
                        <a href="{{ route('register', ['type' => 'supplier']) }}"
                            class="w-full bg-gradient-to-r from-blue-500 to-cyan-600 text-white py-5 px-8 rounded-xl font-semibold text-xl hover:from-blue-600 hover:to-cyan-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center group">
                            <svg class="w-6 h-6 mr-3 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            Registrarme como Proveedor
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center pt-12">
                <p class="text-lg text-gray-600">
                    Al registrarte aceptas los <a
                        class="font-medium text-blue-600 hover:text-blue-800 transition-colors"
                        href="https://docs.google.com/document/d/19cfJP9qHRM-JNJJf7X3VaWuCMtIYu8mTB-Rui1znAww/edit?usp=drivesdk">terminos
                        y condiciones de uso</a> y la <a
                        class="font-medium text-blue-600 hover:text-blue-800 transition-colors"
                        href="https://docs.google.com/document/d/1GkI_AAbQPCxieC1F-EqKtNVWOS2ONQ7RNu7krRojAHI/edit?usp=drivesdk">política
                        de privacidad</a>.
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}"
                        class="font-medium text-blue-600 hover:text-blue-800 transition-colors">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
