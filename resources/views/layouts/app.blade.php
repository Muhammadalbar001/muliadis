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

    /* Animasi rotasi lambat untuk matahari */
    .animate-spin-slow {
        animation: spin 8s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }
    </style>
</head>

<body class="font-sans antialiased transition-colors duration-300"
    :class="darkMode ? 'bg-[#0a0a0a] text-slate-300' : 'bg-slate-50 text-slate-600'">

    @include('layouts.sidebar')

    <div class="flex flex-col min-h-screen transition-all-custom" :class="isSidebarExpanded ? 'lg:pl-64' : 'lg:pl-20'">

        <header
            class="sticky top-0 z-50 flex items-center justify-between px-6 py-3 transition-all duration-300 border-b"
            :class="darkMode ? 'bg-[#0a0a0a]/80 backdrop-blur-xl border-white/5 shadow-2xl shadow-black/50' : 'bg-white/80 backdrop-blur-xl border-slate-200 shadow-sm'">

            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-xl bg-blue-600/10 text-blue-600">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div class="hidden lg:flex items-center gap-3">
                    <div
                        class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                    </div>
                    <h1 class="text-[10px] font-black uppercase tracking-[0.4em]"
                        :class="darkMode ? 'text-white' : 'text-slate-800'">
                        Control <span class="text-blue-500">Center</span>
                    </h1>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">

                <div
                    class="hidden md:flex items-center bg-slate-100 dark:bg-white/5 rounded-2xl px-3 py-1.5 border dark:border-white/5 border-slate-200 shadow-inner">
                    <i class="fas fa-microchip text-[10px] text-blue-500 mr-2"></i>
                    <span class="text-[9px] font-bold uppercase tracking-widest opacity-50">System Active</span>
                </div>

                <button @click="toggleTheme()"
                    class="w-10 h-10 flex items-center justify-center rounded-2xl transition-all border group"
                    :class="darkMode ? 'bg-neutral-900 border-white/5 text-yellow-400 hover:bg-neutral-800' : 'bg-white border-slate-200 text-slate-400 hover:bg-slate-50 shadow-sm'">
                    <i :class="darkMode ? 'fas fa-sun animate-spin-slow' : 'fas fa-moon'"></i>
                </button>

                <div class="h-6 w-px dark:bg-white/10 bg-slate-200 mx-1"></div>

                <div class="flex items-center gap-1">
                    <button
                        class="w-10 h-10 flex items-center justify-center rounded-2xl transition-all dark:text-slate-500 text-slate-400 hover:text-blue-500 dark:hover:bg-white/5 hover:bg-slate-100 relative">
                        <i class="fas fa-bell text-sm"></i>
                        <span
                            class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 border-2 dark:border-[#0a0a0a] border-white rounded-full"></span>
                    </button>
                    <button
                        class="w-10 h-10 flex items-center justify-center rounded-2xl transition-all dark:text-slate-500 text-slate-400 hover:text-blue-500 dark:hover:bg-white/5 hover:bg-slate-100">
                        <i class="fas fa-server text-sm"></i>
                    </button>
                </div>

                <div class="relative ml-2" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="flex items-center gap-2 p-1 pr-3 rounded-2xl transition-all border group"
                        :class="darkMode ? 'bg-neutral-900 border-white/5 hover:border-blue-500/50' : 'bg-white border-slate-200 hover:border-blue-500 shadow-sm'">

                        <div
                            class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white font-black text-[10px] shadow-lg shadow-blue-600/20">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>

                        <div class="text-left hidden sm:block overflow-hidden">
                            <p class="text-[9px] font-black uppercase leading-none truncate w-24"
                                :class="darkMode ? 'text-white' : 'text-slate-900'">{{ Auth::user()->name }}</p>
                        </div>

                        <i class="fas fa-chevron-down text-[8px] transition-transform duration-300"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        class="absolute right-0 mt-3 w-60 rounded-[2rem] shadow-2xl border overflow-hidden p-2 z-[60]"
                        :class="darkMode ? 'bg-[#0f0f0f] border-white/10' : 'bg-white border-slate-200'">

                        <div
                            class="px-4 py-3 border-b dark:border-white/5 border-slate-100 mb-1 bg-slate-50/50 dark:bg-white/[0.02] rounded-t-[1.5rem]">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Session
                                Access</p>
                            <p class="text-[10px] font-bold dark:text-blue-400 text-blue-600 uppercase tracking-tight">
                                {{ Auth::user()->role ?? 'Administrator' }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-2xl dark:hover:bg-white/5 hover:bg-slate-50 transition-colors group">
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <i class="fas fa-user-gear text-xs"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest">Account Settings</span>
                        </a>

                        <div class="h-px dark:bg-white/5 bg-slate-100 my-1 mx-2"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl dark:hover:bg-rose-500/10 hover:bg-rose-50 transition-colors group text-rose-500">
                                <div class="w-8 h-8 rounded-lg bg-rose-500/10 flex items-center justify-center">
                                    <i class="fas fa-power-off text-xs group-hover:animate-pulse"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest">Sign Out System</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        class="fixed inset-0 bg-black/80 backdrop-blur-md z-40 lg:hidden"></div>

    <x-toast-notification />
</body>

</html>