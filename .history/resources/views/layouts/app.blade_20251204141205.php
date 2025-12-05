<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PT. MAD System') }}</title>

    <!-- 1. Livewire Styles -->
    @livewireStyles

    <!-- 2. Tailwind CSS (CDN sebagai fallback jika Vite bermasalah di Laragon) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- 3. FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- 4. Scripts (Vite Breeze) -->
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
        transition: transform 0.3s ease-in-out;
    }
    </style>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="flex flex-col md:flex-row min-h-screen">

        <!-- SIDEBAR MENU -->
        <div class="bg-slate-800 shadow-xl h-screen fixed md:sticky top-0 left-0 z-30 w-64 transform -translate-x-full md:translate-x-0 sidebar-transition overflow-y-auto"
            id="sidebar">
            <!-- Logo / Brand -->
            <div class="p-6 bg-slate-900 border-b border-slate-700 flex justify-between items-center">
                <a href="{{ route('dashboard') }}" class="text-white text-xl font-bold flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-blue-400"></i>
                    PT. MAD
                </a>
                <!-- Close Button Mobile -->
                <button id="mobile-close-btn" class="md:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- User Info (Mobile Only) -->
            <div class="md:hidden p-4 border-b border-slate-700">
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
            <ul class="list-reset text-slate-300 pb-10">

                <!-- DASHBOARD -->
                <li class="w-full">
                    <a href="{{ route('dashboard') }}"
                        class="block py-3 px-6 {{ request()->routeIs('dashboard') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i> Dashboard
                    </a>
                </li>

                <!-- SEPARATOR: MASTER DATA -->
                <li class="px-6 py-2 uppercase text-xs font-bold text-slate-500 mt-4">Master Data</li>

                <li class="w-full">
                    <a href="{{ route('master.produk') }}"
                        class="block py-2 px-6 {{ request()->routeIs('master.produk') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-box mr-3 w-5 text-center"></i> Produk
                    </a>
                </li>
                <li class="w-full">
                    <a href="{{ route('master.supplier') }}"
                        class="block py-2 px-6 {{ request()->routeIs('master.supplier') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-truck mr-3 w-5 text-center"></i> Supplier
                    </a>
                </li>
                <li class="w-full">
                    <a href="{{ route('master.sales') }}"
                        class="block py-2 px-6 {{ request()->routeIs('master.sales') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-user-tie mr-3 w-5 text-center"></i> Sales
                    </a>
                </li>
                <li class="w-full">
                    <a href="{{ route('master.users') }}"
                        class="block py-2 px-6 {{ request()->routeIs('master.users') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-users mr-3 w-5 text-center"></i> Users
                    </a>
                </li>

                <!-- SEPARATOR: TRANSAKSI -->
                <li class="px-6 py-2 uppercase text-xs font-bold text-slate-500 mt-4">Transaksi</li>

                <li class="w-full">
                    <a href="{{ route('transaksi.penjualan') }}"
                        class="block py-2 px-6 {{ request()->routeIs('transaksi.penjualan') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-shopping-cart mr-3 w-5 text-center"></i> Order Penjualan
                    </a>
                </li>
                <li class="w-full">
                    <a href="{{ route('transaksi.retur') }}"
                        class="block py-2 px-6 {{ request()->routeIs('transaksi.retur') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-undo mr-3 w-5 text-center"></i> Retur Penjualan
                    </a>
                </li>
                <li class="w-full">
                    <a href="{{ route('transaksi.ar') }}"
                        class="block py-2 px-6 {{ request()->routeIs('transaksi.ar') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-file-invoice-dollar mr-3 w-5 text-center"></i> AR (Piutang)
                    </a>
                </li>
                <li class="w-full">
                    <a href="{{ route('transaksi.collection') }}"
                        class="block py-2 px-6 {{ request()->routeIs('transaksi.collection') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-money-bill-wave mr-3 w-5 text-center"></i> Collection (Lunas)
                    </a>
                </li>

                <!-- SEPARATOR: LAPORAN -->
                <li class="px-6 py-2 uppercase text-xs font-bold text-slate-500 mt-4">Laporan</li>

                <li class="w-full">
                    <a href="{{ route('laporan.rekap_penjualan') }}"
                        class="block py-2 px-6 {{ request()->routeIs('laporan.rekap_penjualan') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-chart-line mr-3 w-5 text-center"></i> Rekap Penjualan
                    </a>
                </li>
                <li class="w-full">
                    <a href="{{ route('laporan.rekap_retur') }}"
                        class="block py-2 px-6 {{ request()->routeIs('laporan.rekap_retur') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-chart-bar mr-3 w-5 text-center"></i> Rekap Retur
                    </a>
                </li>
                <li class="w-full">
                    <a href="{{ route('laporan.rekap_ar') }}"
                        class="block py-2 px-6 {{ request()->routeIs('laporan.rekap_ar') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-chart-pie mr-3 w-5 text-center"></i> Rekap AR
                    </a>
                </li>
                <li class="w-full">
                    <a href="{{ route('laporan.rekap_collection') }}"
                        class="block py-2 px-6 {{ request()->routeIs('laporan.rekap_collection') ? 'active-nav text-white' : 'nav-item' }}">
                        <i class="fas fa-wallet mr-3 w-5 text-center"></i> Rekap Collection
                    </a>
                </li>
            </ul>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="main-content flex-1 bg-gray-100 min-h-screen relative w-full md:w-auto">

            <!-- OVERLAY (Mobile) -->
            <div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-20 hidden md:hidden"></div>

            <!-- Topbar -->
            <div class="bg-white shadow p-4 flex justify-between items-center sticky top-0 z-10">
                <div class="flex items-center">
                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn"
                        class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none mr-4">
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

                    <div class="relative x-data=" { open: false }">
                        <button
                            class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold hover:bg-blue-700 transition">
                            {{ Auth::user()->name[0] ?? 'U' }}
                        </button>

                        <!-- Simple Logout Form -->
                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 hidden group-hover:block hover:block">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Form Logout Explicit (Alternative) -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2" title="Logout">
                            <i class="fas fa-sign-out-alt fa-lg"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content Container -->
            <div class="p-6">
                <!-- Flash Message Area -->
                @if (session()->has('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                @if (session()->has('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
                @endif

                <!-- SLOT CONTENT (Breeze Default) or YIELD CONTENT (Custom) -->
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Script untuk Mobile Menu -->
    <script>
    const btn = document.getElementById('mobile-menu-btn');
    const closeBtn = document.getElementById('mobile-close-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    btn.addEventListener('click', toggleSidebar);
    closeBtn.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);
    </script>
</body>

</html>