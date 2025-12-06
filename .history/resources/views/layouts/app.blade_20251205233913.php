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
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Scrollbar for Sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 5px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: #1e293b; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #475569; border-radius: 5px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #64748b; }

        /* Sidebar Transition */
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }

        /* Nav Item Styles */
        .nav-item {
            position: relative;
            transition: all 0.2s;
            border-radius: 0.5rem;
            margin-bottom: 0.25rem;
        }
        .nav-item:hover { background-color: rgba(255, 255, 255, 0.05); color: white; }
        
        /* Active State */
        .active-nav {
            background: linear-gradient(90deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        [x-cloak] { display: none !important; }
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
                    <span class="text-white font-bold text-lg tracking-wide transition-opacity duration-300 whitespace-nowrap"
                          :class="{'opacity-0 w-0': !desktopSidebarOpen, 'opacity-100': desktopSidebarOpen}">
                        MULIADIS
                    </span>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto sidebar-scroll p-4 space-y-1">
                
                <p class="px-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-2 transition-opacity duration-300"
                   :class="{'opacity-0 hidden': !desktopSidebarOpen}">
                   Main Menu
                </p>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium nav-item {{ request()->routeIs('dashboard') ? 'active-nav' : '' }}">
                    <i class="fas fa-chart-pie w-5 h-5 transition-all" :class="{'mx-auto': !desktopSidebarOpen, 'mr-3': desktopSidebarOpen}"></i>
                    <span class="whitespace-nowrap transition-all duration-300" 
                          :class="{'opacity-0 w-0 hidden': !desktopSidebarOpen}">Dashboard</span>
                    
                    </a>

                <div x-data="{ open: {{ request()->routeIs('master.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="desktopSidebarOpen ? open = !open : desktopSidebarOpen = true"
                        class="flex items-center w-full px-4 py-3 text-sm font-medium nav-item text-left hover:text-white focus:outline-none justify-between group">
                        <div class="flex items-center">
                            <i class="fas fa-database w-5 h-5 transition-all text-slate-400 group-hover:text-white" :class="{'mx-auto': !desktopSidebarOpen, 'mr-3': desktopSidebarOpen}"></i>
                            <span class="whitespace-nowrap transition-all duration-300" 
                                  :class="{'opacity-0 w-0 hidden': !desktopSidebarOpen}">Master Data</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                           :class="{'rotate-180': open, 'hidden': !desktopSidebarOpen}"></i>
                    </button>

                    <div x-show="open && desktopSidebarOpen" x-collapse x-cloak class="space-y-1 pl-10 pr-2">
                        <a href="{{ route('master.produk') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('master.produk') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Produk</a>
                        <a href="{{ route('master.supplier') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('master.supplier') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Supplier</a>
                        <a href="{{ route('master.sales') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('master.sales') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Sales Team</a>
                        <a href="{{ route('master.users') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('master.users') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Users</a>
                    </div>
                </div>

                <p class="px-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6 transition-opacity duration-300"
                   :class="{'opacity-0 hidden': !desktopSidebarOpen}">
                   Operasional
                </p>

                <div x-data="{ open: {{ request()->routeIs('transaksi.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="desktopSidebarOpen ? open = !open : desktopSidebarOpen = true"
                        class="flex items-center w-full px-4 py-3 text-sm font-medium nav-item text-left hover:text-white focus:outline-none justify-between group">
                        <div class="flex items-center">
                            <i class="fas fa-shopping-cart w-5 h-5 transition-all text-slate-400 group-hover:text-white" :class="{'mx-auto': !desktopSidebarOpen, 'mr-3': desktopSidebarOpen}"></i>
                            <span class="whitespace-nowrap transition-all duration-300" :class="{'opacity-0 w-0 hidden': !desktopSidebarOpen}">Transaksi</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': open, 'hidden': !desktopSidebarOpen}"></i>
                    </button>
                    <div x-show="open && desktopSidebarOpen" x-collapse x-cloak class="space-y-1 pl-10 pr-2">
                        <a href="{{ route('transaksi.penjualan') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('transaksi.penjualan') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Order Penjualan</a>
                        <a href="{{ route('transaksi.retur') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('transaksi.retur') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Retur Barang</a>
                        <a href="{{ route('transaksi.ar') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('transaksi.ar') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">AR (Piutang)</a>
                        <a href="{{ route('transaksi.collection') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('transaksi.collection') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Collection</a>
                    </div>
                </div>

                <div x-data="{ open: {{ request()->routeIs('laporan.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="desktopSidebarOpen ? open = !open : desktopSidebarOpen = true"
                        class="flex items-center w-full px-4 py-3 text-sm font-medium nav-item text-left hover:text-white focus:outline-none justify-between group">
                        <div class="flex items-center">
                            <i class="fas fa-file-contract w-5 h-5 transition-all text-slate-400 group-hover:text-white" :class="{'mx-auto': !desktopSidebarOpen, 'mr-3': desktopSidebarOpen}"></i>
                            <span class="whitespace-nowrap transition-all duration-300" :class="{'opacity-0 w-0 hidden': !desktopSidebarOpen}">Laporan</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': open, 'hidden': !desktopSidebarOpen}"></i>
                    </button>
                    <div x-show="open && desktopSidebarOpen" x-collapse x-cloak class="space-y-1 pl-10 pr-2">
                        <a href="{{ route('laporan.rekap_penjualan') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('laporan.rekap_penjualan') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Rekap Penjualan</a>
                        <a href="{{ route('laporan.rekap_retur') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('laporan.rekap_retur') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Rekap Retur</a>
                        <a href="{{ route('laporan.rekap_ar') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('laporan.rekap_ar') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Rekap Piutang</a>
                        <a href="{{ route('laporan.rekap_collection') }}" class="block px-3 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('laporan.rekap_collection') ? 'text-indigo-400 bg-slate-800' : 'hover:text-white hover:bg-slate-800' }}">Rekap Collection</a>
                    </div>
                </div>

            </div>

            <div class="p-4 border-t border-white/5 bg-slate-950">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-slate-400 bg-slate-900 rounded-lg hover:bg-slate-800 hover:text-white transition-colors group">
                        <i class="fas fa-sign-out-alt transition-all" :class="{'mr-2': desktopSidebarOpen}"></i>
                        <span :class="{'hidden': !desktopSidebarOpen}">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50">

            <header class="sticky top-0 z-40 flex items-center justify-between px-6 py-3 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm transition-all duration-300">
                
                <div class="flex items-center gap-4">
                    <button @click="desktopSidebarOpen = !desktopSidebarOpen" class="text-slate-500 hover:text-indigo-600 focus:outline-none transition-colors">
                        <i class="fas fa-bars-staggered fa-lg"></i>
                    </button>
                    <div class="hidden md:flex items-center relative">
                        <i class="fas fa-search absolute left-3 text-gray-400 text-sm"></i>
                        <input type="text" placeholder="Cari invoice, pelanggan..." 
                               class="pl-10 pr-4 py-2 border border-gray-200 rounded-full text-sm w-64 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition-all bg-gray-50 hover:bg-white">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    
                    <button class="relative p-2 text-gray-400 hover:text-indigo-600 transition-colors rounded-full hover:bg-indigo-50">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="absolute top-1.5 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                    </button>

                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-3 focus:outline-none">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-bold text-gray-700 leading-none">{{ Auth::user()->name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500 mt-1">Administrator</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-blue-500 p-0.5 shadow-md hover:shadow-lg transition-all cursor-pointer">
                                <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-indigo-600 font-bold text-lg">
                                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                                </div>
                            </div>
                        </button>

                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl py-2 border border-gray-100 z-50 origin-top-right">
                            
                            <div class="px-4 py-2 border-b border-gray-100 md:hidden">
                                <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-user-circle w-5 mr-2"></i> Profile
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-cog w-5 mr-2"></i> Settings
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt w-5 mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-8">
                <div class="mb-6 flex justify-between items-end">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">@yield('header')</h2>
                    </div>
                </div>

                @if (session()->has('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                     class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 flex items-center justify-between shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-400 hover:text-green-600"><i class="fas fa-times"></i></button>
                </div>
                @endif

                @if (session()->has('error'))
                <div x-data="{ show: true }" x-show="show" 
                     class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 flex items-center justify-between shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-600"><i class="fas fa-times"></i></button>
                </div>
                @endif

                <div class="animate-fade-in-up">
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>
        </div>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity 
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 md:hidden"></div>
    </div>

    @livewireScripts
</body>
</html>