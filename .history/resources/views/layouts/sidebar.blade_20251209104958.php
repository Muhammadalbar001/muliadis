<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 text-white transition-transform duration-300 transform flex flex-col"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

    <div
        class="h-20 flex-none flex items-center justify-between px-6 border-b border-slate-800/50 bg-slate-900/50 backdrop-blur-xl">
        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20 bg-gradient-to-br from-indigo-500 to-purple-600">
                <i class="fas fa-truck-fast text-sm"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg tracking-tight text-white leading-none">Muliadis</h1>
                <p class="text-[9px] font-medium text-slate-400 tracking-wider uppercase mt-0.5">Admin Panel</p>
            </div>
        </div>

        <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white transition-colors p-1">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-3 space-y-1 custom-scrollbar">

        <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Main</p>
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group text-sm font-medium mb-4
           {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i
                class="fas fa-chart-pie w-5 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span class="ml-3">Dashboard</span>
        </a>

        <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6">Master Data</p>

        <a href="{{ route('master.sales') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium {{ request()->routeIs('master.sales') ? 'bg-blue-600/10 text-blue-400 border border-blue-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-5 flex justify-center"><i
                    class="fas fa-user-tie {{ request()->routeIs('master.sales') ? 'text-blue-400' : 'text-slate-500 group-hover:text-white' }}"></i></span>
            <span class="ml-3">Salesman</span>
        </a>

        <a href="{{ route('master.produk') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium {{ request()->routeIs('master.produk') ? 'bg-indigo-600/10 text-indigo-400 border border-indigo-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-5 flex justify-center"><i
                    class="fas fa-box {{ request()->routeIs('master.produk') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-white' }}"></i></span>
            <span class="ml-3">Produk</span>
        </a>

        <a href="{{ route('master.supplier') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium {{ request()->routeIs('master.supplier') ? 'bg-pink-600/10 text-pink-400 border border-pink-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-5 flex justify-center"><i
                    class="fas fa-truck {{ request()->routeIs('master.supplier') ? 'text-pink-400' : 'text-slate-500 group-hover:text-white' }}"></i></span>
            <span class="ml-3">Supplier</span>
        </a>

        <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6">Transaksi</p>

        <a href="{{ route('transaksi.penjualan') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium {{ request()->routeIs('transaksi.penjualan') ? 'bg-emerald-600/10 text-emerald-400 border border-emerald-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-5 flex justify-center"><i
                    class="fas fa-shopping-cart {{ request()->routeIs('transaksi.penjualan') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-white' }}"></i></span>
            <span class="ml-3">Penjualan</span>
        </a>

        <a href="{{ route('transaksi.retur') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium {{ request()->routeIs('transaksi.retur') ? 'bg-rose-600/10 text-rose-400 border border-rose-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-5 flex justify-center"><i
                    class="fas fa-undo {{ request()->routeIs('transaksi.retur') ? 'text-rose-400' : 'text-slate-500 group-hover:text-white' }}"></i></span>
            <span class="ml-3">Retur</span>
        </a>

        <a href="{{ route('transaksi.ar') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium {{ request()->routeIs('transaksi.ar') ? 'bg-orange-600/10 text-orange-400 border border-orange-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-5 flex justify-center"><i
                    class="fas fa-file-invoice-dollar {{ request()->routeIs('transaksi.ar') ? 'text-orange-400' : 'text-slate-500 group-hover:text-white' }}"></i></span>
            <span class="ml-3">Piutang</span>
        </a>

        <a href="{{ route('transaksi.collection') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium {{ request()->routeIs('transaksi.collection') ? 'bg-cyan-600/10 text-cyan-400 border border-cyan-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <span class="w-5 flex justify-center"><i
                    class="fas fa-hand-holding-dollar {{ request()->routeIs('transaksi.collection') ? 'text-cyan-400' : 'text-slate-500 group-hover:text-white' }}"></i></span>
            <span class="ml-3">Collection</span>
        </a>

        <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6">Laporan</p>

        <a href="{{ route('laporan.rekap-penjualan') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium {{ request()->routeIs('laporan.rekap-penjualan') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-folder-open w-5 text-center text-slate-500 group-hover:text-emerald-400"></i> <span
                class="ml-3">Rekap Penjualan</span>
        </a>
        <a href="{{ route('laporan.rekap-retur') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium {{ request()->routeIs('laporan.rekap-retur') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-folder-open w-5 text-center text-slate-500 group-hover:text-rose-400"></i> <span
                class="ml-3">Rekap Retur</span>
        </a>
        <a href="{{ route('laporan.rekap-ar') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium {{ request()->routeIs('laporan.rekap-ar') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-folder-open w-5 text-center text-slate-500 group-hover:text-orange-400"></i> <span
                class="ml-3">Rekap Piutang</span>
        </a>
        <a href="{{ route('laporan.rekap-collection') }}"
            class="flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium {{ request()->routeIs('laporan.rekap-collection') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-folder-open w-5 text-center text-slate-500 group-hover:text-cyan-400"></i> <span
                class="ml-3">Rekap Collection</span>
        </a>

        <a href="{{ route('laporan.kinerja-sales') }}"
            class="mt-4 mx-2 flex items-center px-4 py-3 rounded-xl transition-all duration-200 group text-sm font-bold border border-purple-500/30 {{ request()->routeIs('laporan.kinerja-sales') ? 'bg-purple-600 text-white shadow-lg shadow-purple-500/20' : 'bg-purple-500/10 text-purple-300 hover:bg-purple-500/20' }}">
            <i class="fas fa-trophy w-5 text-center"></i> <span class="ml-3">Rapor Kinerja Sales</span>
        </a>

        <div class="h-20"></div>
    </div>

    <div class="p-4 border-t border-slate-800 bg-slate-950 flex-none">
        <div class="flex items-center justify-between gap-3">

            <a href="{{ route('profile.edit') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 flex-1 min-w-0 group hover:bg-slate-800 p-2 rounded-xl transition-all"
                title="Edit Profil">

                <div class="relative">
                    <div
                        class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-sm font-bold text-white border-2 border-slate-600 group-hover:border-indigo-500 transition-colors">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div
                        class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-slate-900 rounded-full">
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate group-hover:text-indigo-400 transition-colors">
                        {{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-[10px] text-slate-500 truncate group-hover:text-slate-400">Administrator</p>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-red-500/20 hover:border-red-500/50 border border-transparent rounded-xl transition-all"
                    title="Logout">
                    <i class="fas fa-power-off"></i>
                </button>
            </form>

        </div>
    </div>
</aside>
</div>
</aside>