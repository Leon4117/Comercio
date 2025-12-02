@extends('layouts.dashboard')

@section('title', 'Chat')

@section('content')
    <div class="p-6 h-[calc(100vh-4rem)] flex flex-col">
        <!-- Header -->
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('chat.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $conversation->user_one_id === auth()->id() ? $conversation->userTwo->name : $conversation->userOne->name }}
                    </h1>
                    <p class="text-sm text-gray-500">En línea</p>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="flex-1 bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col overflow-hidden">
            <!-- Messages Area -->
            <div class="flex-1 p-6 overflow-y-auto flex flex-col space-y-4 bg-gray-50" id="messages-container">
                @foreach ($conversation->messages as $message)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-[70%] rounded-2xl px-4 py-2 shadow-sm {{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-gray-900 rounded-bl-none border border-gray-200' }}">
                            <p>{{ $message->content }}</p>
                            <span
                                class="text-xs opacity-75 block text-right mt-1 {{ $message->sender_id === auth()->id() ? 'text-indigo-100' : 'text-gray-500' }}">
                                {{ $message->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-gray-200">
                <form action="{{ route('chat.store', $conversation) }}" method="POST" class="flex gap-4">
                    @csrf
                    <div class="flex-1 relative">
                        <input type="text" name="content"
                            class="w-full rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-4 pr-10 py-3"
                            placeholder="Escribe un mensaje..." required autofocus>
                        <button type="button" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 text-white p-3 rounded-full hover:bg-indigo-700 transition shadow-md flex items-center justify-center">
                        <svg class="w-6 h-6 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Scroll to bottom on load
        const container = document.getElementById('messages-container');
        container.scrollTop = container.scrollHeight;
    </script>
@endsection
