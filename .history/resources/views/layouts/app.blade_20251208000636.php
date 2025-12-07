<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PT. MAD System') }}</title>

    @livewireStyles

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .sidebar-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar-scroll::-webkit-scrollbar-track {
        background: #1e293b;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 5px;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    .nav-item {
        transition: all 0.2s;
        border-radius: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .nav-item:hover {
        background-color: rgba(255, 255, 255, 0.05);
        color: white;
    }

    .active-nav {
        background: linear-gradient(90deg, #4f46e5 0%, #4338ca 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    [x-cloak] {
        display: none !important;
    }
    </style>
</head>

<body class="bg-gray-50 text-slate-800 antialiased">

    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false, desktopSidebarOpen: true }">

        <aside :class="{
                'translate-x-0': sidebarOpen, 
                '-translate-x-full': !sidebarOpen, 
                'md:translate-x-0': true,
                'md:w-64': desktopSidebarOpen,
                'md:w-20': !desktopSidebarOpen
             }"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-400 transition-all duration-300 transform md:static md:inset-auto flex flex-col shadow-2xl">

            <div class="flex items-center justify-between h-16 px-6 bg-slate-950 border-b border-white/5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden">
                    <div class="w-8 h-8 rounded bg-indigo-600 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-cube text-lg"></i>
                    </div>
                    <span
                        class="text-white font-bold text-lg tracking-wide transition-opacity duration-300 whitespace-nowrap"
                        :class="{'opacity-0 w-0': !desktopSidebarOpen, 'opacity-100': desktopSidebarOpen}">
                        MULIADIS
                    </span>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto sidebar-scroll p-4 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium nav-item {{ request()->routeIs('dashboard') ? 'active-nav' : '' }}">
                    <i class="fas fa-chart-pie w-5 h-5 transition-all"
                        :class="{'mx-auto': !desktopSidebarOpen, 'mr-3': desktopSidebarOpen}"></i>
                    <span class="whitespace-nowrap transition-all duration-300"
                        :class="{'opacity-0 w-0 hidden': !desktopSidebarOpen}">Dashboard</span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('master.*') ? 'true' : 'false' }} }">
                    <button @click="desktopSidebarOpen ? open = !open : desktopSidebarOpen = true"
                        class="flex items-center w-full px-4 py-3 text-sm font-medium nav-item text-left hover:text-white justify-between group">
                        <div class="flex items-center">
                            <i class="fas fa-database w-5 h-5 transition-all text-slate-400 group-hover:text-white"
                                :class="{'mx-auto': !desktopSidebarOpen, 'mr-3': desktopSidebarOpen}"></i>
                            <span class="whitespace-nowrap transition-all duration-300"
                                :class="{'opacity-0 w-0 hidden': !desktopSidebarOpen}">Master Data</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                            :class="{'rotate-180': open, 'hidden': !desktopSidebarOpen}"></i>
                    </button>
                    <div x-show="open && desktopSidebarOpen" x-collapse x-cloak class="space-y-1 pl-10 pr-2">
                        <a href="{{ route('master.produk') }}"
                            class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('master.produk') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Produk</a>
                        <a href="{{ route('master.supplier') }}"
                            class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('master.supplier') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Supplier</a>
                    </div>
                </div>

                <div x-data="{ open: {{ request()->routeIs('transaksi.*') ? 'true' : 'false' }} }">
                    <button @click="desktopSidebarOpen ? open = !open : desktopSidebarOpen = true"
                        class="flex items-center w-full px-4 py-3 text-sm font-medium nav-item text-left hover:text-white justify-between group">
                        <div class="flex items-center">
                            <i class="fas fa-shopping-cart w-5 h-5 transition-all text-slate-400 group-hover:text-white"
                                :class="{'mx-auto': !desktopSidebarOpen, 'mr-3': desktopSidebarOpen}"></i>
                            <span class="whitespace-nowrap transition-all duration-300"
                                :class="{'opacity-0 w-0 hidden': !desktopSidebarOpen}">Transaksi</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                            :class="{'rotate-180': open, 'hidden': !desktopSidebarOpen}"></i>
                    </button>
                    <div x-show="open && desktopSidebarOpen" x-collapse x-cloak class="space-y-1 pl-10 pr-2">
                        <a href="{{ route('transaksi.penjualan') }}"
                            class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('transaksi.penjualan') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Penjualan</a>
                        <a href="{{ route('transaksi.retur') }}"
                            class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('transaksi.retur') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Retur</a>
                        <a href="{{ route('transaksi.ar') }}"
                            class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('transaksi.ar') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">AR
                            (Piutang)</a>
                        <a href="{{ route('transaksi.collection') }}"
                            class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('transaksi.collection') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Collection</a>
                    </div>
                </div>

                <div x-data="{ open: {{ request()->routeIs('laporan.*') ? 'true' : 'false' }} }">
                    <button @click="desktopSidebarOpen ? open = !open : desktopSidebarOpen = true"
                        class="flex items-center w-full px-4 py-3 text-sm font-medium nav-item text-left hover:text-white justify-between group">
                        <div class="flex items-center">
                            <i class="fas fa-file-contract w-5 h-5 transition-all text-slate-400 group-hover:text-white"
                                :class="{'mx-auto': !desktopSidebarOpen, 'mr-3': desktopSidebarOpen}"></i>
                            <span class="whitespace-nowrap transition-all duration-300"
                                :class="{'opacity-0 w-0 hidden': !desktopSidebarOpen}">Laporan</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                            :class="{'rotate-180': open, 'hidden': !desktopSidebarOpen}"></i>
                    </button>
                    <div x-show="open && desktopSidebarOpen" x-collapse x-cloak class="space-y-1 pl-10 pr-2">
                        <a href="{{ route('laporan.rekap_penjualan') }}"
                            class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('laporan.rekap_penjualan') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Rekap
                            Penjualan</a>
                        <a href="{{ route('laporan.rekap_retur') }}"
                            class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('laporan.rekap_retur') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Rekap
                            Retur</a>
                        <a href="{{ route('laporan.rekap_ar') }}"
                            class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('laporan.rekap_ar') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Rekap
                            Piutang</a>
                        <a href="{{ route('laporan.rekap_collection') }}"
                            class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('laporan.rekap_collection') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Rekap
                            Collection</a>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-white/5 bg-slate-950">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-slate-400 bg-slate-900 rounded-lg hover:bg-slate-800 hover:text-white transition-colors group">
                        <i class="fas fa-sign-out-alt transition-all" :class="{'mr-2': desktopSidebarOpen}"></i>
                        <span :class="{'hidden': !desktopSidebarOpen}">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50">
            <header
                class="sticky top-0 z-40 flex items-center justify-between px-6 py-3 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm transition-all duration-300">
                <div class="flex items-center gap-4">
                    <button @click="desktopSidebarOpen = !desktopSidebarOpen"
                        class="text-slate-500 hover:text-indigo-600 focus:outline-none transition-colors">
                        <i class="fas fa-bars-staggered fa-lg"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800">@yield('header')</h2>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-gray-700 leading-none">{{ Auth::user()->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500 mt-1">Administrator</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-8">
                @if (session()->has('success'))
                <div
                    class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 flex items-center justify-between shadow-sm">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()"
                        class="text-green-500 hover:text-green-700">&times;</button>
                </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 md:hidden"></div>
    </div>

    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3"></div>

    @livewireScripts

    <script>
    document.addEventListener('livewire:init', () => {
        // Listener untuk event 'show-toast' yang dikirim dari Controller
        Livewire.on('show-toast', (data) => {
            // Di Livewire 3, data biasanya dikirim sebagai array atau object langsung
            // Kita normalisasi agar bisa menerima format: {type: '...', title: '...', message: '...'}
            const payload = Array.isArray(data) ? data[0] : data;

            showToast(payload.type, payload.title, payload.message);
        });
    });

    function showToast(type, title, message) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');

        // Warna & Icon
        const isError = type === 'error';
        const bgColor = isError ? 'bg-white border-l-4 border-red-500' : 'bg-white border-l-4 border-emerald-500';
        const iconColor = isError ? 'text-red-500' : 'text-emerald-500';
        const iconClass = isError ? 'fa-exclamation-circle' : 'fa-check-circle';
        const textColor = 'text-gray-800';

        // Desain Toast Modern
        toast.className =
            `${bgColor} p-4 rounded-r-lg shadow-xl flex items-start gap-3 w-80 transform transition-all duration-300 translate-x-full opacity-0`;
        toast.innerHTML = `
                <div class="mt-0.5 ${iconColor} text-xl"><i class="fas ${iconClass}"></i></div>
                <div class="flex-1">
                    <h4 class="font-bold text-sm ${textColor}">${title}</h4>
                    <p class="text-sm text-gray-600 mt-1 leading-snug whitespace-pre-line">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times"></i>
                </button>
            `;

        container.appendChild(toast);

        // Animasi Masuk
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        });

        // Auto Hilang dalam 6 detik
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 6000);
    }
    </script>
</body>

</html>