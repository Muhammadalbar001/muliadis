<div class="min-h-screen space-y-12 pb-20 font-jakarta transition-colors duration-300 bg-slate-50 dark:bg-[#0a0a0a]">

    {{-- HEADER UTAMA --}}
    <div
        class="sticky top-0 z-40 pt-6 pb-4 px-6 border-b shadow-sm transition-colors duration-300 bg-white/95 backdrop-blur-md border-slate-200 dark:bg-[#121212]/95 dark:border-white/5">
        <div class="max-w-8xl mx-auto flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-black tracking-tighter uppercase leading-none text-slate-800 dark:text-white">
                    Profit & Loss Analysis
                </h1>
                <p class="text-[10px] font-bold tracking-[0.3em] uppercase mt-1 text-slate-400 dark:text-slate-500">
                    Multi-Branch Independent Control
                </p>
            </div>

            <div class="hidden md:block">
                <span class="text-[10px] uppercase font-bold text-slate-400">Mode Urutan:</span>
                <span class="text-xs font-black text-emerald-500 uppercase">
                    {{ $sortDirection === 'desc' ? 'Margin Tertinggi' : 'Margin Terendah' }}
                </span>
            </div>
        </div>
    </div>

    <div class="px-6 max-w-8xl mx-auto space-y-16">

        @foreach($dataPerCabang as $cabang => $data)

        {{-- SETUP TEMA WARNA DINAMIS (DENGAN PERBAIKAN DARK MODE) --}}
        @php
        $themes = [
        // Tema 1: Biru Laut (Ocean)
        [
        'gradient' => 'from-blue-600 via-blue-500 to-cyan-500',
        'bg_soft' => 'bg-blue-50/50 dark:bg-blue-500/10', // Mode Gelap: Background transparan
        'border' => 'border-blue-200 dark:border-blue-500/20', // Mode Gelap: Border redup
        'text' => 'text-blue-600 dark:text-blue-400', // Mode Gelap: Teks lebih terang
        'ring' => 'focus:ring-blue-500',
        'icon' => 'bg-blue-500',
        'btn' => 'hover:shadow-blue-500/30'
        ],
        // Tema 2: Emerald (Nature)
        [
        'gradient' => 'from-emerald-600 via-emerald-500 to-teal-500',
        'bg_soft' => 'bg-emerald-50/50 dark:bg-emerald-500/10',
        'border' => 'border-emerald-200 dark:border-emerald-500/20',
        'text' => 'text-emerald-600 dark:text-emerald-400',
        'ring' => 'focus:ring-emerald-500',
        'icon' => 'bg-emerald-500',
        'btn' => 'hover:shadow-emerald-500/30'
        ],
        // Tema 3: Violet (Royal)
        [
        'gradient' => 'from-violet-600 via-purple-500 to-fuchsia-500',
        'bg_soft' => 'bg-violet-50/50 dark:bg-violet-500/10',
        'border' => 'border-violet-200 dark:border-violet-500/20',
        'text' => 'text-violet-600 dark:text-violet-400',
        'ring' => 'focus:ring-violet-500',
        'icon' => 'bg-violet-500',
        'btn' => 'hover:shadow-violet-500/30'
        ],
        // Tema 4: Amber (Sunset)
        [
        'gradient' => 'from-amber-500 via-orange-500 to-red-500',
        'bg_soft' => 'bg-amber-50/50 dark:bg-amber-500/10',
        'border' => 'border-amber-200 dark:border-amber-500/20',
        'text' => 'text-amber-600 dark:text-amber-400',
        'ring' => 'focus:ring-amber-500',
        'icon' => 'bg-amber-500',
        'btn' => 'hover:shadow-amber-500/30'
        ],
        // Tema 5: Rose (Passion)
        [
        'gradient' => 'from-rose-600 via-pink-500 to-red-400',
        'bg_soft' => 'bg-rose-50/50 dark:bg-rose-500/10',
        'border' => 'border-rose-200 dark:border-rose-500/20',
        'text' => 'text-rose-600 dark:text-rose-400',
        'ring' => 'focus:ring-rose-500',
        'icon' => 'bg-rose-500',
        'btn' => 'hover:shadow-rose-500/30'
        ],
        ];

        // Pilih tema berdasarkan urutan loop (cycling)
        $theme = $themes[$loop->index % count($themes)];
        @endphp

        <div class="animate-fade-in-up" wire:key="cabang-{{ $cabang }}">

            {{-- KARTU UTAMA --}}
            <div
                class="rounded-[2.5rem] overflow-hidden border border-slate-200 dark:border-white/10 shadow-2xl bg-white dark:bg-[#121212]">

                {{-- HEADER CABANG (GRADIENT) --}}
                <div
                    class="bg-gradient-to-r {{ $theme['gradient'] }} p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">

                    {{-- Pattern Decoration --}}
                    <div class="absolute inset-0 opacity-10 pattern-dots pointer-events-none"></div>
                    <div
                        class="absolute -right-10 -top-10 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none">
                    </div>

                    <div class="relative z-10 flex items-center gap-5">
                        <div
                            class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shadow-inner">
                            <span class="text-2xl font-black text-white">{{ substr($cabang, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-white/80 uppercase tracking-[0.2em] mb-1">Branch
                                Location</p>
                            <h2
                                class="text-3xl font-black uppercase text-white tracking-tight leading-none shadow-black drop-shadow-sm">
                                {{ $cabang }}
                            </h2>
                        </div>
                    </div>

                    @if(count($data['products']) > 0)
                    <button wire:click="export('{{ $cabang }}')" wire:loading.attr="disabled"
                        class="relative z-10 flex items-center gap-3 px-6 py-3 bg-white text-slate-800 rounded-xl shadow-lg {{ $theme['btn'] }} transition-all text-xs font-black uppercase tracking-wider group hover:-translate-y-1">
                        <span
                            class="w-6 h-6 rounded-full bg-gradient-to-br {{ $theme['gradient'] }} flex items-center justify-center text-white text-[10px]">
                            <i class="fas fa-file-excel"></i>
                        </span>
                        <span>Export Data</span>
                        <span wire:loading wire:target="export('{{ $cabang }}')" class="ml-2">
                            <i class="fas fa-spinner fa-spin text-slate-400"></i>
                        </span>
                    </button>
                    @endif
                </div>

                {{-- CONTENT BODY --}}
                <div class="p-6 md:p-8 space-y-8">

                    {{-- FILTER SECTION --}}
                    <div
                        class="p-6 rounded-3xl border border-dashed {{ $theme['border'] }} {{ $theme['bg_soft'] }} space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                            {{-- LANGKAH 1: PILIH SUPPLIER --}}
                            <div class="md:col-span-4" x-data="{ 
                                    open: false, 
                                    search: '', 
                                    selected: @entangle('selectedSuppliers.' . $cabang).live,
                                    items: {{ json_encode($data['suppliers_list']) }}
                                 }">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest mb-2 block text-slate-500 dark:text-slate-400 ml-1">
                                    <i class="fas fa-truck-loading mr-1 {{ $theme['text'] }}"></i> Pilih Supplier
                                </label>

                                <div class="relative" @click.outside="open = false">
                                    <button @click="open = !open" type="button" class="w-full pl-4 pr-10 py-3 rounded-2xl text-xs font-bold border transition-all h-[48px] text-left flex items-center overflow-hidden bg-white dark:bg-[#0a0a0a] shadow-sm
                                        {{ count($selectedSuppliers[$cabang] ?? []) > 0 ? $theme['border'] . ' ring-1 ' . str_replace('focus:', '', $theme['ring']) : 'border-slate-200 dark:border-white/10' }}
                                        text-slate-700 dark:text-white">
                                        <span x-show="selected.length === 0" class="text-slate-400">-- Klik untuk Pilih
                                            --</span>
                                        <span x-show="selected.length > 0"
                                            x-text="selected.length + ' Supplier Dipilih'"
                                            class="{{ $theme['text'] }}"></span>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                            <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                                :class="{'rotate-180': open}"></i>
                                        </div>
                                    </button>

                                    {{-- DROPDOWN MENU --}}
                                    <div x-show="open" x-transition.opacity x-cloak
                                        class="absolute z-50 w-full mt-2 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl max-h-80 overflow-hidden flex flex-col ring-1 ring-black/5">
                                        <div
                                            class="p-3 border-b border-slate-100 dark:border-white/5 sticky top-0 bg-white dark:bg-[#1a1a1a]">
                                            <input x-model="search" type="text"
                                                class="w-full px-4 py-2.5 rounded-xl text-xs border bg-slate-50 border-slate-200 dark:bg-black dark:border-white/10 dark:text-white uppercase focus:border-transparent focus:ring-2 {{ $theme['ring'] }}"
                                                placeholder="CARI...">
                                        </div>
                                        <div class="overflow-y-auto p-2 space-y-1 custom-scrollbar flex-1">
                                            <template
                                                x-for="item in items.filter(i => i.toLowerCase().includes(search.toLowerCase()))"
                                                :key="item">
                                                <label
                                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer transition-colors group">
                                                    <div class="relative flex items-center">
                                                        <input type="checkbox" :value="item" x-model="selected"
                                                            class="peer h-4 w-4 cursor-pointer appearance-none rounded border border-slate-300 shadow transition-all checked:border-transparent checked:{{ $theme['icon'] }} hover:shadow-md dark:border-white/20 dark:bg-black">
                                                        <span
                                                            class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none text-[10px]">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    </div>
                                                    <span
                                                        class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase group-hover:{{ $theme['text'] }}"
                                                        x-text="item"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- LANGKAH 2: OPSI FILTER --}}
                            @if(count($selectedSuppliers[$cabang] ?? []) > 0)
                            <div class="md:col-span-8 flex items-end pb-1 gap-6 animate-fade-in-up">
                                <div>
                                    <label
                                        class="text-[9px] font-black uppercase tracking-widest mb-3 block text-slate-500 dark:text-slate-400">
                                        <i class="fas fa-filter mr-1 {{ $theme['text'] }}"></i> Opsi Filter
                                    </label>
                                    <div
                                        class="flex items-center gap-4 bg-white dark:bg-[#0a0a0a] p-1.5 rounded-2xl border border-slate-200 dark:border-white/10 w-fit">
                                        <label class="cursor-pointer relative">
                                            <input type="radio" value="all" wire:model.live="filterMode.{{ $cabang }}"
                                                class="peer sr-only">
                                            <div
                                                class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 dark:text-slate-400 transition-all peer-checked:{{ $theme['icon'] }} peer-checked:text-white peer-checked:shadow-lg">
                                                Semua
                                            </div>
                                        </label>
                                        <label class="cursor-pointer relative">
                                            <input type="radio" value="selected"
                                                wire:model.live="filterMode.{{ $cabang }}" class="peer sr-only">
                                            <div
                                                class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 dark:text-slate-400 transition-all peer-checked:{{ $theme['icon'] }} peer-checked:text-white peer-checked:shadow-lg">
                                                Per Item
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- FILTER LANJUTAN: PILIH ITEM & SEARCH --}}
                        @if(count($selectedSuppliers[$cabang] ?? []) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 pt-2 animate-fade-in-up">

                            @if(($filterMode[$cabang] ?? 'all') === 'selected')
                            <div class="md:col-span-6" x-data="{ 
                                    open: false, 
                                    search: '', 
                                    selected: @entangle('selectedProductIds.' . $cabang).live,
                                    items: {{ json_encode($data['products_list_dropdown']) }}
                                 }">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest mb-1.5 block text-slate-500 dark:text-slate-400 ml-1">Pilih
                                    Item</label>
                                <div class="relative" @click.outside="open = false">
                                    <button @click="open = !open" type="button"
                                        class="w-full pl-4 pr-10 py-3 rounded-2xl text-xs font-bold border {{ $theme['border'] }} transition-all h-[48px] text-left flex items-center overflow-hidden bg-white dark:bg-[#0a0a0a] text-slate-700 dark:text-white shadow-sm ring-1 {{ str_replace('focus:', '', $theme['ring']) }}">
                                        <span x-show="selected.length === 0" class="text-slate-400">-- Klik Item
                                            --</span>
                                        <span x-show="selected.length > 0" x-text="selected.length + ' Item'"
                                            class="{{ $theme['text'] }}"></span>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none {{ $theme['text'] }}">
                                            <i class="fas fa-chevron-down text-xs" :class="{'rotate-180': open}"></i>
                                        </div>
                                    </button>

                                    {{-- Dropdown Item --}}
                                    <div x-show="open" x-transition.opacity x-cloak
                                        class="absolute z-50 w-full mt-2 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-2xl shadow-xl max-h-80 overflow-hidden flex flex-col">
                                        <div
                                            class="p-3 border-b border-slate-100 dark:border-white/5 bg-white dark:bg-[#1a1a1a]">
                                            <input x-model="search" type="text"
                                                class="w-full px-3 py-2 rounded-lg text-xs border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-black dark:text-white uppercase focus:ring-2 {{ $theme['ring'] }}"
                                                placeholder="CARI ITEM...">
                                        </div>
                                        <div class="overflow-y-auto p-2 space-y-1 custom-scrollbar flex-1">
                                            <template
                                                x-for="item in items.filter(i => i.name_item.toLowerCase().includes(search.toLowerCase()))"
                                                :key="item.id">
                                                <label
                                                    class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer transition-colors">
                                                    <input type="checkbox" :value="item.id" x-model="selected"
                                                        class="rounded border-slate-300 dark:border-white/20 dark:bg-black {{ str_replace('bg-', 'text-', $theme['icon']) }} focus:{{ str_replace('bg-', 'ring-', $theme['icon']) }}">
                                                    <span
                                                        class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase truncate"
                                                        x-text="item.name_item"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div
                                class="{{ ($filterMode[$cabang] ?? 'all') === 'selected' ? 'md:col-span-6' : 'md:col-span-12' }}">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest mb-1.5 block text-slate-500 dark:text-slate-400 ml-1">Pencarian
                                    Tabel</label>
                                <div class="relative group">
                                    <i
                                        class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:{{ $theme['text'] }} transition-colors text-xs"></i>
                                    <input wire:model.live.debounce.300ms="search.{{ $cabang }}" type="text"
                                        class="w-full pl-10 pr-4 py-3 rounded-2xl text-xs font-bold border border-slate-200 dark:border-white/10 transition-all h-[48px] uppercase bg-white dark:bg-[#0a0a0a] text-slate-700 focus:ring-2 {{ $theme['ring'] }} focus:border-transparent placeholder-slate-400 dark:text-white"
                                        placeholder="KETIK NAMA BARANG / SKU...">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- TABEL UTAMA --}}
                    @if(count($data['products']) > 0)
                    <div
                        class="rounded-3xl border overflow-hidden shadow-lg bg-white border-slate-100 dark:bg-[#121212] dark:border-white/5">
                        <div
                            class="px-6 py-4 border-b border-slate-100 dark:border-white/5 flex justify-between items-center {{ $theme['bg_soft'] }}">
                            <div class="text-xs font-black {{ $theme['text'] }} uppercase tracking-wider"><i
                                    class="fas fa-table mr-2"></i>Data {{ $cabang }}</div>
                            <div
                                class="px-3 py-1 bg-white dark:bg-white/10 rounded-full shadow-sm text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">
                                {{ count($data['products']) }} Items
                            </div>
                        </div>
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-[11px] text-left whitespace-nowrap font-jakarta">
                                <thead
                                    class="uppercase tracking-wider font-extrabold sticky top-0 z-20 bg-slate-50 text-slate-500 border-b border-slate-200 dark:bg-[#0a0a0a] dark:text-slate-400 dark:border-white/10">
                                    <tr>
                                        <th
                                            class="px-4 py-4 w-48 sticky left-0 z-30 border-r bg-slate-50 border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10">
                                            LAST SUPPLIER</th>
                                        <th class="px-4 py-4 w-64 border-r border-slate-200 dark:border-white/5">NAME
                                            ITEM</th>
                                        <th
                                            class="px-4 py-4 text-center w-20 border-r border-slate-200 dark:border-white/5">
                                            STOCK</th>

                                        
                                        <th
                                            class="px-4 py-4 text-right w-36 {{ $theme['text'] }} {{ $theme['bg_soft'] }} border-r {{ $theme['border'] }}">
                                            Harga Beli + PPN
                                            <div
                                                class="text-[9px] opacity-70 normal-case font-bold tracking-tight mt-0.5">
                                                <i class="fas fa-mouse-pointer mr-1"></i>Klik Detail</div>
                                        </th>

                                        <th
                                            class="px-4 py-4 text-right w-32 text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-500/10 border-r border-blue-100 dark:border-white/5">
                                            Harga Jual Sistem</th>
                                        <th wire:click="toggleSort"
                                            class="px-4 py-4 text-right w-28 text-emerald-600 dark:text-emerald-400 bg-emerald-50/30 dark:bg-emerald-500/10 cursor-pointer hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-colors group select-none">
                                            <div class="flex items-center justify-end gap-2">
                                                MARGIN (%)
                                                @if($sortDirection === 'desc') <i
                                                    class="fas fa-sort-amount-down text-[10px]"></i> @else <i
                                                    class="fas fa-sort-amount-up text-[10px]"></i> @endif
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                    @foreach($data['products'] as $p)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">
                                        <td
                                            class="px-4 py-3 font-bold sticky left-0 border-r z-10 transition-colors bg-white text-slate-500 border-slate-100 group-hover:bg-slate-50 dark:bg-[#121212] dark:text-slate-400 dark:border-white/5 dark:group-hover:bg-[#151515]">
                                            <div class="truncate w-40" title="{{ $p['last_supplier'] }}">
                                                {{ $p['last_supplier'] }}</div>
                                        </td>
                                        <td
                                            class="px-4 py-3 font-bold text-slate-700 dark:text-slate-200 border-r border-slate-100 dark:border-white/5">
                                            <div class="truncate w-64" title="{{ $p['name_item'] }}">
                                                {{ $p['name_item'] }}</div>
                                        </td>
                                        <td
                                            class="px-4 py-3 text-center font-mono font-bold text-slate-600 dark:text-slate-300 border-r border-slate-100 dark:border-white/5">
                                            {{ $p['stock'] }}</td>

                                        {{-- CELL AVG (INTERACTIVE) --}}
                                        <td wire:click="openDetail({{ $p['id'] }})"
                                            class="px-4 py-3 text-right font-mono font-black {{ $theme['text'] }} {{ $theme['bg_soft'] }} border-r {{ $theme['border'] }} cursor-pointer hover:brightness-95 transition-all duration-200 relative group-cell"
                                            title="Lihat Detail Pembentuk Harga">
                                            {{ number_format($p['avg_ppn'], 0, ',', '.') }}
                                            <div
                                                class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-cell-hover:opacity-100 transition-opacity">
                                                <i class="fas fa-eye text-[10px]"></i></div>
                                        </td>

                                        <td
                                            class="px-4 py-3 text-right font-mono font-bold text-blue-600 dark:text-blue-400 bg-blue-50/10 dark:bg-blue-500/5 border-r border-blue-50 dark:border-white/5">
                                            {{ number_format($p['harga_jual'], 0, ',', '.') }}</td>
                                        <td
                                            class="px-4 py-3 text-right font-mono font-black {{ $p['margin_persen'] < 0 ? 'text-red-500' : 'text-emerald-600 dark:text-emerald-400' }} bg-emerald-50/10 dark:bg-emerald-500/5">
                                            {{ number_format($p['margin_persen'], 2, ',', '.') }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    {{-- STATE KOSONG --}}
                    @if(count($selectedSuppliers[$cabang] ?? []) > 0)
                    <div
                        class="p-10 text-center border-2 border-dashed border-slate-200 rounded-3xl dark:border-white/10 animate-fade-in-up bg-slate-50/50 dark:bg-white/5">
                        <div
                            class="w-16 h-16 rounded-full {{ $theme['bg_soft'] }} flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search {{ $theme['text'] }} text-xl"></i>
                        </div>
                        <p class="text-xs text-slate-400 dark:text-slate-600 font-bold uppercase">
                            {{ ($filterMode[$cabang] ?? 'all') === 'selected' ? 'Silakan pilih minimal 1 item produk.' : 'Tidak ada data ditemukan untuk supplier ini.' }}
                        </p>
                    </div>
                    @else
                    <div
                        class="p-10 text-center border-2 border-dashed border-slate-200 rounded-3xl dark:border-white/5 bg-slate-50/30 dark:bg-white/5">
                        <div
                            class="w-16 h-16 rounded-full bg-slate-100 dark:bg-white/10 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-arrow-up text-slate-300 dark:text-slate-600 text-xl animate-bounce"></i>
                        </div>
                        <p class="text-xs text-slate-400 dark:text-slate-600 font-bold uppercase">Pilih Supplier di atas
                            untuk menampilkan data.</p>
                    </div>
                    @endif
                    @endif

                </div>
            </div>

        </div>
        @endforeach

    </div>

    {{-- MODAL DETAIL (REDESIGNED) --}}
    <div x-data="{ show: @entangle('isDetailOpen') }" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[60] flex items-center justify-center p-4" style="display: none;">

        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="$wire.closeDetail()">
        </div>

        <div
            class="bg-white dark:bg-[#121212] rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden border border-slate-100 dark:border-white/10 relative z-10 flex flex-col max-h-[90vh]">

            @if($detailProduct)
            <div
                class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 dark:from-black dark:to-[#1a1a1a] p-8 flex justify-between items-start text-white">
                <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none"><i
                        class="fas fa-boxes text-9xl"></i></div>
                <div class="relative z-10 flex gap-5 items-center">
                    <div
                        class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
                        <i class="fas fa-cube text-3xl text-amber-400"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-amber-500 text-black tracking-wider">{{ $detailProduct->cabang }}</span>
                            <span
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $detailProduct->supplier }}</span>
                        </div>
                        <h3 class="text-xl md:text-2xl font-black uppercase tracking-tight leading-snug max-w-lg">
                            {{ $detailProduct->name_item }}</h3>
                        <p class="text-xs text-slate-400 font-mono mt-1 opacity-80">SKU:
                            {{ $detailProduct->sku ?? '-' }}</p>
                    </div>
                </div>
                <button wire:click="closeDetail"
                    class="relative z-10 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors text-white"><i
                        class="fas fa-times"></i></button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar bg-slate-50 dark:bg-[#0a0a0a] flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4
                            class="text-xs font-black uppercase text-slate-400 tracking-[0.2em] flex items-center gap-2 mb-4">
                            <i class="fas fa-warehouse text-emerald-500"></i> Inventory Stats</h4>
                        <div
                            class="bg-white dark:bg-[#181818] p-5 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm space-y-4">
                            <div class="flex justify-between items-center group">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs">
                                        <i class="fas fa-box"></i></div>
                                    <span
                                        class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase">Stok
                                        Fisik</span>
                                </div>
                                <span
                                    class="text-sm font-black text-slate-800 dark:text-white font-mono">{{ $detailProduct->stok }}</span>
                            </div>
                            <div class="h-px bg-slate-100 dark:bg-white/5"></div>
                            <div class="flex justify-between items-center group">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">
                                        <i class="fas fa-sync-alt"></i></div>
                                    <span
                                        class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase">Konversi</span>
                                </div>
                                <span
                                    class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $detailProduct->good_konversi }}</span>
                            </div>
                            <div class="h-px bg-slate-100 dark:bg-white/5"></div>
                            <div class="flex justify-between items-center group">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs">
                                        <i class="fas fa-clipboard-list"></i></div>
                                    <span
                                        class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase">KTN</span>
                                </div>
                                <span
                                    class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $detailProduct->ktn }}</span>
                            </div>
                            <div
                                class="mt-2 p-3 bg-slate-50 dark:bg-white/5 rounded-xl flex justify-between items-center border border-slate-100 dark:border-white/5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Sell / Week</span>
                                <span
                                    class="text-xs font-black text-slate-800 dark:text-white font-mono">{{ $detailProduct->sell_per_week }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4
                            class="text-xs font-black uppercase text-slate-400 tracking-[0.2em] flex items-center gap-2 mb-4">
                            <i class="fas fa-coins text-amber-500"></i> Cost Structure</h4>
                        <div
                            class="bg-white dark:bg-[#181818] p-5 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm space-y-3 relative overflow-hidden">
                            <div class="flex justify-between items-end pb-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Harga Beli (Buy)</span>
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 font-mono">Rp
                                    {{ number_format($detailProduct->buy, 0, ',', '.') }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center pb-2 border-b border-dashed border-slate-200 dark:border-white/10">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Diskon Supplier</span>
                                    @if($detailProduct->buy_disc > 0)
                                    <span
                                        class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">-{{ $detailProduct->buy_disc }}%</span>
                                    @endif
                                </div>
                                <span
                                    class="text-xs font-bold text-red-500 font-mono">{{ $detailProduct->buy_disc > 0 ? $detailProduct->buy_disc . '%' : '-' }}</span>
                            </div>
                            <div class="py-2">
                                <div class="flex justify-between items-center mb-1">
                                    <span
                                        class="text-[11px] font-black text-amber-600 dark:text-amber-500 uppercase">RAW
                                        HPP (AVG)</span>
                                    <span class="text-base font-black text-amber-600 dark:text-amber-500 font-mono">Rp
                                        {{ number_format($detailProduct->avg, 0, ',', '.') }}</span>
                                </div>
                                <p class="text-[9px] text-slate-400 leading-tight">*Angka ini adalah HPP murni dari
                                    Analisa Stok sebelum ditambah PPN.</p>
                            </div>
                            <div
                                class="bg-slate-50 dark:bg-white/5 rounded-xl p-3 border border-slate-100 dark:border-white/5 flex justify-between items-center">
                                <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">PPN
                                    Input</span>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-xs font-black text-slate-800 dark:text-white font-mono">{{ is_numeric($detailProduct->ppn) ? $detailProduct->ppn . '%' : $detailProduct->ppn }}</span>
                                    @if(strtoupper(trim($detailProduct->ppn)) === 'Y' ||
                                    (is_numeric($detailProduct->ppn) && $detailProduct->ppn > 0))
                                    <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                                    @else
                                    <i class="fas fa-times-circle text-slate-300 text-xs"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-slate-50 dark:bg-[#151515] border-t border-slate-200 dark:border-white/5">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Harga Jual Final
                            (Fix)</p>
                        <div
                            class="text-3xl font-black text-blue-600 dark:text-blue-500 tracking-tighter flex items-baseline gap-1">
                            <span class="text-sm font-bold opacity-60">Rp</span>
                            {{ number_format($detailProduct->fix, 0, ',', '.') }}
                        </div>
                    </div>
                    <button wire:click="closeDetail"
                        class="px-8 py-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-black font-bold text-xs uppercase tracking-widest hover:scale-105 transition-transform shadow-lg">Tutup
                        Detail</button>
                </div>
            </div>
            @else
            <div class="p-12 flex flex-col items-center justify-center text-slate-300 dark:text-slate-600">
                <i class="fas fa-circle-notch fa-spin text-4xl mb-4"></i>
                <p class="text-xs font-bold uppercase tracking-widest animate-pulse">Memuat Data...</p>
            </div>
            @endif
        </div>
    </div>

</div>

<style>
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
}

.group-cell:hover .group-cell-hover\:opacity-100 {
    opacity: 1;
}

.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Pattern Dot untuk Hiasan Header */
.pattern-dots {
    background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px);
    background-size: 8px 8px;
}
</style>