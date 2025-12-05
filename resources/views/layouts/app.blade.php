<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PT. MAD System') }}</title>

    <!-- 1. Livewire Styles -->
    @livewireStyles

    <!-- 2. Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- 3. FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- 4. Scripts (Vite Breeze + AlpineJS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    .active-nav {
        background-color: #1e40af;
        /* blue-800 */
        border-left: 4px solid #60a5fa;
        /* blue-400 */
    }

    .nav-item:hover {
        background-color: #1e3a8a;
        /* blue-900 */
    }

    /* Transisi untuk Mobile Menu */
    .sidebar-transition {
        transition: all 0.3s ease-in-out;
    }

    /* FIX: Agar konten tabel lebar bisa scroll tanpa memotong sidebar */
    .main-content-wrapper {
        min-width: 0;
        overflow-x: auto;
    }

    /* Style untuk menu collapsible */
    .menu-header {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .menu-header:hover {
        background-color: #1e293b;
        /* slate-800 */
    }

    /* Rotasi panah saat menu terbuka */
    .rotate-90 {
        transform: rotate(90deg);
    }
    </style>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Tambahkan desktopSidebarOpen: true agar default terbuka di desktop -->
    <div class="flex flex-col md:flex-row min-h-screen" x-data="{ sidebarOpen: false, desktopSidebarOpen: true }">

        <!-- SIDEBAR MENU -->
        <!-- Logic Class: 
             Mobile: Gunakan sidebarOpen untuk translate.
             Desktop: Gunakan desktopSidebarOpen untuk mengatur lebar (w-64 vs w-0).
        -->
        <div :class="{
                'translate-x-0 w-64': sidebarOpen, 
                '-translate-x-full w-64': !sidebarOpen, 
                'md:translate-x-0': true,
                'md:w-64': desktopSidebarOpen,
                'md:w-0': !desktopSidebarOpen,
                'md:overflow-hidden': !desktopSidebarOpen
             }"
            class="bg-slate-800 shadow-xl h-screen fixed md:sticky top-0 left-0 z-30 transform sidebar-transition overflow-y-auto"
            id="sidebar">

            <!-- Logo / Brand -->
            <div class="p-6 bg-slate-900 border-b border-slate-700 flex justify-between items-center whitespace-nowrap">
                <a href="{{ route('dashboard') }}" class="text-white text-xl font-bold flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-blue-400"></i>
                    PT. MAD
                </a>
                <!-- Close Button Mobile -->
                <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- User Info (Mobile Only) -->
            <div class="md:hidden p-4 border-b border-slate-700 whitespace-nowrap">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                        {{ Auth::user()->name[0] ?? 'G' }}
                    </div>
                    <div class="text-slate-300 text-sm">
                        <p class="font-bold">{{ Auth::user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-slate-500">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Menu List -->
            <ul class="list-reset text-slate-300 pb-10 whitespace-nowrap">

                <!-- DASHBOARD -->
                <li class="w-full border-b border-slate-700">
                    <a href="{{ route('dashboard') }}"
                        class="block py-3 px-6 {{ request()->routeIs('dashboard') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i> Dashboard
                    </a>
                </li>

                <!-- GROUP: MASTER DATA (Collapsible) -->
                <li class="w-full border-b border-slate-700"
                    x-data="{ open: {{ request()->routeIs('master.*') ? 'true' : 'false' }} }">

                    <div @click="open = !open"
                        class="menu-header flex justify-between items-center py-3 px-6 text-slate-400 hover:text-white">
                        <span class="flex items-center">
                            <i class="fas fa-database mr-3 w-5 text-center"></i>
                            <span class="font-bold">MASTER DATA</span>
                        </span>
                        <i class="fas fa-chevron-right text-xs transition-transform duration-200"
                            :class="{'rotate-90': open}"></i>
                    </div>

                    <ul x-show="open" x-collapse class="bg-slate-900/50">
                        <li class="w-full">
                            <a href="{{ route('master.produk') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('master.produk') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                Produk
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="{{ route('master.supplier') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('master.supplier') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                Supplier
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="{{ route('master.sales') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('master.sales') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                Sales
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="{{ route('master.users') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('master.users') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                Users
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- GROUP: TRANSAKSI (Collapsible) -->
                <li class="w-full border-b border-slate-700"
                    x-data="{ open: {{ request()->routeIs('transaksi.*') ? 'true' : 'false' }} }">

                    <div @click="open = !open"
                        class="menu-header flex justify-between items-center py-3 px-6 text-slate-400 hover:text-white">
                        <span class="flex items-center">
                            <i class="fas fa-shopping-cart mr-3 w-5 text-center"></i>
                            <span class="font-bold">TRANSAKSI</span>
                        </span>
                        <i class="fas fa-chevron-right text-xs transition-transform duration-200"
                            :class="{'rotate-90': open}"></i>
                    </div>

                    <ul x-show="open" x-collapse class="bg-slate-900/50">
                        <li class="w-full">
                            <a href="{{ route('transaksi.penjualan') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('transaksi.penjualan') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                Order Penjualan
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="{{ route('transaksi.retur') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('transaksi.retur') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                Retur Penjualan
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="{{ route('transaksi.ar') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('transaksi.ar') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                AR (Piutang)
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="{{ route('transaksi.collection') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('transaksi.collection') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                Collection (Lunas)
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- GROUP: LAPORAN (Collapsible) -->
                <li class="w-full border-b border-slate-700"
                    x-data="{ open: {{ request()->routeIs('laporan.*') ? 'true' : 'false' }} }">

                    <div @click="open = !open"
                        class="menu-header flex justify-between items-center py-3 px-6 text-slate-400 hover:text-white">
                        <span class="flex items-center">
                            <i class="fas fa-chart-line mr-3 w-5 text-center"></i>
                            <span class="font-bold">LAPORAN</span>
                        </span>
                        <i class="fas fa-chevron-right text-xs transition-transform duration-200"
                            :class="{'rotate-90': open}"></i>
                    </div>

                    <ul x-show="open" x-collapse class="bg-slate-900/50">
                        <li class="w-full">
                            <a href="{{ route('laporan.rekap_penjualan') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('laporan.rekap_penjualan') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                Rekap Penjualan
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="{{ route('laporan.rekap_retur') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('laporan.rekap_retur') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                Rekap Retur
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="{{ route('laporan.rekap_ar') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('laporan.rekap_ar') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                Rekap AR
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="{{ route('laporan.rekap_collection') }}"
                                class="block py-2 pl-12 pr-4 text-sm {{ request()->routeIs('laporan.rekap_collection') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                                Rekap Collection
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="main-content-wrapper flex-1 bg-gray-100 min-h-screen relative w-full md:w-auto">

            <!-- OVERLAY (Mobile) -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                class="fixed inset-0 bg-black opacity-50 z-20 md:hidden transition-opacity"></div>

            <!-- Topbar -->
            <div class="bg-white shadow p-4 flex justify-between items-center sticky top-0 z-10">
                <div class="flex items-center">

                    <!-- Mobile Menu Button (Hamburger) -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none mr-4">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>

                    <!-- Desktop Sidebar Toggle Button (Baru) -->
                    <button @click="desktopSidebarOpen = !desktopSidebarOpen"
                        class="hidden md:block text-gray-500 hover:text-gray-700 focus:outline-none mr-4"
                        title="Toggle Sidebar">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>

                    <h2 class="text-xl font-semibold text-gray-800">
                        @yield('header', 'Dashboard')
                    </h2>
                </div>

                <!-- User Profile Dropdown (Desktop) -->
                <div class="hidden md:flex items-center gap-4">
                    <div class="text-right">
                        <span class="block text-sm font-bold text-gray-700">{{ Auth::user()->name ?? 'User' }}</span>
                        <span class="block text-xs text-gray-500">Admin</span>
                    </div>

                    <div class="relative" x-data="{ userMenuOpen: false }">
                        <button @click="userMenuOpen = !userMenuOpen"
                            class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold hover:bg-blue-700 transition focus:outline-none">
                            {{ Auth::user()->name[0] ?? 'U' }}
                        </button>

                        <!-- Dropdown Logout -->
                        <div x-show="userMenuOpen" @click.away="userMenuOpen = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Container -->
            <div class="p-6">
                <!-- Flash Message Area (Global) -->
                @if (session()->has('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 flex justify-between"
                    role="alert">
                    <div>
                        <p class="font-bold">Berhasil</p>
                        <p>{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
                </div>
                @endif

                @if (session()->has('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 flex justify-between"
                    role="alert">
                    <div>
                        <p class="font-bold">Error</p>
                        <p>{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-700 font-bold">&times;</button>
                </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>

</html>