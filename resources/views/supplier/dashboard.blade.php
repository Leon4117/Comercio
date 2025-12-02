@extends('layouts.dashboard')

@section('title', 'Dashboard Proveedor')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Proveedor</h1>
            <p class="text-gray-600 mt-1">Gestiona tus pedidos y servicios</p>
        </div>

        <!-- Estadísticas principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pedidos Pendientes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pedidos Pendientes</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['pending_orders'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Pedidos Realizados -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pedidos Realizados</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['completed_orders'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Ganancias Totales -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ganancias Totales</dt>
                            <dd class="text-lg font-medium text-gray-900">${{ number_format($stats['total_earnings'], 2) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Calificación -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Calificación</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['average_rating'], 1) }}/5
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pedidos Pendientes -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Pedidos Pendientes</h2>
                    <p class="text-sm text-gray-600">Gestiona tus pedidos activos</p>
                </div>
                <a href="{{ route('supplier.pending-orders') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver todos
                </a>
            </div>

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
                                        <!-- Botón de chat -->
                                        @if ($order->status !== 'cancelled')
                                            <a href="{{ route('chat.start', $order->event->user) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                Chat
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay pedidos pendientes</h3>
                    <p class="mt-1 text-sm text-gray-500">Cuando recibas nuevos pedidos aparecerán aquí.</p>
                </div>
            @endif
        </div>

        <!-- Reportes y Gráficas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Pie Chart: Order Status -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Estatus de Pedidos (Este Mes)</h3>
                <div class="relative h-64 w-full flex justify-center">
                    @if (count($pieChartData['data']) > 0)
                        <canvas id="statusChart"></canvas>
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400">
                            No hay pedidos este mes
                        </div>
                    @endif
                </div>
            </div>

            <!-- Earnings Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Ganancias por Día (Este Mes)</h3>
                <div class="relative h-64 w-full">
                    @if (count($barChartData['data']) > 0)
                        <canvas id="earningsChart"></canvas>
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400">
                            No hay ganancias registradas este mes
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pedidos Realizados Recientes -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Pedidos Realizados Recientes</h2>
                    <p class="text-sm text-gray-600">Últimos 5 pedidos completados</p>
                </div>
                <a href="{{ route('supplier.completed-orders') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver todos
                </a>
            </div>

            @if ($completedOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha Evento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio Final</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Completado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($completedOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->event->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $order->event->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->event->event_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($order->final_price > 0 ? $order->final_price : $order->service->base_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->updated_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay pedidos completados</h3>
                    <p class="mt-1 text-sm text-gray-500">Tus pedidos completados aparecerán aquí.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pie Chart Configuration
            @if (count($pieChartData['data']) > 0)
                const statusCtx = document.getElementById('statusChart').getContext('2d');
                new Chart(statusCtx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($pieChartData['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($pieChartData['data']) !!},
                            backgroundColor: {!! json_encode($pieChartData['colors']) !!},
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            @endif

            // Earnings Chart Configuration
            @if (count($barChartData['data']) > 0)
                const earningsCtx = document.getElementById('earningsChart').getContext('2d');
                new Chart(earningsCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($barChartData['labels']) !!},
                        datasets: [{
                            label: 'Ganancias ($)',
                            data: {!! json_encode($barChartData['data']) !!},
                            borderColor: '#4F46E5', // Indigo 600
                            backgroundColor: 'rgba(79, 70, 229, 0.1)', // Indigo 600 with opacity
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#4F46E5',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [2, 4],
                                    color: '#E5E7EB'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1F2937',
                                padding: 12,
                                titleFont: {
                                    size: 13
                                },
                                bodyFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                callbacks: {
                                    label: function(context) {
                                        return 'Ganancias: $' + context.raw;
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                    }
                });
            @endif
        });
    </script>


@endsection

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

<!-- Toast Container -->
<div id="toastContainer" class="fixed bottom-5 right-5 z-[70] flex flex-col space-y-2"></div>

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

    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toastContainer');
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

        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        });

        setTimeout(() => {
            toast.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // Show session messages
    @if (session('success'))
        showToast("{{ session('success') }}", 'success');
    @endif
    @if (session('error'))
        showToast("{{ session('error') }}", 'error');
    @endif
</script>
