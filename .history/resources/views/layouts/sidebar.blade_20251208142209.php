<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 transform"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

    <div class="h-16 flex items-center justify-center border-b border-slate-800 bg-slate-950 shadow-md">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                <i class="fas fa-truck-fast"></i>
            </div>
            <span class="font-bold text-lg tracking-wide text-gray-100">Muliadis App</span>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group
           {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-chart-pie w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Dashboard</span>
        </a>

        <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider flex justify-between">
            <span>Master Data</span>
            <i class="fas fa-database text-[10px]"></i>
        </div>

        <a href="{{ route('master.sales') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors group {{ request()->routeIs('master.sales') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-user-tie w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Salesman</span>
        </a>

        <a href="#"
            class="flex items-center px-4 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors group">
            <i class="fas fa-box w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Produk</span>
        </a>

        <a href="#"
            class="flex items-center px-4 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors group">
            <i class="fas fa-truck w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Supplier</span>
        </a>

        <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider flex justify-between">
            <span>Operasional (Input)</span>
            <i class="fas fa-keyboard text-[10px]"></i>
        </div>

        <a href="{{ route('transaksi.penjualan') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors group {{ request()->routeIs('transaksi.penjualan') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-shopping-cart w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Input Penjualan</span>
        </a>
        <a href="{{ route('transaksi.retur') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors group {{ request()->routeIs('transaksi.retur') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-undo w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Input Retur</span>
        </a>
        <a href="{{ route('transaksi.ar') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors group {{ request()->routeIs('transaksi.ar') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-file-invoice-dollar w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Input Piutang</span>
        </a>
        <a href="{{ route('transaksi.collection') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors group {{ request()->routeIs('transaksi.collection') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-hand-holding-dollar w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Input Collection</span>
        </a>

        <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider flex justify-between">
            <span>Laporan Rekapitulasi</span>
            <i class="fas fa-table text-[10px]"></i>
        </div>

        <a href="{{ route('laporan.rekap-penjualan') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors group 
           {{ request()->routeIs('laporan.rekap-penjualan') ? 'bg-indigo-900/50 text-indigo-300 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-list-alt w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Rekap Penjualan</span>
        </a>

        <a href="{{ route('laporan.rekap-retur') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors group 
           {{ request()->routeIs('laporan.rekap-retur') ? 'bg-indigo-900/50 text-indigo-300 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-rotate-left w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Rekap Retur Jual</span>
        </a>

        <a href="{{ route('laporan.rekap-ar') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors group 
           {{ request()->routeIs('laporan.rekap-ar') ? 'bg-indigo-900/50 text-indigo-300 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-file-invoice w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Rekap Piutang (AR)</span>
        </a>

        <a href="{{ route('laporan.rekap-collection') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors group 
           {{ request()->routeIs('laporan.rekap-collection') ? 'bg-indigo-900/50 text-indigo-300 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-money-check-dollar w-5 text-center"></i>
            <span class="ml-3 font-medium text-sm">Rekap Collection</span>
        </a>

        <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider flex justify-between">
            <span>Analisa Kinerja</span>
            <i class="fas fa-chart-line text-[10px]"></i>
        </div>

        <a href="{{ route('laporan.kinerja-sales') }}"
            class="flex items-center px-4 py-2.5 rounded-lg transition-colors group 
           {{ request()->routeIs('laporan.kinerja-sales') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-chart-simple w-5 text-center {{ request()->routeIs('laporan.kinerja-sales') ? 'text-white' : 'text-emerald-500 group-hover:text-white' }}"></i>
            <span class="ml-3 font-bold text-sm">Rapor Kinerja Sales</span>
        </a>

    </div>

    <div class="p-4 border-t border-slate-800 bg-slate-950">
        <div class="flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-white uppercase">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-xs text-slate-500 truncate">Administrator</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>