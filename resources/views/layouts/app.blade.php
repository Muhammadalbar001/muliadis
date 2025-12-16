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
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #334155;
        border-radius: 20px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #475569;
    }

    /* Hide AlpineJS elements before load */
    [x-cloak] {
        display: none !important;
    }
    </style>
</head>

<body class="font-jakarta antialiased bg-slate-50 text-slate-600" x-data="{ 
        sidebarOpen: false, 
        isSidebarExpanded: localStorage.getItem('sidebarExpanded') === null ? true : localStorage.getItem('sidebarExpanded') === 'true',
        toggleSidebar() {
            this.isSidebarExpanded = !this.isSidebarExpanded;
            localStorage.setItem('sidebarExpanded', this.isSidebarExpanded);
        }
    }" x-on:livewire:navigated.window="sidebarOpen = false">

    <!-- <div wire:loading.delay.longest
        class="fixed top-0 left-0 w-full h-1 z-[9999] bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 animate-pulse">
    </div> -->

    @include('layouts.sidebar')

    <div class="flex flex-col min-h-screen transition-all duration-300 ease-in-out"
        :class="isSidebarExpanded ? 'lg:pl-64' : 'lg:pl-20'">

        <header
            class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 bg-white/80 backdrop-blur-md border-b border-slate-200 lg:hidden">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <span class="font-bold text-lg text-slate-800">Muliadis</span>
            </div>
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 font-bold text-xs border border-slate-300">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            {{ $slot }}
        </main>
    </div>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden">
    </div>

    @if (isset($slot->attributes['toast']))
    {{ $slot->attributes['toast'] }}
    @else
    <x-toast-notification />
    @endif

    @livewireScripts
</body>

</html>