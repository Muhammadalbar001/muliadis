<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta" x-data="{ activeTab: 'overview' }"
    x-init="$watch('activeTab', value => {
         if (value === 'ranking' || value === 'salesman') {
             setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 200);
         }
     })">

    {{-- HEADER & NAVIGATION --}}
    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col md:flex-row items-center gap-8 w-full lg:w-auto">
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Executive <span class="text-blue-500">Dashboard</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 mt-1 dark:text-slate-400 text-slate-500">
                        Mulia Distribution System</p>
                </div>

                <div
                    class="flex p-1 rounded-2xl border transition-all dark:bg-neutral-900/50 dark:border-white/5 bg-slate-100 border-slate-200">
                    <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'dark:bg-blue-600 bg-white dark:text-white text-blue-600 shadow-lg' : 'text-slate-500 hover:text-blue-400'"
                        class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-chart-pie text-xs"></i> Overview
                    </button>
                    <button @click="activeTab = 'ranking'"
                        :class="activeTab === 'ranking' ? 'dark:bg-blue-600 bg-white dark:text-white text-blue-600 shadow-lg' : 'text-slate-500 hover:text-blue-400'"
                        class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-trophy text-xs"></i> Ranking
                    </button>
                    <button @click="activeTab = 'salesman'"
                        :class="activeTab === 'salesman' ? 'dark:bg-blue-600 bg-white dark:text-white text-blue-600 shadow-lg' : 'text-slate-500 hover:text-blue-400'"
                        class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-user-tie text-xs"></i> Sales
                    </button>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full lg:w-auto justify-end font-jakarta">
                <div
                    class="flex items-center gap-2 border rounded-xl px-3 py-1.5 transition-all dark:bg-neutral-900 dark:border-white/10 bg-white border-slate-200 shadow-sm">
                    <input type="date" wire:model.live="startDate"
                        class="bg-transparent border-none text-[11px] font-black uppercase tracking-widest focus:ring-0 p-0 text-blue-500 cursor-pointer">
                    <span class="opacity-30">/</span>
                    <input type="date" wire:model.live="endDate"
                        class="bg-transparent border-none text-[11px] font-black uppercase tracking-widest focus:ring-0 p-0 text-blue-500 cursor-pointer">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between border px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm h-[38px]
                        dark:bg-neutral-900 dark:border-white/10 dark:text-slate-300 bg-white border-slate-200 text-slate-700">
                        <span
                            class="truncate">{{ empty($filterCabang) ? 'Regional' : count($filterCabang).' Selected' }}</span>
                        <i class="fas fa-chevron-down opacity-40 transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute z-50 mt-2 w-full border rounded-xl shadow-2xl p-2 max-h-48 overflow-y-auto right-0 bg-white border-slate-200 dark:bg-slate-900 dark:border-slate-800">
                        @foreach($optCabang as $cab)
                        <label
                            class="flex items-center px-2 py-2 hover:bg-blue-500/10 rounded-lg cursor-pointer transition-colors group">
                            <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 h-3 w-3">
                            <span
                                class="ml-3 text-[10px] font-bold uppercase tracking-tight group-hover:text-blue-400 dark:text-slate-400 text-slate-600">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button wire:click="applyFilter"
                    class="px-4 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all h-[38px]">
                    <i class="fas fa-sync-alt mr-1"></i> Terapkan
                </button>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none"
        class="transition-opacity duration-300 px-4 sm:px-6 lg:px-8">

        {{-- 1. OVERVIEW TAB (REDESIGNED & VERTICAL STACK) --}}
        <div x-show="activeTab === 'overview'" x-transition.opacity class="space-y-8">

            {{-- KPI CARDS (COLORFUL GRADIENTS) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- A. Gross Revenue (BLUE THEME) --}}
                <div
                    class="relative p-6 rounded-[2.5rem] border transition-all duration-500 group overflow-hidden bg-gradient-to-br from-blue-500 to-indigo-600 shadow-xl shadow-blue-500/20 border-white/10">
                    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                        <i class="fas fa-chart-line text-8xl text-white transform rotate-12"></i>
                    </div>
                    <div class="relative z-10 text-white">
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/20">
                                <i class="fas fa-wallet text-xs"></i>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Gross Revenue</p>
                        </div>
                        <h3 class="text-3xl font-black tracking-tighter drop-shadow-sm">Rp
                            {{ $this->formatCompact($salesSum) }}</h3>
                        <div class="mt-4 flex items-center gap-2">
                            <span
                                class="px-2 py-1 rounded-md bg-white/20 text-[9px] font-bold backdrop-blur-sm border border-white/10">
                                <i class="fas fa-check-circle mr-1"></i> Real
                            </span>
                            <p class="text-[10px] font-mono opacity-60">{{ number_format($salesSum, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- B. Total Return (ROSE/RED THEME) --}}
                <div
                    class="relative p-6 rounded-[2.5rem] border transition-all duration-500 group overflow-hidden bg-gradient-to-br from-rose-500 to-pink-600 shadow-xl shadow-rose-500/20 border-white/10">
                    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                        <i class="fas fa-undo-alt text-8xl text-white transform -rotate-12"></i>
                    </div>
                    <div class="relative z-10 text-white">
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/20">
                                <i class="fas fa-exchange-alt text-xs"></i>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Total Return</p>
                        </div>
                        <h3 class="text-3xl font-black tracking-tighter drop-shadow-sm">Rp
                            {{ $this->formatCompact($returSum) }}</h3>
                        <div class="mt-4 flex items-center gap-2">
                            <span
                                class="px-2 py-1 rounded-md bg-white/20 text-[9px] font-bold backdrop-blur-sm border border-white/10">
                                Ratio: {{ number_format($persenRetur, 2) }}%
                            </span>
                        </div>
                    </div>
                </div>

                {{-- C. Outlet Active (PURPLE/VIOLET THEME) --}}
                <div
                    class="relative p-6 rounded-[2.5rem] border transition-all duration-500 group overflow-hidden bg-gradient-to-br from-purple-500 to-violet-600 shadow-xl shadow-purple-500/20 border-white/10">
                    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                        <i class="fas fa-store text-8xl text-white"></i>
                    </div>
                    <div class="relative z-10 text-white">
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/20">
                                <i class="fas fa-shop text-xs"></i>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Outlet Active</p>
                        </div>
                        <h3 class="text-3xl font-black tracking-tighter drop-shadow-sm">
                            {{ number_format($totalOa) }} <span class="text-sm font-bold opacity-60">Toko</span>
                        </h3>
                        <div class="mt-4 w-full h-1 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-white/50 w-3/4 rounded-full"></div>
                        </div>
                    </div>
                </div>

                {{-- D. Effective Call (EMERALD/TEAL THEME) --}}
                <div
                    class="relative p-6 rounded-[2.5rem] border transition-all duration-500 group overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600 shadow-xl shadow-emerald-500/20 border-white/10">
                    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                        <i class="fas fa-file-invoice-dollar text-8xl text-white transform rotate-6"></i>
                    </div>
                    <div class="relative z-10 text-white">
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/20">
                                <i class="fas fa-check-double text-xs"></i>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Effective Call</p>
                        </div>
                        <h3 class="text-3xl font-black tracking-tighter drop-shadow-sm">
                            {{ number_format($totalEc) }} <span class="text-sm font-bold opacity-60">Nota</span>
                        </h3>
                        <div class="mt-4 w-full h-1 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-white/50 w-2/3 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DAILY TREND CHARTS (MODIFIKASI: GRID-COLS-1 AGAR KE BAWAH) --}}
            <div class="grid grid-cols-1 gap-8" wire:ignore>

                {{-- Chart 1: Sales Trend (BLUE THEME HEADER) --}}
                <div
                    class="rounded-[3rem] border overflow-hidden transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-blue-100 shadow-sm">
                    <div
                        class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-6 border-b border-blue-100 dark:border-white/5 flex justify-between items-center">
                        <h4
                            class="font-black text-xs uppercase tracking-[0.2em] flex items-center gap-3 dark:text-blue-300 text-blue-900">
                            <span
                                class="w-8 h-8 rounded-xl bg-blue-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                                <i class="fas fa-chart-area"></i>
                            </span>
                            Daily Sales Trend
                        </h4>
                        <div
                            class="px-4 py-2 rounded-xl bg-white dark:bg-white/10 border border-blue-100 dark:border-white/5 text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider shadow-sm">
                            Last 30 Days
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="chart-sales-retur" style="min-height: 350px;"></div>
                    </div>
                </div>

                {{-- Chart 2: AR vs Collection (ORANGE THEME HEADER) --}}
                <div
                    class="rounded-[3rem] border overflow-hidden transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-orange-100 shadow-sm">
                    <div
                        class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 p-6 border-b border-orange-100 dark:border-white/5 flex justify-between items-center">
                        <h4
                            class="font-black text-xs uppercase tracking-[0.2em] flex items-center gap-3 dark:text-orange-300 text-orange-900">
                            <span
                                class="w-8 h-8 rounded-xl bg-orange-500 flex items-center justify-center text-white shadow-lg shadow-orange-500/30">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </span>
                            Tagihan vs Pembayaran
                        </h4>
                        <div
                            class="px-4 py-2 rounded-xl bg-white dark:bg-white/10 border border-orange-100 dark:border-white/5 text-[10px] font-black text-orange-600 dark:text-orange-400 uppercase tracking-wider shadow-sm">
                            Finance Flow
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="chart-ar-coll" style="min-height: 350px;"></div>
                    </div>
                </div>

            </div>
        </div>

        {{-- 2. RANKING TAB (GRID 1 COLUMN AGAR TERSUSUN KE BAWAH) --}}
        <div x-show="activeTab === 'ranking'" x-transition.opacity class="grid grid-cols-1 gap-8" wire:ignore>

            {{-- A. TOP PRODUK (BLUE) --}}
            <div
                class="rounded-[2.5rem] border overflow-hidden transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-blue-100 shadow-sm">
                <div
                    class="bg-gradient-to-r from-blue-500 to-cyan-500 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white shadow-inner">
                            <i class="fas fa-box text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-sm uppercase tracking-wider text-white">Top Products (Qty)</h4>
                            <p class="text-[10px] text-blue-100 font-bold uppercase tracking-widest">Ranking by Quantity
                            </p>
                        </div>
                    </div>

                    {{-- Multi Select: Supplier --}}
                    <div class="relative w-full md:w-72" x-data="{ 
                            open: false, 
                            search: '', 
                            selected: @entangle('filterSupplierTopProduk').live,
                            items: {{ json_encode($optSupplierList) }}
                         }">
                        <button @click="open = !open" @click.outside="open = false" type="button"
                            class="w-full pl-4 pr-10 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider border-none bg-white text-blue-600 shadow-lg flex items-center justify-between transition-transform active:scale-95">
                            <span
                                x-text="selected.length > 0 ? selected.length + ' Supplier Dipilih' : 'Filter Supplier...'"></span>
                            <i class="fas fa-chevron-down text-xs opacity-50"></i>
                        </button>

                        <div x-show="open" x-transition
                            class="absolute z-50 mt-2 w-full bg-white dark:bg-slate-900 rounded-xl shadow-2xl p-2 border border-slate-100 dark:border-white/10 text-slate-800 dark:text-slate-200">
                            <div class="p-2 border-b border-slate-100 dark:border-white/5">
                                <input x-model="search" type="text" placeholder="Cari..."
                                    class="w-full px-3 py-1.5 rounded-lg text-xs bg-slate-50 dark:bg-white/5 border-none focus:ring-2 focus:ring-blue-500 uppercase font-bold">
                            </div>
                            <div class="max-h-48 overflow-y-auto p-1 custom-scrollbar">
                                <template
                                    x-for="item in items.filter(i => i.toLowerCase().includes(search.toLowerCase()))"
                                    :key="item">
                                    <label
                                        class="flex items-center gap-3 px-2 py-2 hover:bg-blue-50 dark:hover:bg-white/10 rounded-lg cursor-pointer">
                                        <input type="checkbox" :value="item" x-model="selected"
                                            class="rounded border-slate-300 text-blue-500 focus:ring-blue-500 w-3.5 h-3.5">
                                        <span class="text-[10px] font-bold uppercase" x-text="item"></span>
                                    </label>
                                </template>
                            </div>
                            <div class="p-2 border-t border-slate-100 dark:border-white/5 text-center">
                                <button @click="selected = []"
                                    class="text-[9px] text-red-500 font-bold hover:underline">Reset Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div id="chart-top-produk" style="min-height: 400px;"></div>
                </div>
            </div>

            {{-- B. TOP CUSTOMER (PURPLE) --}}
            <div
                class="rounded-[2.5rem] border overflow-hidden transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-purple-100 shadow-sm">
                <div
                    class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white shadow-inner">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-sm uppercase tracking-wider text-white">Top Customers</h4>
                            <p class="text-[10px] text-purple-100 font-bold uppercase tracking-widest">By Revenue</p>
                        </div>
                    </div>

                    {{-- Multi Select: Salesman --}}
                    <div class="relative w-full md:w-72" x-data="{ 
                            open: false, 
                            search: '', 
                            selected: @entangle('filterSalesTopCust').live,
                            items: {{ json_encode($optSales) }}
                         }">
                        <button @click="open = !open" @click.outside="open = false" type="button"
                            class="w-full pl-4 pr-10 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider border-none bg-white text-purple-600 shadow-lg flex items-center justify-between transition-transform active:scale-95">
                            <span
                                x-text="selected.length > 0 ? selected.length + ' Sales Dipilih' : 'Filter Salesman...'"></span>
                            <i class="fas fa-chevron-down text-xs opacity-50"></i>
                        </button>

                        <div x-show="open" x-transition
                            class="absolute z-50 mt-2 w-full bg-white dark:bg-slate-900 rounded-xl shadow-2xl p-2 border border-slate-100 dark:border-white/10 text-slate-800 dark:text-slate-200">
                            <div class="p-2 border-b border-slate-100 dark:border-white/5">
                                <input x-model="search" type="text" placeholder="Cari..."
                                    class="w-full px-3 py-1.5 rounded-lg text-xs bg-slate-50 dark:bg-white/5 border-none focus:ring-2 focus:ring-purple-500 uppercase font-bold">
                            </div>
                            <div class="max-h-48 overflow-y-auto p-1 custom-scrollbar">
                                <template
                                    x-for="item in items.filter(i => i.toLowerCase().includes(search.toLowerCase()))"
                                    :key="item">
                                    <label
                                        class="flex items-center gap-3 px-2 py-2 hover:bg-purple-50 dark:hover:bg-white/10 rounded-lg cursor-pointer">
                                        <input type="checkbox" :value="item" x-model="selected"
                                            class="rounded border-slate-300 text-purple-500 focus:ring-purple-500 w-3.5 h-3.5">
                                        <span class="text-[10px] font-bold uppercase truncate" x-text="item"></span>
                                    </label>
                                </template>
                            </div>
                            <div class="p-2 border-t border-slate-100 dark:border-white/5 text-center">
                                <button @click="selected = []"
                                    class="text-[9px] text-red-500 font-bold hover:underline">Reset Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div id="chart-top-customer" style="min-height: 400px;"></div>
                </div>
            </div>

            {{-- C. TOP SUPPLIER (PINK) --}}
            <div
                class="rounded-[2.5rem] border overflow-hidden transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-pink-100 shadow-sm">
                <div
                    class="bg-gradient-to-r from-pink-500 to-rose-500 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white shadow-inner">
                            <i class="fas fa-truck text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-sm uppercase tracking-wider text-white">Top Suppliers</h4>
                            <p class="text-[10px] text-pink-100 font-bold uppercase tracking-widest">By Revenue</p>
                        </div>
                    </div>

                    {{-- Multi Select: Kategori --}}
                    <div class="relative w-full md:w-72" x-data="{ 
                            open: false, 
                            search: '', 
                            selected: @entangle('filterKategoriTopSupp').live,
                            items: {{ json_encode($optKategoriList) }}
                         }">
                        <button @click="open = !open" @click.outside="open = false" type="button"
                            class="w-full pl-4 pr-10 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider border-none bg-white text-pink-600 shadow-lg flex items-center justify-between transition-transform active:scale-95">
                            <span
                                x-text="selected.length > 0 ? selected.length + ' Kategori' : 'Filter Kategori...'"></span>
                            <i class="fas fa-chevron-down text-xs opacity-50"></i>
                        </button>

                        <div x-show="open" x-transition
                            class="absolute z-50 mt-2 w-full bg-white dark:bg-slate-900 rounded-xl shadow-2xl p-2 border border-slate-100 dark:border-white/10 text-slate-800 dark:text-slate-200">
                            <div class="p-2 border-b border-slate-100 dark:border-white/5">
                                <input x-model="search" type="text" placeholder="Cari..."
                                    class="w-full px-3 py-1.5 rounded-lg text-xs bg-slate-50 dark:bg-white/5 border-none focus:ring-2 focus:ring-pink-500 uppercase font-bold">
                            </div>
                            <div class="max-h-48 overflow-y-auto p-1 custom-scrollbar">
                                <template
                                    x-for="item in items.filter(i => i.toLowerCase().includes(search.toLowerCase()))"
                                    :key="item">
                                    <label
                                        class="flex items-center gap-3 px-2 py-2 hover:bg-pink-50 dark:hover:bg-white/10 rounded-lg cursor-pointer">
                                        <input type="checkbox" :value="item" x-model="selected"
                                            class="rounded border-slate-300 text-pink-500 focus:ring-pink-500 w-3.5 h-3.5">
                                        <span class="text-[10px] font-bold uppercase truncate" x-text="item"></span>
                                    </label>
                                </template>
                            </div>
                            <div class="p-2 border-t border-slate-100 dark:border-white/5 text-center">
                                <button @click="selected = []"
                                    class="text-[9px] text-red-500 font-bold hover:underline">Reset Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div id="chart-top-supplier" style="min-height: 400px;"></div>
                </div>
            </div>

        </div>

        {{-- 3. SALESMAN TAB --}}
        <div x-show="activeTab === 'salesman'" x-transition.opacity class="max-w-5xl mx-auto" wire:ignore>
            <div
                class="p-8 rounded-[3rem] border transition-all dark:bg-neutral-900/40 dark:border-white/5 bg-white border-slate-100 shadow-sm">
                <h4
                    class="font-black text-xs uppercase tracking-[0.2em] mb-10 flex items-center gap-3 dark:text-blue-300 text-indigo-900">
                    <i class="fas fa-bullseye text-lg"></i> Top 10 Sales Performance
                </h4>
                <div id="chart-sales-perf" style="min-height: 500px;"></div>
            </div>
        </div>

    </div>
