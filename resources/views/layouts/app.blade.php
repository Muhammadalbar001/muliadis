<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ 
        darkMode: localStorage.getItem('theme') === 'dark',
        sidebarOpen: false, 
        isSidebarExpanded: localStorage.getItem('sidebarExpanded') !== 'false',
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        },
        toggleSidebar() {
            this.isSidebarExpanded = !this.isSidebarExpanded;
            localStorage.setItem('sidebarExpanded', this.isSidebarExpanded);
        }
      }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Muliadis App System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    [x-cloak] {
        display: none !important;
    }

    .transition-all-custom {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #3b82f6;
        border-radius: 10px;
    }
    </style>
</head>

<body class="font-sans antialiased transition-colors duration-300"
    :class="darkMode ? 'bg-[#0a0a0a] text-slate-300' : 'bg-slate-50 text-slate-600'">

    @include('layouts.sidebar')

    <div class="flex flex-col min-h-screen transition-all-custom" :class="isSidebarExpanded ? 'lg:pl-64' : 'lg:pl-20'">

        <header
            class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 transition-colors duration-300 border-b"
            :class="darkMode ? 'bg-[#0a0a0a]/80 backdrop-blur-md border-white/5' : 'bg-white/80 backdrop-blur-md border-slate-200'">

            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-blue-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="hidden lg:block">
                    <h1 class="text-xs font-black uppercase tracking-[0.2em]"
                        :class="darkMode ? 'text-white' : 'text-slate-800'">
                        Control <span class="text-blue-500">Center</span>
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button @click="toggleTheme()"
                    class="w-10 h-10 flex items-center justify-center rounded-xl border transition-all"
                    :class="darkMode ? 'bg-neutral-900 border-neutral-800 text-yellow-400' : 'bg-slate-100 border-slate-200 text-slate-500'">
                    <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'"></i>
                </button>

                <div class="flex items-center gap-3 pl-4 border-l border-white/10">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-black uppercase leading-none"
                            :class="darkMode ? 'text-white' : 'text-slate-900'">{{ Auth::user()->name }}</p>
                        <p class="text-[8px] font-bold text-blue-500 uppercase tracking-widest mt-1">Administrator</p>
                    </div>
                    <div
                        class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-lg shadow-blue-600/30">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"></div>

</body>

</html>