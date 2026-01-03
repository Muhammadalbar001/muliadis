<aside class="fixed inset-y-0 left-0 z-50 transition-all duration-300 flex flex-col border-r font-jakarta
    dark:bg-[#0a0a0a] dark:border-neutral-800 dark:text-neutral-300
    bg-white border-slate-200 text-slate-700" :class="{ 
        'translate-x-0': sidebarOpen, 
        '-translate-x-full': !sidebarOpen, 
        'lg:translate-x-0': true,
        'w-64': isSidebarExpanded,
        'w-20': !isSidebarExpanded
    }" x-cloak>

    {{-- BRAND LOGO AREA --}}
    <div class="h-24 flex-none flex items-center px-6 border-b transition-colors
        dark:border-neutral-800 dark:bg-[#121212]
        border-slate-100 bg-slate-50/50" :class="isSidebarExpanded ? 'justify-between' : 'justify-center'">
        <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
            <div
                class="w-11 h-11 rounded-2xl flex-none flex items-center justify-center text-white shadow-xl bg-gradient-to-tr from-indigo-600 to-blue-500 ring-4 ring-indigo-500/10">
                <i class="fas fa-box-open text-lg"></i>
            </div>
            <div x-show="isSidebarExpanded" x-transition.opacity.duration.200ms>
                <h1 class="font-black text-sm tracking-tighter dark:text-white text-slate-900 uppercase leading-none">PT
                    MULIA ANUGERAH</h1>
                <p class="text-[8px] font-bold text-indigo-600 tracking-[0.2em] uppercase mt-1">Distribusindo System</p>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden text-neutral-500 hover:text-rose-500 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    {{-- MENU WRAPPER --}}
    <div class="flex-1 overflow-y-auto py-6 space-y-8 custom-scrollbar overflow-x-hidden"
        :class="isSidebarExpanded ? 'px-4' : 'px-2'">

        {{-- 1. EXECUTIVE CONTROL (BLUE) --}}
        <div>
            <div x-show="isSidebarExpanded"
                class="px-4 text-[9px] font-black text-blue-600 dark:text-blue-500 uppercase tracking-[0.3em] mb-4">
                Executive Control</div>
            <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-blue-500/20 mb-4"></div>

            <div class="space-y-1">
                @foreach([
                ['route' => 'admin.dashboard', 'icon' => 'fa-chart-pie', 'label' => 'Dashboard'],
                ['route' => 'pimpinan.stock-analysis', 'icon' => 'fa-layer-group', 'label' => 'Stock Analysis'],
                ['route' => 'pimpinan.profit-analysis', 'icon' => 'fa-hand-holding-usd', 'label' => 'Profit Analysis'],
                ] as $menu)
                <a href="{{ route($menu['route']) }}"
                    class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-[11px] font-bold relative uppercase tracking-wider
                    {{ request()->routeIs($menu['route']) ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'dark:text-neutral-500 text-slate-500 dark:hover:bg-neutral-800 hover:bg-blue-50 hover:text-blue-600' }}"
                    :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
                    <span class="w-5 flex justify-center"><i
                            class="fas {{ $menu['icon'] }} {{ request()->routeIs($menu['route']) ? '' : 'group-hover:text-blue-500' }}"></i></span>
                    <span x-show="isSidebarExpanded" class="ml-3 truncate">{{ $menu['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- 2. MANAJEMEN MASTER (INDIGO) --}}
        <div>
            <div x-show="isSidebarExpanded"
                class="px-4 text-[9px] font-black text-indigo-600 dark:text-indigo-500 uppercase tracking-[0.3em] mb-4">
                Manajemen Master</div>
            <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-indigo-500/20 mb-4"></div>

            <div class="space-y-1">
                @foreach([
                ['route' => 'master.produk', 'icon' => 'fa-boxes', 'label' => 'Data Produk'],
                ['route' => 'master.supplier', 'icon' => 'fa-truck-loading', 'label' => 'Data Supplier'],
                ['route' => 'master.sales', 'icon' => 'fa-user-tie', 'label' => 'Data Salesman'],
                ['route' => 'master.user', 'icon' => 'fa-users-cog', 'label' => 'Kontrol Akses'],
                ] as $menu)
                <a href="{{ route($menu['route']) }}"
                    class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-[11px] font-bold relative uppercase tracking-wider
                    {{ request()->routeIs($menu['route']) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'dark:text-neutral-500 text-slate-500 dark:hover:bg-neutral-800 hover:bg-indigo-50 hover:text-indigo-600' }}"
                    :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
                    <span class="w-5 flex justify-center"><i
                            class="fas {{ $menu['icon'] }} {{ request()->routeIs($menu['route']) ? '' : 'group-hover:text-indigo-500' }}"></i></span>
                    <span x-show="isSidebarExpanded" class="ml-3 truncate">{{ $menu['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- 3. OPERASIONAL HUB (EMERALD) --}}
        <div>
            <div x-show="isSidebarExpanded"
                class="px-4 text-[9px] font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-[0.3em] mb-4">
                Operasional Hub</div>
            <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-emerald-500/20 mb-4"></div>

            <div class="space-y-1">
                @foreach([
                ['route' => 'transaksi.penjualan', 'icon' => 'fa-shopping-cart', 'label' => 'Data Penjualan'],
                ['route' => 'transaksi.retur', 'icon' => 'fa-undo-alt', 'label' => 'Data Retur'],
                ['route' => 'transaksi.ar', 'icon' => 'fa-file-invoice-dollar', 'label' => 'Data Piutang'],
                ['route' => 'transaksi.collection', 'icon' => 'fa-cash-register', 'label' => 'Data Tagihan'],
                ] as $menu)
                <a href="{{ route($menu['route']) }}"
                    class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-[11px] font-bold relative uppercase tracking-wider
                    {{ request()->routeIs($menu['route']) ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'dark:text-neutral-500 text-slate-500 dark:hover:bg-neutral-800 hover:bg-emerald-50 hover:text-emerald-600' }}"
                    :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
                    <span class="w-5 flex justify-center"><i
                            class="fas {{ $menu['icon'] }} {{ request()->routeIs($menu['route']) ? '' : 'group-hover:text-emerald-500' }}"></i></span>
                    <span x-show="isSidebarExpanded" class="ml-3 truncate">{{ $menu['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- 4. DATA REKAPITULASI (AMBER) --}}
        <div>
            <div x-show="isSidebarExpanded"
                class="px-4 text-[9px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-[0.3em] mb-4">
                Data Rekapitulasi</div>
            <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-amber-500/20 mb-4"></div>

            <div class="space-y-1">
                @foreach([
                ['route' => 'laporan.kinerja-sales', 'icon' => 'fa-user-chart', 'label' => 'Kinerja Sales'],
                ['route' => 'laporan.rekap-penjualan', 'icon' => 'fa-list-alt', 'label' => 'Rekap Penjualan'],
                ['route' => 'laporan.rekap-retur', 'icon' => 'fa-history', 'label' => 'Rekap Retur'],
                ['route' => 'laporan.rekap-ar', 'icon' => 'fa-search-dollar', 'label' => 'Rekap Piutang'],
                ['route' => 'laporan.rekap-collection', 'icon' => 'fa-hand-holding-usd', 'label' => 'Rekap Tagihan'],
                ] as $menu)
                <a href="{{ route($menu['route']) }}"
                    class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-[11px] font-bold relative uppercase tracking-wider
                    {{ request()->routeIs($menu['route']) ? 'bg-amber-500 text-white shadow-lg shadow-amber-600/20' : 'dark:text-neutral-500 text-slate-500 dark:hover:bg-neutral-800 hover:bg-amber-50 hover:text-amber-600' }}"
                    :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
                    <span class="w-5 flex justify-center"><i
                            class="fas {{ $menu['icon'] }} {{ request()->routeIs($menu['route']) ? '' : 'group-hover:text-amber-500' }}"></i></span>
                    <span x-show="isSidebarExpanded" class="ml-3 truncate">{{ $menu['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- FOOTER ACTION --}}
    <div class="p-4 border-t dark:border-neutral-800 border-slate-100 bg-slate-50/50 dark:bg-[#080808]">
        <button @click="toggleSidebar()" class="flex w-full items-center justify-center p-3 rounded-2xl transition-all border
            dark:bg-neutral-900 dark:text-neutral-500 dark:hover:text-indigo-400 dark:border-neutral-800
            bg-white text-slate-400 hover:text-indigo-600 border-slate-200 shadow-sm">
            <i class="fas fa-chevron-left transition-transform duration-500"
                :class="!isSidebarExpanded ? 'rotate-180' : ''"></i>
            <span x-show="isSidebarExpanded" class="ml-3 text-[10px] font-black uppercase tracking-[0.2em]">Minimize
                Menu</span>
        </button>
    </div>
</aside>