</div>

{{-- JAVASCRIPT CHARTS --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {
    let charts = {};
    const initData = @json($chartData);

    const getThemeConfig = () => {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            text: isDark ? '#94a3b8' : '#64748b',
            grid: isDark ? 'rgba(255, 255, 255, 0.03)' : 'rgba(0, 0, 0, 0.03)',
            tooltip: isDark ? 'dark' : 'light',
            font: "'Plus Jakarta Sans', sans-serif"
        };
    };

    const renderCharts = (data) => {
        const config = getThemeConfig();
        const fmtRp = (v) => "Rp " + new Intl.NumberFormat('id-ID').format(v);
        const fmtJt = (v) => (v / 1000000).toFixed(1) + " Jt";

        const baseOptions = {
            chart: {
                fontFamily: config.font,
                foreColor: config.text,
                toolbar: {
                    show: false
                },
                background: 'transparent'
            },
            grid: {
                borderColor: config.grid
            },
            theme: {
                mode: config.tooltip
            },
            dataLabels: {
                enabled: false
            },
        };

        // 1. Sales vs Retur
        if (charts.sr) charts.sr.destroy();
        charts.sr = new ApexCharts(document.querySelector("#chart-sales-retur"), {
            ...baseOptions,
            series: [{
                name: 'Penjualan',
                data: data.trend_sales
            }, {
                name: 'Retur',
                data: data.trend_retur
            }],
            chart: {
                ...baseOptions.chart,
                type: 'area',
                height: 350
            },
            colors: ['#3b82f6', '#f43f5e'],
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.3,
                    opacityTo: 0.01
                }
            },
            xaxis: {
                categories: data.dates,
                type: 'datetime',
                labels: {
                    style: {
                        fontSize: '10px',
                        fontWeight: 600
                    },
                    format: 'dd MMM'
                }
            },
            yaxis: {
                labels: {
                    formatter: fmtJt
                }
            },
            tooltip: {
                x: {
                    format: 'dd MMMM yyyy'
                },
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.sr.render();

        // 2. AR vs Collection
        if (charts.ac) charts.ac.destroy();
        charts.ac = new ApexCharts(document.querySelector("#chart-ar-coll"), {
            ...baseOptions,
            series: [{
                name: 'Piutang',
                data: data.trend_ar
            }, {
                name: 'Pelunasan',
                data: data.trend_coll
            }],
            chart: {
                ...baseOptions.chart,
                type: 'bar',
                height: 350
            },
            colors: ['#f97316', '#10b981'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '50%'
                }
            },
            xaxis: {
                categories: data.dates,
                type: 'datetime',
                labels: {
                    style: {
                        fontSize: '10px',
                        fontWeight: 600
                    },
                    format: 'dd/MM'
                }
            },
            yaxis: {
                labels: {
                    formatter: fmtJt
                }
            },
            tooltip: {
                x: {
                    format: 'dd MMMM yyyy'
                },
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.ac.render();

        // Ranking Helper
        const rankingOpts = (id, seriesName, seriesData, categories, color) => ({
            ...baseOptions,
            series: [{
                name: seriesName,
                data: seriesData
            }],
            chart: {
                ...baseOptions.chart,
                type: 'bar',
                height: 400
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 6,
                    barHeight: '60%'
                }
            },
            colors: [color],
            xaxis: {
                categories: categories
            },
            dataLabels: {
                enabled: true,
                formatter: (v) => seriesName === 'Qty' ? v : fmtJt(v)
            },
            tooltip: {
                y: {
                    formatter: (v) => seriesName === 'Qty' ? v + ' Unit' : fmtRp(v)
                }
            }
        });

        // 3. Top Produk
        if (charts.tp) charts.tp.destroy();
        charts.tp = new ApexCharts(document.querySelector("#chart-top-produk"), rankingOpts(
            "#chart-top-produk", 'Qty', data.top_produk_val, data.top_produk_lbl, '#3b82f6'));
        charts.tp.render();

        // 4. Top Customer
        if (charts.tc) charts.tc.destroy();
        charts.tc = new ApexCharts(document.querySelector("#chart-top-customer"), rankingOpts(
            "#chart-top-customer", 'Omzet', data.top_cust_val, data.top_cust_lbl, '#8b5cf6'));
        charts.tc.render();

        // 5. Top Supplier
        if (charts.ts) charts.ts.destroy();
        charts.ts = new ApexCharts(document.querySelector("#chart-top-supplier"), rankingOpts(
            "#chart-top-supplier", 'Omzet', data.top_supp_val, data.top_supp_lbl, '#ec4899'));
        charts.ts.render();

        // 6. Sales Performance
        if (charts.sp) charts.sp.destroy();
        charts.sp = new ApexCharts(document.querySelector("#chart-sales-perf"), {
            ...baseOptions,
            series: [{
                name: 'Realisasi',
                data: data.sales_real
            }, {
                name: 'Target',
                data: data.sales_target
            }],
            chart: {
                ...baseOptions.chart,
                type: 'bar',
                height: 500
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '60%'
                }
            },
            colors: ['#3b82f6', '#f59e0b'], // Biru & Kuning
            xaxis: {
                categories: data.sales_names
            },
            yaxis: {
                labels: {
                    formatter: fmtJt
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.sp.render();
    };

    if (initData) renderCharts(initData);

    Livewire.on('update-charts', (event) => {
        const newData = event.data || (event[0] && event[0].data) || event;
        if (newData) renderCharts(newData);
    });

    const observer = new MutationObserver(() => {
        if (initData) renderCharts(initData);
    });
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap');

.font-jakarta {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

* {
    transition-property: background-color, border-color, color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

::-webkit-scrollbar {
    width: 4px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: #3b82f6;
    border-radius: 10px;
}
</style>