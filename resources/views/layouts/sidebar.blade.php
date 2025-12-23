<aside
    class="fixed inset-y-0 left-0 z-50 bg-[#0a0a0a] border-r border-neutral-800 text-neutral-300 transition-all duration-300 flex flex-col"
    :class="{ 
        'translate-x-0': sidebarOpen, 
        '-translate-x-full': !sidebarOpen, 
        'lg:translate-x-0': true,
        'w-64': isSidebarExpanded,
        'w-20': !isSidebarExpanded
    }" x-cloak>

    <div class="h-20 flex-none flex items-center px-6 border-b border-neutral-800 bg-[#121212]"
        :class="isSidebarExpanded ? 'justify-between' : 'justify-center'">

        <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
            <div
                class="w-10 h-10 rounded-xl flex-none flex items-center justify-center text-white shadow-lg shadow-blue-600/20 bg-blue-600">
                <i class="fas fa-truck-fast text-sm"></i>
            </div>
            <div x-show="isSidebarExpanded" x-transition.opacity.duration.200ms>
                <h1 class="font-black text-sm tracking-tighter text-white uppercase leading-none">PT MULIA ANUGERAH</h1>
                <p class="text-[8px] font-bold text-blue-500 tracking-[0.2em] uppercase mt-1">Distribusindo</p>
            </div>
        </div>

        <button @click="sidebarOpen = false"
            class="lg:hidden text-neutral-500 hover:text-white transition-colors p-1 focus:outline-none absolute right-4">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto py-6 space-y-1 custom-scrollbar overflow-x-hidden"
        :class="isSidebarExpanded ? 'px-3' : 'px-2'">

        <div x-show="isSidebarExpanded"
            class="px-4 text-[9px] font-black text-neutral-600 uppercase tracking-[0.3em] mb-3 transition-opacity">Main
            Dashboard</div>
        <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-neutral-800 mb-4 mt-2"></div>

        <a href="{{ route('dashboard') }}"
            class="flex items-center py-3 rounded-xl transition-all duration-200 group text-xs font-bold mb-4 relative
            {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-neutral-500 hover:bg-neutral-800 hover:text-white' }}"
            :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
            <i
                class="fas fa-chart-pie w-5 text-center text-base {{ request()->routeIs('dashboard') ? 'text-white' : 'text-neutral-500 group-hover:text-blue-400' }}"></i>
            <span x-show="isSidebarExpanded" class="ml-3 truncate uppercase tracking-widest">Dashboard</span>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-neutral-800 text-white text-[10px] px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap border border-neutral-700 shadow-xl ml-2 uppercase font-black">
                Dashboard
            </div>
        </a>

        @if(in_array(auth()->user()->role, ['admin', 'pimpinan']))
        <div x-show="isSidebarExpanded"
            class="px-4 text-[9px] font-black text-neutral-600 uppercase tracking-[0.3em] mb-3 mt-8 transition-opacity">
            Master Data</div>
        <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-neutral-800 mb-4 mt-4"></div>

        @foreach([
        ['route' => 'master.sales', 'icon' => 'fa-user-tie', 'label' => 'Salesman'],
        ['route' => 'master.produk', 'icon' => 'fa-box', 'label' => 'Produk'],
        ['route' => 'master.supplier', 'icon' => 'fa-truck', 'label' => 'Supplier'],
        ['route' => 'master.user', 'icon' => 'fa-users-cog', 'label' => 'User'],
        ] as $menu)
        <a href="{{ route($menu['route']) }}"
            class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-[11px] font-bold mb-1 relative uppercase tracking-wider
            {{ request()->routeIs($menu['route']) ? 'bg-blue-600/10 text-blue-400 border border-blue-500/20' : 'text-neutral-500 hover:bg-neutral-800 hover:text-white border border-transparent' }}"
            :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
            <span class="w-5 flex justify-center"><i
                    class="fas {{ $menu['icon'] }} {{ request()->routeIs($menu['route']) ? 'text-blue-400' : 'text-neutral-600 group-hover:text-blue-400' }}"></i></span>
            <span x-show="isSidebarExpanded" class="ml-3 truncate">{{ $menu['label'] }}</span>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-neutral-800 text-white text-[10px] px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap border border-neutral-700 shadow-xl ml-2 uppercase font-black">
                {{ $menu['label'] }}
            </div>
        </a>
        @endforeach
        @endif

        <div x-show="isSidebarExpanded"
            class="px-4 text-[9px] font-black text-neutral-600 uppercase tracking-[0.3em] mb-3 mt-8 transition-opacity">
            Transaksi Digital</div>
        <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-neutral-800 mb-4 mt-4"></div>

        @foreach([
        ['route' => 'transaksi.penjualan', 'icon' => 'fa-shopping-cart', 'label' => 'Penjualan'],
        ['route' => 'transaksi.retur', 'icon' => 'fa-undo', 'label' => 'Retur'],
        ['route' => 'transaksi.ar', 'icon' => 'fa-file-invoice-dollar', 'label' => 'Piutang (AR)'],
        ['route' => 'transaksi.collection', 'icon' => 'fa-hand-holding-dollar', 'label' => 'Collection'],
        ] as $menu)
        <a href="{{ route($menu['route']) }}"
            class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-[11px] font-bold mb-1 relative uppercase tracking-wider
            {{ request()->routeIs($menu['route']) ? 'bg-neutral-800 text-blue-400 border border-blue-500/30' : 'text-neutral-500 hover:bg-neutral-800 hover:text-white border border-transparent' }}"
            :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
            <span class="w-5 flex justify-center"><i
                    class="fas {{ $menu['icon'] }} {{ request()->routeIs($menu['route']) ? 'text-blue-400' : 'text-neutral-600 group-hover:text-blue-400' }}"></i></span>
            <span x-show="isSidebarExpanded" class="ml-3 truncate">{{ $menu['label'] }}</span>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-neutral-800 text-white text-[10px] px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap border border-neutral-700 shadow-xl ml-2 uppercase font-black">
                {{ $menu['label'] }}
            </div>
        </a>
        @endforeach

        @if(in_array(auth()->user()->role, ['admin', 'pimpinan']))
        <div x-show="isSidebarExpanded"
            class="px-4 text-[9px] font-black text-neutral-600 uppercase tracking-[0.3em] mb-3 mt-8 transition-opacity">
            Executive Report</div>
        <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-neutral-800 mb-4 mt-4"></div>

        {{-- 1. KINERJA SALES (Kuning) --}}
        <a href="{{ route('laporan.kinerja-sales') }}"
            class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-[11px] font-black border border-yellow-500/10 mb-2 relative uppercase tracking-wider
            {{ request()->routeIs('laporan.kinerja-sales') ? 'bg-yellow-500/10 text-yellow-500' : 'bg-[#121212] text-neutral-400 hover:bg-neutral-800 hover:text-white' }}"
            :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
            <span class="w-5 flex justify-center"><i
                    class="fas fa-trophy {{ request()->routeIs('laporan.kinerja-sales') ? 'text-yellow-500' : 'text-yellow-700' }}"></i></span>
            <span x-show="isSidebarExpanded" class="ml-3 truncate">Kinerja Sales</span>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-neutral-800 text-white text-[10px] px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap border border-neutral-700 shadow-xl ml-2 uppercase font-black">
                Kinerja Sales
            </div>
        </a>

        {{-- 2. STOCK ANALYSIS (Hijau / Emerald) --}}
        <a href="{{ route('pimpinan.stock-analysis') }}"
            class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-[11px] font-black border border-emerald-500/10 mb-2 relative uppercase tracking-wider
            {{ request()->routeIs('pimpinan.stock-analysis') ? 'bg-emerald-500/10 text-emerald-500' : 'bg-[#121212] text-neutral-400 hover:bg-neutral-800 hover:text-white' }}"
            :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
            <span class="w-5 flex justify-center"><i
                    class="fas fa-boxes-stacked {{ request()->routeIs('pimpinan.stock-analysis') ? 'text-emerald-500' : 'text-emerald-700' }}"></i></span>
            <span x-show="isSidebarExpanded" class="ml-3 truncate">Analisa Stok</span>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-neutral-800 text-white text-[10px] px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap border border-neutral-700 shadow-xl ml-2 uppercase font-black">
                Analisa Stok
            </div>
        </a>

        {{-- 3. PROFIT ANALYSIS (Biru / Blue) - BARU --}}
        <a href="{{ route('pimpinan.profit-analysis') }}"
            class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-[11px] font-black border border-blue-500/10 mb-2 relative uppercase tracking-wider
            {{ request()->routeIs('pimpinan.profit-analysis') ? 'bg-blue-500/10 text-blue-500' : 'bg-[#121212] text-neutral-400 hover:bg-neutral-800 hover:text-white' }}"
            :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
            <span class="w-5 flex justify-center"><i
                    class="fas fa-chart-line {{ request()->routeIs('pimpinan.profit-analysis') ? 'text-blue-500' : 'text-blue-700' }}"></i></span>
            <span x-show="isSidebarExpanded" class="ml-3 truncate">Laba Rugi Produk</span>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-neutral-800 text-white text-[10px] px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap border border-neutral-700 shadow-xl ml-2 uppercase font-black">
                Laba Rugi Produk
            </div>
        </a>

        {{-- 4. DATA REKAP (Dropdown) --}}
        <div x-data="{ open: {{ request()->routeIs('laporan.rekap*') ? 'true' : 'false' }} }">
            <button @click="isSidebarExpanded ? open = !open : toggleSidebar()"
                class="flex items-center w-full py-2.5 rounded-xl transition-all duration-200 group text-[11px] font-bold text-neutral-500 hover:bg-neutral-800 hover:text-white relative uppercase tracking-wider"
                :class="isSidebarExpanded ? 'px-4 justify-between' : 'justify-center'">
                <div class="flex items-center">
                    <span class="w-5 flex justify-center"><i
                            class="fas fa-folder-open text-neutral-600 group-hover:text-blue-400"></i></span>
                    <span x-show="isSidebarExpanded" class="ml-3">Data Rekap</span>
                </div>
                <i x-show="isSidebarExpanded" class="fas fa-chevron-down text-[8px] transition-transform duration-200"
                    :class="{'rotate-180': open}"></i>
            </button>

            <div x-show="open && isSidebarExpanded" x-transition x-cloak
                class="space-y-1 mt-1 px-2 border-l border-neutral-800 ml-6">
                @foreach([
                ['route' => 'laporan.rekap-penjualan', 'label' => 'Penjualan'],
                ['route' => 'laporan.rekap-retur', 'label' => 'Retur'],
                ['route' => 'laporan.rekap-ar', 'label' => 'Piutang'],
                ['route' => 'laporan.rekap-collection', 'label' => 'Collection'],
                ] as $sub)
                <a href="{{ route($sub['route']) }}"
                    class="flex items-center px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all {{ request()->routeIs($sub['route']) ? 'text-blue-400 bg-blue-500/10' : 'text-neutral-500 hover:text-white hover:bg-neutral-800' }}">
                    <div
                        class="w-1 h-1 rounded-full mr-3 {{ request()->routeIs($sub['route']) ? 'bg-blue-400' : 'bg-neutral-700' }}">
                    </div>
                    {{ $sub['label'] }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <div class="p-4 border-t border-neutral-800 bg-[#070707] flex-none">
        <button @click="toggleSidebar()"
            class="flex w-full items-center justify-center p-2 rounded-xl bg-neutral-900 text-neutral-500 hover:text-blue-400 hover:bg-neutral-800 transition-all border border-neutral-800">
            <i class="fas fa-chevron-left transition-transform duration-300"
                :class="!isSidebarExpanded ? 'rotate-180' : ''"></i>
            <span x-show="isSidebarExpanded" class="ml-2 text-[9px] font-black uppercase tracking-[0.2em]">Minimize
                Sidebar</span>
        </button>
    </div>
</aside>