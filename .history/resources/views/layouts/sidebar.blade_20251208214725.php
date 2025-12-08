<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 transform flex flex-col"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

    <div class="h-16 flex-none flex items-center justify-center border-b border-slate-800 bg-slate-950 shadow-md">
        <div class="flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                <i class="fas fa-truck-fast"></i>
            </div>
            <span class="font-bold text-lg tracking-wide text-gray-100">Muliadis App</span>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">

        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group font-medium text-sm
           {{ request()->routeIs('dashboard') 
              ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-chart-pie w-5 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3">Dashboard</span>
        </a>

        <div
            class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider flex justify-between items-center">
            <span>Master Data</span>
        </div>

        <a href="{{ route('master.sales') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('master.sales') 
              ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-user-tie w-5 text-center {{ request()->routeIs('master.sales') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3">Salesman</span>
        </a>

        <a href="{{ route('master.produk') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('master.produk') 
              ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-box w-5 text-center {{ request()->routeIs('master.produk') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3">Produk</span>
        </a>

        <a href="{{ route('master.supplier') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('master.supplier') 
              ? 'bg-pink-600 text-white shadow-lg shadow-pink-500/30' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-truck w-5 text-center {{ request()->routeIs('master.supplier') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3">Supplier</span>
        </a>

        <div
            class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider flex justify-between items-center">
            <span>Operasional (Input)</span>
        </div>

        <a href="{{ route('transaksi.penjualan') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('transaksi.penjualan') 
              ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/30' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-shopping-cart w-5 text-center {{ request()->routeIs('transaksi.penjualan') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3">Input Penjualan</span>
        </a>

        <a href="{{ route('transaksi.retur') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('transaksi.retur') 
              ? 'bg-rose-600 text-white shadow-lg shadow-rose-500/30' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-undo w-5 text-center {{ request()->routeIs('transaksi.retur') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3">Input Retur</span>
        </a>

        <a href="{{ route('transaksi.ar') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('transaksi.ar') 
              ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/30' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-file-invoice-dollar w-5 text-center {{ request()->routeIs('transaksi.ar') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3">Input Piutang</span>
        </a>

        <a href="{{ route('transaksi.collection') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('transaksi.collection') 
              ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-hand-holding-dollar w-5 text-center {{ request()->routeIs('transaksi.collection') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3">Input Collection</span>
        </a>

        <div
            class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider flex justify-between items-center">
            <span>Laporan Rekapitulasi</span>
        </div>

        <a href="{{ route('laporan.rekap-penjualan') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('laporan.rekap-penjualan') 
              ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-list-alt w-5 text-center"></i>
            <span class="ml-3">Rekap Penjualan</span>
        </a>

        <a href="{{ route('laporan.rekap-retur') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('laporan.rekap-retur') 
              ? 'bg-gradient-to-r from-rose-600 to-pink-600 text-white shadow-md' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-rotate-left w-5 text-center"></i>
            <span class="ml-3">Rekap Retur Jual</span>
        </a>

        <a href="{{ route('laporan.rekap-ar') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('laporan.rekap-ar') 
              ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-md' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-file-invoice w-5 text-center"></i>
            <span class="ml-3">Rekap Piutang (AR)</span>
        </a>

        <a href="{{ route('laporan.rekap-collection') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('laporan.rekap-collection') 
              ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-money-check-dollar w-5 text-center"></i>
            <span class="ml-3">Rekap Collection</span>
        </a>

        <div
            class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider flex justify-between items-center">
            <span>Analisa Kinerja</span>
        </div>

        <a href="{{ route('laporan.kinerja-sales') }}" class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium
           {{ request()->routeIs('laporan.kinerja-sales') 
              ? 'bg-gradient-to-r from-violet-600 to-purple-600 text-white shadow-lg shadow-purple-500/30' 
              : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-chart-line w-5 text-center"></i>
            <span class="ml-3">Rapor Kinerja Sales</span>
        </a>

        <div class="h-10"></div>
    </div>

    <div class="p-4 border-t border-slate-800 bg-slate-950 flex-none">
        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center text-sm font-bold text-white uppercase border border-slate-600">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-[10px] text-slate-400 truncate">Administrator</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-red-400 transition-colors p-1" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>