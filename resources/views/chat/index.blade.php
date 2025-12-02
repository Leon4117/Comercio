@extends('layouts.dashboard')

@section('title', 'Mensajes')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mensajes</h1>
            <p class="text-gray-600 mt-1">Tus conversaciones activas</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                @if ($conversations->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes mensajes</h3>
                        <p class="mt-1 text-sm text-gray-500">Tus conversaciones aparecerán aquí.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($conversations as $conversation)
                            @php
                                $otherUser =
                                    $conversation->user_one_id === auth()->id()
                                        ? $conversation->userTwo
                                        : $conversation->userOne;
                                $lastMessage = $conversation->messages->first();
                            @endphp
                            <a href="{{ route('chat.show', $conversation) }}"
                                class="block p-4 border rounded-lg hover:bg-gray-50 transition duration-150 ease-in-out group">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                                {{ substr($otherUser->name, 0, 2) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h3
                                                class="font-semibold text-lg text-gray-900 group-hover:text-indigo-600 transition-colors">
                                                {{ $otherUser->name }}</h3>
                                            <p class="text-gray-600 text-sm truncate max-w-md">
                                                {{ $lastMessage ? $lastMessage->content : 'Inicia la conversación...' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
