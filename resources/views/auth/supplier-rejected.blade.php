@extends('layouts.guest')

@section('content')
<div class="w-full max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Solicitud Rechazada</h2>
            <p class="text-gray-600 mt-2">Tu solicitud para ser proveedor ha sido revisada</p>
        </div>

        <!-- Información del rechazo -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Motivo del rechazo:
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>{{ $supplier->rejection_reason ?? 'No se especificó un motivo.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        ¿Qué puedes hacer ahora?
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Revisa el motivo del rechazo mencionado arriba</li>
                            <li>Corrige la información o documentos según sea necesario</li>
                            <li>Vuelve a enviar tu solicitud con la información actualizada</li>
                            <li>Si tienes dudas, puedes contactar a nuestro equipo de soporte</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de la solicitud -->
        <div class="border border-gray-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Información de tu solicitud</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">Nombre:</span>
                    <span class="text-gray-900 ml-2">{{ $supplier->user->name }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Email:</span>
                    <span class="text-gray-900 ml-2">{{ $supplier->user->email }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Categoría:</span>
                    <span class="text-gray-900 ml-2">{{ $supplier->category->name ?? 'No especificada' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Ubicación:</span>
                    <span class="text-gray-900 ml-2">{{ $supplier->location }}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="font-medium text-gray-700">Fecha de solicitud:</span>
                    <span class="text-gray-900 ml-2">{{ $supplier->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="font-medium text-gray-700">Fecha de rechazo:</span>
                    <span class="text-gray-900 ml-2">{{ $supplier->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('supplier.registration-form') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Actualizar Solicitud
            </a>
            
            <a href="mailto:soporte@festivando.com?subject=Consulta sobre solicitud rechazada&body=Hola, mi solicitud de proveedor fue rechazada y me gustaría obtener más información. Mi email es: {{ $supplier->user->email }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Contactar Soporte
            </a>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-red-100 text-red-700 font-medium rounded-md hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>

        <!-- Información de contacto -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
            <p>¿Necesitas ayuda? Contáctanos en <a href="mailto:soporte@festivando.com" class="text-blue-600 hover:text-blue-800">soporte@festivando.com</a></p>
            <p class="mt-1">O llámanos al <a href="tel:+525555551234" class="text-blue-600 hover:text-blue-800">+52 55 5555 1234</a></p>
        </div>
</div>
@endsection
