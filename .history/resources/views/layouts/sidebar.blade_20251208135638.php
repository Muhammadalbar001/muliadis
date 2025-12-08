<aside class="fixed inset-y-0 left-0 z-50 bg-slate-900 text-white transition-all duration-300 flex flex-col shadow-2xl"
    :class="sidebarOpen ? 'w-64' : 'w-20 hidden lg:flex'">

    <div class="h-16 flex items-center justify-center border-b border-slate-800 bg-slate-950">
        <div class="flex items-center gap-3" x-show="sidebarOpen" x-transition>
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                <i class="fas fa-truck-fast text-white text-sm"></i>
            </div>
            <span class="font-bold text-lg tracking-wide">Muliadis</span>
        </div>
        <div x-show="!sidebarOpen" class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
            <i class="fas fa-truck-fast text-white"></i>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        <a href="{{ route('dashboard') }}"
            class="flex items-center px-3 py-3 rounded-lg transition-colors group relative
           {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-chart-pie w-6 text-center text-lg {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3 font-medium text-sm transition-opacity duration-200"
                :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'">Dashboard</span>
        </a>

        <div class="mt-6 mb-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider"
            :class="!sidebarOpen && 'hidden'">
            Master Data
        </div>

        <a href="{{ route('master.sales') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition-colors group relative
           {{ request()->routeIs('master.sales') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-user-tie w-6 text-center {{ request()->routeIs('master.sales') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3 font-medium text-sm" :class="!sidebarOpen && 'hidden'">Salesman & Target</span>
        </a>

        <div class="mt-6 mb-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider"
            :class="!sidebarOpen && 'hidden'">
            Operasional
        </div>

        <a href="{{ route('transaksi.penjualan') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition-colors group relative
           {{ request()->routeIs('transaksi.penjualan') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-shopping-cart w-6 text-center {{ request()->routeIs('transaksi.penjualan') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3 font-medium text-sm" :class="!sidebarOpen && 'hidden'">Penjualan</span>
        </a>

        <a href="{{ route('transaksi.retur') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition-colors group relative
           {{ request()->routeIs('transaksi.retur') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-undo w-6 text-center {{ request()->routeIs('transaksi.retur') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3 font-medium text-sm" :class="!sidebarOpen && 'hidden'">Retur Barang</span>
        </a>

        <a href="{{ route('transaksi.ar') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition-colors group relative
           {{ request()->routeIs('transaksi.ar') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-file-invoice-dollar w-6 text-center {{ request()->routeIs('transaksi.ar') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3 font-medium text-sm" :class="!sidebarOpen && 'hidden'">Piutang (AR)</span>
        </a>

        <a href="{{ route('transaksi.collection') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition-colors group relative
           {{ request()->routeIs('transaksi.collection') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-hand-holding-dollar w-6 text-center {{ request()->routeIs('transaksi.collection') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3 font-medium text-sm" :class="!sidebarOpen && 'hidden'">Collection</span>
        </a>

        <div class="mt-6 mb-2 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider"
            :class="!sidebarOpen && 'hidden'">
            Analisa & Laporan
        </div>

        <a href="{{ route('laporan.kinerja-sales') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition-colors group relative
           {{ request()->routeIs('laporan.kinerja-sales') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-chart-simple w-6 text-center {{ request()->routeIs('laporan.kinerja-sales') ? 'text-white' : 'text-emerald-500 group-hover:text-white' }}"></i>
            <span class="ml-3 font-bold text-sm" :class="!sidebarOpen && 'hidden'">Rapor Kinerja Sales</span>
        </a>

    </div>

    <div class="p-4 border-t border-slate-800 bg-slate-950">
        <div class="flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-white">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="flex-1 overflow-hidden" :class="!sidebarOpen && 'hidden'">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-slate-500 truncate">Administrator</p>
            </div>

            <form method="POST" action="{{ route('logout') }}" :class="!sidebarOpen && 'hidden'">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>