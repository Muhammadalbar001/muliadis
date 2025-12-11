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
        width: 6px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }

    [x-cloak] {
        display: none !important;
    }
    </style>
</head>

<body class="font-jakarta antialiased bg-slate-50 text-slate-600" x-data="{ sidebarOpen: false }"
    x-on:keydown.window.escape="sidebarOpen = false" x-on:livewire:navigated.window="sidebarOpen = false">
    <div wire:loading
        class="fixed top-0 left-0 w-full h-1 z-[100] bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 animate-pulse">
    </div>

    @include('layouts.sidebar')

    <div class="flex flex-col min-h-screen transition-all duration-300 lg:pl-64">

        <header
            class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 bg-white/90 backdrop-blur-md border-b border-slate-200 lg:hidden shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-slate-500 hover:text-indigo-600 focus:outline-none focus:text-indigo-600 transition-colors p-1">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <span class="font-extrabold text-lg text-slate-800 tracking-tight">Muliadis</span>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs border border-indigo-200">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>

    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden" x-cloak>
    </div>

    <div x-data="{ show: false, message: '', type: 'success' }"
        x-on:show-toast.window="show = true; message = $event.detail[0]?.message || $event.detail.message; type = $event.detail[0]?.type || $event.detail.type || 'success'; setTimeout(() => show = false, 3000)"
        class="fixed top-20 right-5 z-[100] flex flex-col gap-2 w-full max-w-xs" style="display: none;" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-full"
        x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full">

        <div :class="{
                'border-l-4 bg-white shadow-xl': true,
                'border-emerald-500': type === 'success',
                'border-rose-500': type === 'error',
                'border-blue-500': type === 'info'
             }" class="flex items-center p-4 rounded-r-lg border border-slate-100">

            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg" :class="{
                    'text-emerald-500 bg-emerald-100': type === 'success',
                    'text-rose-500 bg-rose-100': type === 'error',
                    'text-blue-500 bg-blue-100': type === 'info'
                 }">
                <i class="fas"
                    :class="{ 'fa-check': type === 'success', 'fa-times': type === 'error', 'fa-info': type === 'info' }"></i>
            </div>
            <div class="ml-3 text-sm font-bold text-slate-700" x-text="message"></div>
            <button @click="show = false"
                class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8">
                <span class="sr-only">Close</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

</body>

</html>