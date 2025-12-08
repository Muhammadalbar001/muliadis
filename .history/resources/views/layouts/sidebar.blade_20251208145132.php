<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 transform flex flex-col h-full shadow-2xl"
    :class="sidebarOpen ? 'translate-x-0 relative' : '-translate-x-full absolute lg:static lg:-translate-x-64'">

    <div class="h-16 flex-none flex items-center justify-center border-b border-slate-800 bg-slate-950">
        <div class="flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                <i class="fas fa-truck-fast"></i>
            </div>
            <span class="font-bold text-lg tracking-wide text-gray-100">Muliadis</span>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">

        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-chart-pie w-5 text-center"></i>
            <span class="ml-3 font-bold text-sm">Dashboard</span>
        </a>

        <div class="mt-6 mb-2 px-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Master Data</div>

        <a href="{{ route('master.sales') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('master.sales') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-user-tie w-5 text-center"></i><span class="ml-3 font-medium text-sm">Salesman</span>
        </a>
        <a href="{{ route('master.produk') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('master.produk') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-box w-5 text-center"></i><span class="ml-3 font-medium text-sm">Produk</span>
        </a>
        <a href="{{ route('master.supplier') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('master.supplier') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-truck w-5 text-center"></i><span class="ml-3 font-medium text-sm">Supplier</span>
        </a>

        <div class="mt-6 mb-2 px-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Operasional</div>

        <a href="{{ route('transaksi.penjualan') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('transaksi.penjualan') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-shopping-cart w-5 text-center"></i><span class="ml-3 font-medium text-sm">Input
                Penjualan</span>
        </a>
        <a href="{{ route('transaksi.retur') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('transaksi.retur') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-undo w-5 text-center"></i><span class="ml-3 font-medium text-sm">Input Retur</span>
        </a>
        <a href="{{ route('transaksi.ar') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('transaksi.ar') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-file-invoice-dollar w-5 text-center"></i><span class="ml-3 font-medium text-sm">Input
                Piutang</span>
        </a>
        <a href="{{ route('transaksi.collection') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('transaksi.collection') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-hand-holding-dollar w-5 text-center"></i><span class="ml-3 font-medium text-sm">Input
                Collection</span>
        </a>

        <div class="mt-6 mb-2 px-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Laporan Rekap
        </div>

        <a href="{{ route('laporan.rekap-penjualan') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('laporan.rekap-penjualan') ? 'text-indigo-300 bg-slate-800' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-list-alt w-5 text-center"></i><span class="ml-3 font-medium text-sm">Rekap Penjualan</span>
        </a>
        <a href="{{ route('laporan.rekap-retur') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('laporan.rekap-retur') ? 'text-indigo-300 bg-slate-800' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-history w-5 text-center"></i><span class="ml-3 font-medium text-sm">Rekap Retur</span>
        </a>
        <a href="{{ route('laporan.rekap-ar') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('laporan.rekap-ar') ? 'text-indigo-300 bg-slate-800' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-file-contract w-5 text-center"></i><span class="ml-3 font-medium text-sm">Rekap
                Piutang</span>
        </a>
        <a href="{{ route('laporan.rekap-collection') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('laporan.rekap-collection') ? 'text-indigo-300 bg-slate-800' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fas fa-money-bill-wave w-5 text-center"></i><span class="ml-3 font-medium text-sm">Rekap
                Collection</span>
        </a>

        <div class="mt-6 mb-2 px-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Analisa Kinerja
        </div>

        <a href="{{ route('laporan.kinerja-sales') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-colors group {{ request()->routeIs('laporan.kinerja-sales') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-chart-simple w-5 text-center {{ request()->routeIs('laporan.kinerja-sales') ? 'text-white' : 'text-emerald-500 group-hover:text-white' }}"></i>
            <span class="ml-3 font-bold text-sm">Rapor Kinerja Sales</span>
        </a>

        <div class="h-10"></div>
    </div>

    <div class="p-4 border-t border-slate-800 bg-slate-950 flex-none">
        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xs font-bold text-white uppercase shadow-md">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-[10px] text-slate-500 truncate uppercase tracking-wider">Administrator</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-slate-400 hover:text-red-500 transition-colors p-2 rounded-lg hover:bg-slate-800"
                    title="Logout">
                    <i class="fas fa-power-off"></i>
                </button>
            </form>
        </div>
    </div>
</aside>