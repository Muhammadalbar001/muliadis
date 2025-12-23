<div class="min-h-screen space-y-12 pb-20 font-jakarta transition-colors duration-300 bg-slate-50 dark:bg-[#0a0a0a]">

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
        <div class="animate-fade-in-up" wire:key="cabang-{{ $cabang }}">

            <div class="flex items-center justify-between mb-6 pl-4 border-l-4 border-emerald-500">
                <h2 class="text-3xl font-black uppercase text-slate-800 dark:text-white tracking-tight">
                    {{ $cabang }}
                </h2>

                @if(count($data['products']) > 0)
                <button wire:click="export('{{ $cabang }}')" wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg shadow-lg hover:shadow-emerald-500/30 transition-all text-xs font-bold uppercase tracking-wider group">
                    <i class="fas fa-file-excel group-hover:scale-110 transition-transform"></i>
                    <span>Export Excel</span>
                    <span wire:loading wire:target="export('{{ $cabang }}')" class="ml-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
                @endif
            </div>

            <div
                class="p-5 rounded-2xl border transition-colors bg-white border-slate-200 shadow-sm dark:bg-[#121212] dark:border-white/5 mb-6 space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-4" x-data="{ 
                            open: false, 
                            search: '', 
                            selected: @entangle('selectedSuppliers.' . $cabang).live,
                            items: {{ json_encode($data['suppliers_list']) }}
                         }">
                        <label
                            class="text-[9px] font-black uppercase tracking-widest mb-1.5 block text-slate-500 dark:text-slate-400 ml-1">
                            Langkah 1: Pilih Supplier
                        </label>

                        <div class="relative" @click.outside="open = false">
                            <button @click="open = !open" type="button" class="w-full pl-4 pr-10 py-2.5 rounded-xl text-xs font-bold border transition-all h-[42px] text-left flex items-center overflow-hidden
                                {{ count($selectedSuppliers[$cabang] ?? []) > 0 ? 'border-emerald-500 ring-1 ring-emerald-500 bg-emerald-50/50 dark:bg-emerald-900/20' : 'bg-slate-50 border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10' }}
                                text-slate-700 dark:text-white">
                                <span x-show="selected.length === 0" class="text-slate-400">-- Pilih Supplier --</span>
                                <span x-show="selected.length > 0" x-text="selected.length + ' Supplier Dipilih'"
                                    class="text-emerald-600 dark:text-emerald-400"></span>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                        :class="{'rotate-180': open}"></i>
                                </div>
                            </button>

                            <div x-show="open" x-transition.opacity x-cloak
                                class="absolute z-50 w-full mt-2 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-xl shadow-2xl max-h-80 overflow-hidden flex flex-col">
                                <div
                                    class="p-3 border-b border-slate-100 dark:border-white/5 sticky top-0 bg-white dark:bg-[#1a1a1a]">
                                    <input x-model="search" type="text"
                                        class="w-full px-3 py-2 rounded-lg text-xs border bg-slate-50 border-slate-200 focus:ring-emerald-500 dark:bg-black dark:border-white/10 dark:text-white uppercase"
                                        placeholder="CARI SUPPLIER...">
                                </div>
                                <div class="overflow-y-auto p-2 space-y-1 custom-scrollbar flex-1">
                                    <template
                                        x-for="item in items.filter(i => i.toLowerCase().includes(search.toLowerCase()))"
                                        :key="item">
                                        <label
                                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer transition-colors">
                                            <input type="checkbox" :value="item" x-model="selected"
                                                class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500 dark:bg-black dark:border-white/20">
                                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase"
                                                x-text="item"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(count($selectedSuppliers[$cabang] ?? []) > 0)
                    <div class="md:col-span-8 flex items-end pb-1 gap-6 animate-fade-in-up">
                        <div>
                            <label
                                class="text-[9px] font-black uppercase tracking-widest mb-2 block text-slate-500 dark:text-slate-400">
                                Langkah 2: Opsi Tampilan
                            </label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" value="all" wire:model.live="filterMode.{{ $cabang }}"
                                        class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:bg-black dark:border-white/20">
                                    <span
                                        class="ml-2 text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Tampilkan
                                        Semua</span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" value="selected" wire:model.live="filterMode.{{ $cabang }}"
                                        class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:bg-black dark:border-white/20">
                                    <span
                                        class="ml-2 text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Filter
                                        Produk Tertentu</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                @if(count($selectedSuppliers[$cabang] ?? []) > 0)
                <div
                    class="grid grid-cols-1 md:grid-cols-12 gap-4 pt-2 border-t border-slate-100 dark:border-white/5 animate-fade-in-up">

                    @if(($filterMode[$cabang] ?? 'all') === 'selected')
                    <div class="md:col-span-6" x-data="{ 
                            open: false, 
                            search: '', 
                            selected: @entangle('selectedProductIds.' . $cabang).live,
                            items: {{ json_encode($data['products_list_dropdown']) }}
                         }">
                        <label
                            class="text-[9px] font-black uppercase tracking-widest mb-1.5 block text-slate-500 dark:text-slate-400 ml-1">
                            Pilih Produk (Bisa Banyak)
                        </label>

                        <div class="relative" @click.outside="open = false">
                            <button @click="open = !open" type="button"
                                class="w-full pl-4 pr-10 py-2.5 rounded-xl text-xs font-bold border transition-all h-[42px] text-left flex items-center overflow-hidden
                                border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-blue-900/20 text-slate-700 dark:text-white">
                                <span x-show="selected.length === 0" class="text-slate-400">-- Klik untuk Pilih Item
                                    --</span>
                                <span x-show="selected.length > 0" x-text="selected.length + ' Item Dipilih'"
                                    class="text-blue-600 dark:text-blue-400"></span>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                        :class="{'rotate-180': open}"></i>
                                </div>
                            </button>

                            <div x-show="open" x-transition.opacity x-cloak
                                class="absolute z-50 w-full mt-2 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-white/10 rounded-xl shadow-2xl max-h-80 overflow-hidden flex flex-col">
                                <div
                                    class="p-3 border-b border-slate-100 dark:border-white/5 sticky top-0 bg-white dark:bg-[#1a1a1a]">
                                    <input x-model="search" type="text"
                                        class="w-full px-3 py-2 rounded-lg text-xs border bg-slate-50 border-slate-200 focus:ring-blue-500 dark:bg-black dark:border-white/10 dark:text-white uppercase"
                                        placeholder="CARI NAMA ITEM...">
                                </div>
                                <div class="overflow-y-auto p-2 space-y-1 custom-scrollbar flex-1">
                                    <template
                                        x-for="item in items.filter(i => i.name_item.toLowerCase().includes(search.toLowerCase()))"
                                        :key="item.id">
                                        <label
                                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer transition-colors">
                                            <input type="checkbox" :value="item.id" x-model="selected"
                                                class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 dark:bg-black dark:border-white/20">
                                            <span
                                                class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase truncate"
                                                x-text="item.name_item"></span>
                                        </label>
                                    </template>
                                </div>
                                <div
                                    class="p-2 border-t border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-black/20 flex justify-between items-center">
                                    <span class="text-[10px] text-slate-400" x-text="selected.length + ' item'"></span>
                                    <button @click="selected = []"
                                        class="text-[10px] text-red-500 font-bold hover:underline">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div
                        class="{{ ($filterMode[$cabang] ?? 'all') === 'selected' ? 'md:col-span-6' : 'md:col-span-12' }}">
                        <label
                            class="text-[9px] font-black uppercase tracking-widest mb-1.5 block text-slate-500 dark:text-slate-400 ml-1">
                            Pencarian Cepat di Tabel
                        </label>
                        <div class="relative group">
                            <i
                                class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors text-xs"></i>
                            <input wire:model.live.debounce.300ms="search.{{ $cabang }}" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-bold border transition-all h-[42px] uppercase
                                bg-slate-50 border-slate-200 text-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 placeholder-slate-400
                                dark:bg-[#0a0a0a] dark:border-white/10 dark:text-white dark:placeholder-slate-600"
                                placeholder="KETIK NAMA BARANG / SKU...">
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if(count($data['products']) > 0)
            <div
                class="rounded-[1.5rem] border overflow-hidden shadow-xl bg-white border-slate-200 dark:bg-[#121212] dark:border-white/5">
                <div
                    class="px-6 py-4 border-b flex justify-between items-center bg-slate-50/50 dark:bg-[#1a1a1a] dark:border-white/5">
                    <div class="text-xs font-bold text-slate-500">Data {{ $cabang }}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase">Total {{ count($data['products']) }}
                        Items</div>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-[11px] text-left whitespace-nowrap font-jakarta">
                        <thead class="uppercase tracking-wider font-extrabold sticky top-0 z-20
                                bg-slate-100 text-slate-500 border-b border-slate-200 
                                dark:bg-[#0a0a0a] dark:text-slate-400 dark:border-white/10">
                            <tr>
                                <th
                                    class="px-4 py-4 w-48 sticky left-0 z-30 border-r bg-slate-100 border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10">
                                    LAST SUPPLIER</th>
                                <th class="px-4 py-4 w-64 border-r border-slate-200 dark:border-white/5">NAME ITEM</th>
                                <th class="px-4 py-4 text-center w-20 border-r border-slate-200 dark:border-white/5">
                                    STOCK</th>
                                <th
                                    class="px-4 py-4 text-right w-32 text-amber-600 dark:text-amber-400 bg-amber-50/30 dark:bg-amber-500/10 border-r border-amber-100 dark:border-white/5">
                                    AVG (HPP+PPN)</th>
                                <th
                                    class="px-4 py-4 text-right w-32 text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-500/10 border-r border-blue-100 dark:border-white/5">
                                    Harga Jual</th>

                                <th wire:click="toggleSort"
                                    class="px-4 py-4 text-right w-28 text-emerald-600 dark:text-emerald-400 bg-emerald-50/30 dark:bg-emerald-500/10 cursor-pointer hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-colors group select-none">
                                    <div class="flex items-center justify-end gap-2">
                                        MARGIN (%)
                                        @if($sortDirection === 'desc')
                                        <i class="fas fa-sort-amount-down text-[10px]"></i>
                                        @else
                                        <i class="fas fa-sort-amount-up text-[10px]"></i>
                                        @endif
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @foreach($data['products'] as $p)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                <td
                                    class="px-4 py-3 font-bold sticky left-0 border-r z-10 transition-colors bg-white text-slate-500 border-slate-100 group-hover:bg-slate-50 dark:bg-[#121212] dark:text-slate-400 dark:border-white/5 dark:group-hover:bg-[#151515]">
                                    <div class="truncate w-40" title="{{ $p['last_supplier'] }}">
                                        {{ $p['last_supplier'] }}</div>
                                </td>
                                <td
                                    class="px-4 py-3 font-bold text-slate-700 dark:text-slate-200 border-r border-slate-100 dark:border-white/5">
                                    <div class="truncate w-64" title="{{ $p['name_item'] }}">{{ $p['name_item'] }}</div>
                                </td>
                                <td
                                    class="px-4 py-3 text-center font-mono font-bold text-slate-600 dark:text-slate-300 border-r border-slate-100 dark:border-white/5">
                                    {{ $p['stock'] }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right font-mono font-black text-amber-600 dark:text-amber-400 bg-amber-50/10 dark:bg-amber-500/5 border-r border-amber-50 dark:border-white/5">
                                    {{ number_format($p['avg_ppn'], 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right font-mono font-bold text-blue-600 dark:text-blue-400 bg-blue-50/10 dark:bg-blue-500/5 border-r border-blue-50 dark:border-white/5">
                                    {{ number_format($p['harga_jual'], 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right font-mono font-black {{ $p['margin_persen'] < 0 ? 'text-red-500' : 'text-emerald-600 dark:text-emerald-400' }} bg-emerald-50/10 dark:bg-emerald-500/5">
                                    {{ number_format($p['margin_persen'], 2, ',', '.') }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            @if(count($selectedSuppliers[$cabang] ?? []) > 0)
            <div
                class="p-8 text-center border-2 border-dashed border-slate-200 rounded-2xl dark:border-white/10 animate-fade-in-up">
                @if(($filterMode[$cabang] ?? 'all') === 'selected')
                <p class="text-xs text-slate-400 dark:text-slate-600 font-bold uppercase">
                    Silakan pilih minimal 1 item produk pada dropdown di atas.
                </p>
                @else
                <p class="text-xs text-slate-400 dark:text-slate-600 font-bold uppercase">
                    Tidak ada data ditemukan untuk supplier ini.
                </p>
                @endif
            </div>
            @else
            <div class="p-8 text-center border-2 border-dashed border-slate-100 rounded-2xl dark:border-white/5">
                <p class="text-xs text-slate-300 dark:text-slate-700 font-bold uppercase">
                    Pilih Supplier untuk menampilkan data {{ $cabang }}
                </p>
            </div>
            @endif
            @endif

        </div>
        @endforeach

    </div>
</div>

<style>
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    height: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
    border: 2px solid transparent;
    background-clip: content-box;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #475569;
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
</style>