<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Muliadis App') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #475569;
        border-radius: 20px;
    }

    [x-cloak] {
        display: none !important;
    }
    </style>
</head>

<body class="font-jakarta antialiased bg-slate-50 text-slate-600" x-data="{ sidebarOpen: false }">

    @include('layouts.sidebar')

    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/80 z-40 lg:hidden" x-cloak>
    </div>

    <div class="flex flex-col min-h-screen transition-all duration-300 lg:pl-64">

        <header
            class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 bg-white/80 backdrop-blur-md border-b border-slate-200 lg:hidden">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-slate-500 hover:text-indigo-600 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <span class="font-bold text-lg text-slate-800">Muliadis</span>
            </div>
            <div
                class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>

    <div x-data="{ show: false, message: '', type: 'success' }"
        x-on:show-toast.window="show = true; message = $event.detail[0]?.message || $event.detail.message; type = $event.detail[0]?.type || $event.detail.type || 'success'; setTimeout(() => show = false, 3000)"
        class="fixed top-5 right-5 z-[100]" style="display: none;" x-show="show" x-transition.duration.300ms>
        <div :class="type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
            class="text-white px-6 py-3 rounded-xl shadow-xl font-bold text-sm flex items-center gap-3">
            <i class="fas" :class="type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'"></i>
            <span x-text="message"></span>
        </div>
    </div>

</body>

</html>