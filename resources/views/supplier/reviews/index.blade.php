@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Mis Reseñas</h1>
            <p class="text-gray-600">Gestiona y visualiza las opiniones de tus clientes</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Average Rating Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 font-medium">Calificación Promedio</h3>
                    <div class="p-2 bg-yellow-50 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end space-x-2">
                    <span class="text-4xl font-bold text-gray-800">{{ number_format($averageRating, 1) }}</span>
                    <span class="text-gray-500 mb-1">/ 5.0</span>
                </div>
            </div>

            <!-- Total Reviews Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 font-medium">Total de Reseñas</h3>
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end space-x-2">
                    <span class="text-4xl font-bold text-gray-800">{{ $totalReviews }}</span>
                    <span class="text-gray-500 mb-1">opiniones</span>
                </div>
            </div>

            <!-- Rating Distribution -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-gray-500 font-medium mb-4">Distribución</h3>
                <div class="space-y-2">
                    @foreach ([5, 4, 3, 2, 1] as $rating)
                        <div class="flex items-center text-sm">
                            <span class="w-3 text-gray-600 font-medium">{{ $rating }}</span>
                            <svg class="w-4 h-4 text-yellow-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden mx-2">
                                @php
                                    $percentage =
                                        $totalReviews > 0 ? ($ratingCounts[$rating] / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-gray-500 w-8 text-right">{{ $ratingCounts[$rating] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Historial de Reseñas</h2>
            </div>

            @if ($reviews->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach ($reviews as $review)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                                            {{ strtoupper(substr($review->user->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center mb-1">
                                            <h4 class="font-medium text-gray-900 mr-2">{{ $review->user->name }}</h4>
                                            <span
                                                class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg>
                                            @endfor
                                        </div>
                                        @if ($review->comment)
                                            <p class="text-gray-600">{{ $review->comment }}</p>
                                        @else
                                            <p class="text-gray-400 italic">Sin comentario escrito</p>
                                        @endif
                                    </div>
                                </div>
                                <!-- Optional: Add response button or actions here -->
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-6 border-t border-gray-100">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aún no tienes reseñas</h3>
                    <p class="text-gray-500">Las opiniones de tus clientes aparecerán aquí una vez que completen tus
                        servicios.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
