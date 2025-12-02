@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Reportes Mensuales</h1>
            <p class="text-gray-600">Resumen de actividad y ganancias del mes actual</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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

            <!-- Bar Chart: Earnings -->
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

            // Bar Chart Configuration
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
