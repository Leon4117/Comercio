@extends('layouts.dashboard')

@section('title', 'Pedidos Pendientes')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pedidos Pendientes</h1>
                <p class="text-gray-600 mt-1">Todos tus pedidos activos</p>
            </div>
            <a href="{{ route('supplier.dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                &larr; Volver al Dashboard
            </a>
        </div>

        <!-- Pedidos Pendientes Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            @if ($pendingOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Servicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha Evento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($pendingOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span
                                                        class="text-sm font-medium text-gray-700">{{ substr($order->event->user->name, 0, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $order->event->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $order->event->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $supplier->category->name ?? 'Servicio' }}
                                        </div>
                                        @if ($order->urgent)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Urgente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->event->event_date->format('d/m/Y') }}
                                        <div class="text-xs text-gray-500">
                                            {{ $order->event->event_date->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($order->status === 'requested')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Solicitado
                                            </span>
                                        @elseif ($order->status === 'quoted')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Cotizando
                                            </span>
                                        @elseif($order->status === 'confirmed')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Confirmado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if ($order->final_price)
                                            ${{ number_format($order->final_price, 2) }}
                                        @else
                                            ${{ number_format($order->service->base_price, 2) }}
                                            <div class="text-xs text-gray-500">Base</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        @if ($order->status === 'requested' || $order->status === 'quoted')
                                            <!-- Botón para confirmar pedido -->
                                            <form id="confirmRequestForm-{{ $order->id }}"
                                                action="{{ route('supplier.orders.confirm-request', $order) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="text-blue-600 hover:text-blue-900"
                                                    onclick="showConfirmationModal('¿Confirmar este pedido con el precio base?', 'confirmRequestForm-{{ $order->id }}')">
                                                    Confirmar
                                                </button>
                                            </form>
                                        @elseif($order->status === 'confirmed')
                                            <!-- Botón para confirmar entrega -->
                                            <form id="confirmDeliveryForm-{{ $order->id }}"
                                                action="{{ route('supplier.orders.confirm-delivery', $order) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="text-green-600 hover:text-green-900"
                                                    onclick="showConfirmationModal('¿Confirmar que el pedido ha sido entregado?', 'confirmDeliveryForm-{{ $order->id }}')">
                                                    Confirmar Entrega
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Botón para cancelar -->
                                        <form id="cancelOrderForm-{{ $order->id }}"
                                            action="{{ route('supplier.orders.cancel', $order) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="text-red-600 hover:text-red-900"
                                                onclick="showConfirmationModal('¿Estás seguro de cancelar este pedido?', 'cancelOrderForm-{{ $order->id }}')">
                                                Cancelar
                                            </button>
                                        </form>

                                        <!-- Botón de chat -->
                                        <a href="{{ route('chat.start', $order->event->user) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            Chat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay pedidos pendientes</h3>
                    <p class="mt-1 text-sm text-gray-500">Cuando recibas nuevos pedidos aparecerán aquí.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[60]">
        <div class="relative top-1/4 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Confirmar Acción</h3>
                <div class="mt-2 px-7 py-3">
                    <p id="confirmationMessage" class="text-sm text-gray-500">¿Estás seguro?</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmBtn"
                        class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Confirmar
                    </button>
                    <button onclick="closeConfirmationModal()"
                        class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let formToSubmit = null;

        function showConfirmationModal(message, formId) {
            document.getElementById('confirmationMessage').innerText = message;
            formToSubmit = document.getElementById(formId);
            document.getElementById('confirmationModal').classList.remove('hidden');
        }

        function closeConfirmationModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
            formToSubmit = null;
        }

        document.getElementById('confirmBtn').addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
            closeConfirmationModal();
        });
    </script>
@endsection
