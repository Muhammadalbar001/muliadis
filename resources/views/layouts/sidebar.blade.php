<aside
    class="fixed inset-y-0 left-0 z-50 bg-slate-900 border-r border-slate-800 text-white transition-all duration-300 flex flex-col"
    :class="{ 
        'translate-x-0': sidebarOpen, 
        '-translate-x-full': !sidebarOpen, 
        'lg:translate-x-0': true,
        'w-64': isSidebarExpanded,
        'w-20': !isSidebarExpanded
    }" x-cloak>

    <div class="h-20 flex-none flex items-center px-6 border-b border-slate-800/50 bg-slate-900/50 backdrop-blur-xl relative"
        :class="isSidebarExpanded ? 'justify-between' : 'justify-center'">

        <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
            <div
                class="w-9 h-9 rounded-xl flex-none flex items-center justify-center text-white shadow-lg shadow-indigo-500/20 bg-gradient-to-br from-indigo-500 to-purple-600">
                <i class="fas fa-cube text-sm"></i>
            </div>
            <div x-show="isSidebarExpanded" x-transition.opacity.duration.200ms>
                <h1 class="font-bold text-lg tracking-tight text-white leading-none">Muliadis</h1>
                <p class="text-[9px] font-medium text-slate-400 tracking-wider uppercase mt-0.5">App System</p>
            </div>
        </div>

        <button @click="sidebarOpen = false"
            class="lg:hidden text-slate-400 hover:text-white transition-colors p-1 focus:outline-none absolute right-4">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto py-6 space-y-1 custom-scrollbar overflow-x-hidden"
        :class="isSidebarExpanded ? 'px-3' : 'px-2'">

        <div x-show="isSidebarExpanded"
            class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 transition-opacity">Main
        </div>
        <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-slate-800 mb-2 mt-2"></div>

        <a href="{{ route('dashboard') }}"
            class="flex items-center py-3 rounded-xl transition-all duration-200 group text-sm font-medium mb-4 relative
            {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
            :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
            <i
                class="fas fa-chart-pie w-5 text-center text-lg {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
            <span x-show="isSidebarExpanded" class="ml-3 truncate">Dashboard</span>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap border border-slate-700 shadow-xl ml-2">
                Dashboard
            </div>
        </a>

        <div x-show="isSidebarExpanded"
            class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6 transition-opacity">
            Master Data</div>
        <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-slate-800 mb-2 mt-4"></div>

        @foreach([
        ['route' => 'master.sales', 'icon' => 'fa-user-tie', 'label' => 'Salesman', 'color' => 'blue'],
        ['route' => 'master.produk', 'icon' => 'fa-box', 'label' => 'Produk', 'color' => 'indigo'],
        ['route' => 'master.supplier', 'icon' => 'fa-truck', 'label' => 'Supplier', 'color' => 'pink'],
        ['route' => 'master.user', 'icon' => 'fa-users-cog', 'label' => 'User', 'color' => 'slate'],
        ] as $menu)
        <a href="{{ route($menu['route']) }}"
            class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium mb-1 relative
            {{ request()->routeIs($menu['route']) ? 'bg-'.$menu['color'].'-500/10 text-'.$menu['color'].'-400 border border-'.$menu['color'].'-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white border border-transparent' }}"
            :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
            <span class="w-5 flex justify-center"><i
                    class="fas {{ $menu['icon'] }} {{ request()->routeIs($menu['route']) ? 'text-'.$menu['color'].'-400' : 'text-slate-500 group-hover:text-white' }}"></i></span>
            <span x-show="isSidebarExpanded" class="ml-3 truncate">{{ $menu['label'] }}</span>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap border border-slate-700 shadow-xl ml-2">
                {{ $menu['label'] }}
            </div>
        </a>
        @endforeach

        <div x-show="isSidebarExpanded"
            class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6 transition-opacity">
            Transaksi</div>
        <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-slate-800 mb-2 mt-4"></div>

        @foreach([
        ['route' => 'transaksi.penjualan', 'icon' => 'fa-shopping-cart', 'label' => 'Penjualan', 'color' => 'emerald'],
        ['route' => 'transaksi.retur', 'icon' => 'fa-undo', 'label' => 'Retur', 'color' => 'rose'],
        ['route' => 'transaksi.ar', 'icon' => 'fa-file-invoice-dollar', 'label' => 'Piutang (AR)', 'color' => 'orange'],
        ['route' => 'transaksi.collection', 'icon' => 'fa-hand-holding-dollar', 'label' => 'Collection', 'color' =>
        'cyan'],
        ] as $menu)
        <a href="{{ route($menu['route']) }}"
            class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium mb-1 relative
            {{ request()->routeIs($menu['route']) ? 'bg-'.$menu['color'].'-500/10 text-'.$menu['color'].'-400 border border-'.$menu['color'].'-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white border border-transparent' }}"
            :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
            <span class="w-5 flex justify-center"><i
                    class="fas {{ $menu['icon'] }} {{ request()->routeIs($menu['route']) ? 'text-'.$menu['color'].'-400' : 'text-slate-500 group-hover:text-white' }}"></i></span>
            <span x-show="isSidebarExpanded" class="ml-3 truncate">{{ $menu['label'] }}</span>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap border border-slate-700 shadow-xl ml-2">
                {{ $menu['label'] }}
            </div>
        </a>
        @endforeach

        <div x-show="isSidebarExpanded"
            class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-6 transition-opacity">
            Laporan</div>
        <div x-show="!isSidebarExpanded" class="h-px w-8 mx-auto bg-slate-800 mb-2 mt-4"></div>

        <a href="{{ route('laporan.kinerja-sales') }}"
            class="flex items-center py-2.5 rounded-xl transition-all duration-200 group text-sm font-bold border border-yellow-500/20 mb-1 relative
            {{ request()->routeIs('laporan.kinerja-sales') ? 'bg-yellow-500/10 text-yellow-400' : 'bg-slate-800/50 text-slate-300 hover:bg-slate-800 hover:text-white' }}"
            :class="isSidebarExpanded ? 'px-4' : 'justify-center'">
            <span class="w-5 flex justify-center"><i
                    class="fas fa-trophy {{ request()->routeIs('laporan.kinerja-sales') ? 'text-yellow-400' : 'text-yellow-600' }}"></i></span>
            <span x-show="isSidebarExpanded" class="ml-3 truncate">Kinerja Sales</span>

            <div x-show="!isSidebarExpanded"
                class="absolute left-14 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap border border-slate-700 shadow-xl ml-2">
                Kinerja Sales
            </div>
        </a>

        <div x-data="{ open: {{ request()->routeIs('laporan.rekap*') ? 'true' : 'false' }} }">
            <button @click="isSidebarExpanded ? open = !open : toggleSidebar()"
                class="flex items-center w-full py-2.5 rounded-xl transition-all duration-200 group text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white relative"
                :class="isSidebarExpanded ? 'px-4 justify-between' : 'justify-center'">
                <div class="flex items-center">
                    <span class="w-5 flex justify-center"><i
                            class="fas fa-folder-open text-slate-500 group-hover:text-white"></i></span>
                    <span x-show="isSidebarExpanded" class="ml-3">Data Rekap</span>
                </div>
                <i x-show="isSidebarExpanded" class="fas fa-chevron-down text-[10px] transition-transform duration-200"
                    :class="{'rotate-180': open}"></i>

                <div x-show="!isSidebarExpanded"
                    class="absolute left-14 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap border border-slate-700 shadow-xl ml-2">
                    Data Rekap (Expand)
                </div>
            </button>

            <div x-show="open && isSidebarExpanded" x-transition x-cloak
                class="space-y-1 mt-1 px-2 border-l border-slate-700 ml-6">
                @foreach([
                ['route' => 'laporan.rekap-penjualan', 'label' => 'Penjualan', 'color' => 'emerald'],
                ['route' => 'laporan.rekap-retur', 'label' => 'Retur', 'color' => 'rose'],
                ['route' => 'laporan.rekap-ar', 'label' => 'Piutang', 'color' => 'orange'],
                ['route' => 'laporan.rekap-collection', 'label' => 'Collection', 'color' => 'cyan'],
                ] as $sub)
                <a href="{{ route($sub['route']) }}"
                    class="flex items-center px-4 py-2 rounded-lg text-xs font-medium transition-all {{ request()->routeIs($sub['route']) ? 'text-'.$sub['color'].'-400 bg-'.$sub['color'].'-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <div
                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs($sub['route']) ? 'bg-'.$sub['color'].'-400' : 'bg-slate-600' }}">
                    </div>
                    {{ $sub['label'] }}
                </a>
                @endforeach
            </div>
        </div>

    </div>

    <div class="p-4 border-t border-slate-800 bg-slate-950 flex-none">

        <button @click="toggleSidebar()"
            class="hidden lg:flex w-full items-center justify-center p-2 mb-4 rounded-lg bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 transition-all group">
            <i class="fas fa-chevron-left transition-transform duration-300"
                :class="!isSidebarExpanded ? 'rotate-180' : ''"></i>
            <span x-show="isSidebarExpanded" class="ml-2 text-xs font-bold uppercase tracking-wider">Collapse</span>
        </button>

        <div class="flex items-center gap-3 transition-all duration-300"
            :class="isSidebarExpanded ? 'justify-between' : 'justify-center flex-col gap-4'">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 min-w-0 group cursor-pointer">
                <div class="relative flex-none">
                    <div
                        class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-white border-2 border-slate-600 group-hover:border-indigo-500 transition-colors">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div
                        class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-slate-900 rounded-full animate-pulse">
                    </div>
                </div>
                <div x-show="isSidebarExpanded" class="min-w-0">
                    <p
                        class="text-sm font-medium text-white truncate w-24 group-hover:text-indigo-400 transition-colors">
                        {{ Auth::user()->name ?? 'User' }}
                    </p>
                    <p class="text-[10px] text-slate-500 truncate group-hover:text-slate-400">
                        {{ ucfirst(Auth::user()->role ?? 'User') }}</p>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-900/20 border border-transparent rounded-xl transition-all"
                    title="Logout">
                    <i class="fas fa-power-off text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</aside>