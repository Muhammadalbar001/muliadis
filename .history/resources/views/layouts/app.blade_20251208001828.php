<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Muliadis') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    [x-cloak] {
        display: none !important;
    }

    /* Scrollbar Halus */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    </style>
</head>

<body class="bg-slate-50 antialiased text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 ease-in-out transform md:translate-x-0 md:static md:inset-0 shadow-xl flex flex-col"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <div class="flex items-center justify-center h-20 bg-slate-950/50 shadow-sm border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded bg-indigo-600 flex items-center justify-center text-white">
                        <i class="fas fa-cube text-sm"></i>
                    </div>
                    <span class="text-lg font-bold tracking-wide">MULIADIS</span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto py-6 px-3 space-y-1">

                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-chart-pie w-6 text-center mr-2"></i> Dashboard
                </a>

                <div class="mt-6 mb-2 px-4 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Master Data
                </div>

                <a href="{{ route('master.produk') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('master.produk') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-box w-6 text-center mr-2"></i> Produk
                </a>
                <a href="{{ route('master.supplier') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('master.supplier') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-truck w-6 text-center mr-2"></i> Supplier
                </a>

                <div class="mt-6 mb-2 px-4 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Transaksi
                </div>

                <a href="{{ route('transaksi.penjualan') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('transaksi.penjualan') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-shopping-cart w-6 text-center mr-2"></i> Penjualan
                </a>
                <a href="{{ route('transaksi.retur') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('transaksi.retur') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-undo-alt w-6 text-center mr-2"></i> Retur
                </a>
                <a href="{{ route('transaksi.ar') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('transaksi.ar') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-file-invoice-dollar w-6 text-center mr-2"></i> Piutang (AR)
                </a>
                <a href="{{ route('transaksi.collection') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('transaksi.collection') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-cash-register w-6 text-center mr-2"></i> Collection
                </a>

                <div class="mt-6 mb-2 px-4 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Laporan</div>

                <a href="{{ route('laporan.penjualan') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('laporan.penjualan') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-chart-line w-6 text-center mr-2"></i> Rekap Penjualan
                </a>
                <a href="{{ route('laporan.retur') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('laporan.retur') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-reply-all w-6 text-center mr-2"></i> Rekap Retur
                </a>
                <a href="{{ route('laporan.ar') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('laporan.ar') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-money-bill-wave w-6 text-center mr-2"></i> Rekap AR
                </a>
                <a href="{{ route('laporan.collection') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('laporan.collection') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-hand-holding-usd w-6 text-center mr-2"></i> Rekap Collection
                </a>

            </div>

            <div class="p-4 bg-slate-950/30 border-t border-slate-800">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-white text-xs">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                        <p class="text-xs text-slate-500 truncate">Admin</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-white transition-colors" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden relative">

            <header
                class="bg-white border-b border-gray-100 h-16 flex items-center justify-between px-6 shadow-sm z-40">

                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <div>
                        <h2 class="font-bold text-xl text-slate-800 leading-tight">
                            @if (isset($header)) {{ $header }} @else Dashboard @endif
                        </h2>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button class="text-gray-400 hover:text-indigo-600 transition relative">
                        <i class="far fa-bell text-lg"></i>
                        <span
                            class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500"></span>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6 scroll-smooth">
                {{ $slot }}
            </main>

            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/80 z-40 md:hidden glass">
            </div>

        </div>
    </div>

    <div x-data="{ show: false, message: '', type: 'success', title: '' }"
        @show-toast.window="show = true; title = $event.detail.title; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
        x-show="show" x-transition.move.right
        class="fixed top-5 right-5 z-50 flex items-start w-full max-w-xs p-4 space-x-3 text-gray-500 bg-white rounded-lg shadow-2xl border-l-4"
        :class="type === 'success' ? 'border-green-500' : 'border-red-500'" style="display: none;">

        <div class="flex-1">
            <h3 class="text-sm font-bold text-gray-900" x-text="title"></h3>
            <p class="text-xs text-gray-600 mt-1 whitespace-pre-line" x-text="message"></p>
        </div>
        <button @click="show = false" class="text-gray-400 hover:text-gray-900">
            <i class="fas fa-times"></i>
        </button>
    </div>

</body>

</html>