<div class="min-h-screen space-y-6 pb-20 font-jakarta transition-colors duration-300 bg-slate-50 dark:bg-[#0a0a0a]">

    <div
        class="sticky top-0 z-30 pt-6 pb-4 px-6 border-b shadow-sm transition-colors duration-300 bg-white/95 backdrop-blur-md border-slate-200 dark:bg-[#121212]/95 dark:border-white/5">
        <div class="max-w-8xl mx-auto">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h1
                        class="text-2xl font-black tracking-tighter uppercase leading-none text-slate-800 dark:text-white">
                        Stock Analysis
                    </h1>
                    <p class="text-[10px] font-bold tracking-[0.3em] uppercase mt-1 text-slate-400 dark:text-slate-500">
                        Multi-Branch & Supplier Intelligence
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach($branches as $b)
                <button wire:click="setCabang('{{ $b }}')" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 border relative overflow-hidden group
                    {{ $selectedCabang === $b 
                        ? 'bg-slate-800 text-white border-slate-800 shadow-lg shadow-slate-800/20 dark:bg-white dark:text-black' 
                        : 'bg-white text-slate-500 border-slate-200 hover:border-slate-300 hover:text-slate-700 dark:bg-[#1a1a1a] dark:text-slate-400 dark:border-white/10 dark:hover:text-white' 
                    }}">
                    {{ $b }}
                    @if($selectedCabang === $b)
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-blue-500"></div>
                    @endif
                </button>
                @endforeach
            </div>

        </div>
    </div>

    <div class="px-6 max-w-8xl mx-auto mt-4">
        <div
            class="grid grid-cols-1 md:grid-cols-12 gap-4 p-5 rounded-2xl border transition-colors bg-white border-slate-200 shadow-sm dark:bg-[#121212] dark:border-white/5">

            <div class="md:col-span-4" x-data="{ 
                    open: false, 
                    search: '', 
                    // PENTING: .live agar langsung update ke controller
                    selected: @entangle('selectedSuppliers').live,
                    items: {{ json_encode($suppliersList) }}
                 }">
                <label
                    class="text-[9px] font-black uppercase tracking-widest mb-1.5 block text-slate-500 dark:text-slate-400 ml-1">
                    Filter Supplier (Wajib Pilih)
                </label>

                <div class="relative" @click.outside="open = false">
                    <button @click="open = !open" type="button" class="w-full pl-4 pr-10 py-2.5 rounded-xl text-xs font-bold border transition-all h-[42px] text-left flex items-center overflow-hidden
                        {{ count($selectedSuppliers) > 0 ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50 dark:bg-blue-900/20' : 'bg-slate-50 border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10' }}
                        text-slate-700 dark:text-white">

                        <span x-show="selected.length === 0" class="text-slate-400">-- Klik untuk Pilih Supplier
                            --</span>
                        <span x-show="selected.length > 0" x-text="selected.length + ' Supplier Dipilih'"
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
                                class="w-full px-3 py-2 rounded-lg text-xs border bg-slate-50 border-slate-200 focus:ring-blue-500 focus:border-blue-500 dark:bg-black dark:border-white/10 dark:text-white uppercase"
                                placeholder="CARI NAMA SUPPLIER...">
                        </div>

                        <div class="overflow-y-auto p-2 space-y-1 custom-scrollbar flex-1">
                            <template x-for="item in items.filter(i => i.toLowerCase().includes(search.toLowerCase()))"
                                :key="item">
                                <label
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer transition-colors">
                                    <input type="checkbox" :value="item" x-model="selected"
                                        class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 dark:bg-black dark:border-white/20">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase"
                                        x-text="item"></span>
                                </label>
                            </template>
                            <div x-show="items.filter(i => i.toLowerCase().includes(search.toLowerCase())).length === 0"
                                class="text-center py-4 text-xs text-slate-400">
                                Tidak ditemukan
                            </div>
                        </div>

                        <div
                            class="p-2 border-t border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-black/20 flex justify-between items-center">
                            <span class="text-[10px] text-slate-400" x-text="selected.length + ' dipilih'"></span>
                            <button @click="selected = []"
                                class="text-[10px] text-red-500 font-bold hover:underline">Reset</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-8 opacity-50 transition-opacity duration-300"
                :class="{'opacity-100': selected.length > 0}">
                <label
                    class="text-[9px] font-black uppercase tracking-widest mb-1.5 block text-slate-500 dark:text-slate-400 ml-1">
                    Cari Produk (Dalam Supplier Terpilih)
                </label>
                <div class="relative group">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-bold border transition-all h-[42px] uppercase
                        bg-slate-50 border-slate-200 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 placeholder-slate-400
                        dark:bg-[#0a0a0a] dark:border-white/10 dark:text-white dark:placeholder-slate-600"
                        placeholder="KETIK NAMA BARANG / SKU..." :disabled="selected.length === 0">
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 max-w-8xl mx-auto mt-6">

        @if(count($selectedSuppliers) > 0)
        <div class="rounded-[1.5rem] border overflow-hidden shadow-2xl transition-all duration-300 animate-fade-in-up
                bg-white border-slate-200 dark:bg-[#121212] dark:border-white/5">

            <div
                class="px-6 py-4 border-b flex justify-between items-center bg-slate-50/50 dark:bg-[#1a1a1a] dark:border-white/5">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Cabang:</span>
                    <span
                        class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-black dark:bg-blue-900/30 dark:text-blue-400">
                        {{ $selectedCabang }}
                    </span>
                    <span class="text-xs text-slate-400 px-1">+</span>
                    <span
                        class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs font-black dark:bg-emerald-900/30 dark:text-emerald-400">
                        {{ count($selectedSuppliers) }} Supplier
                    </span>
                </div>
                <div class="text-[10px] font-bold text-slate-400 uppercase">
                    Total {{ $products->total() }} Produk
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-[11px] text-left whitespace-nowrap font-jakarta">
                    <thead class="uppercase tracking-wider font-extrabold sticky top-0 z-20
                            bg-slate-100 text-slate-500 border-b border-slate-200 
                            dark:bg-[#0a0a0a] dark:text-slate-400 dark:border-white/10">
                        <tr>
                            <th class="px-4 py-4 sticky left-0 z-30 w-64 border-r transition-colors shadow-[4px_0_10px_rgba(0,0,0,0.02)]
                                    bg-slate-100 border-slate-200 dark:bg-[#0a0a0a] dark:border-white/10">Nama Item
                            </th>
                            <th class="px-4 py-4 text-center w-24">Good Konv</th>
                            <th class="px-4 py-4 text-center w-24 text-blue-600 dark:text-blue-400">KTN</th>
                            <th class="px-4 py-4 text-center w-24 text-purple-600 dark:text-purple-400">Sell/Week</th>
                            <th class="px-4 py-4 text-center w-20 text-red-600 dark:text-red-400">EMPTY</th>
                            <th class="px-4 py-4 text-right w-28">BUY</th>
                            <th class="px-4 py-4 text-right w-28 text-emerald-600 dark:text-emerald-400">BUY-DISC</th>
                            <th
                                class="px-4 py-4 text-right w-28 text-amber-600 dark:text-amber-400 bg-amber-50/50 dark:bg-amber-500/10 border-l border-r border-amber-100 dark:border-white/5">
                                AVG (On PPN)</th>
                            <th class="px-4 py-4 text-center w-20">FIX</th>
                            <th class="px-4 py-4 text-center w-16">PPN</th>
                            <th class="px-4 py-4 w-40 text-slate-400">Supplier</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($products as $p)
                        <tr class="transition-colors group border-b border-slate-50 dark:border-white/5 
                                hover:bg-blue-50/30 dark:hover:bg-blue-500/5">
                            <td
                                class="px-4 py-3 font-bold sticky left-0 border-r z-10 transition-colors shadow-[4px_0_10px_rgba(0,0,0,0.02)]
                                    bg-white text-slate-700 border-slate-100 group-hover:bg-blue-50/30 
                                    dark:bg-[#121212] dark:text-slate-200 dark:border-white/5 dark:group-hover:bg-[#151515]">
                                <div class="truncate w-64" title="{{ $p['nama_item'] }}">{{ $p['nama_item'] }}</div>
                            </td>

                            <td class="px-4 py-3 text-center font-mono text-slate-500 text-[10px]">
                                {{ $p['good_konversi'] }}</td>
                            <td
                                class="px-4 py-3 text-center font-black bg-blue-50/20 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                {{ $p['ktn'] }}</td>
                            <td
                                class="px-4 py-3 text-center font-bold bg-purple-50/20 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                {{ $p['sell_per_week'] }}</td>

                            <td
                                class="px-4 py-3 text-center font-bold text-red-600 dark:text-red-400 bg-red-50/5 dark:bg-red-500/5">
                                {{ $p['empty_field'] }}
                            </td>

                            <td class="px-4 py-3 text-right font-mono text-slate-500 dark:text-slate-400">
                                {{ is_numeric($p['buy']) ? number_format($p['buy'], 0, ',', '.') : $p['buy'] }}
                            </td>
                            <td
                                class="px-4 py-3 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50/10 dark:bg-emerald-500/5">
                                {{ is_numeric($p['buy_disc']) ? number_format($p['buy_disc'], 0, ',', '.') : $p['buy_disc'] }}
                            </td>
                            <td
                                class="px-4 py-3 text-right font-mono font-black text-amber-700 dark:text-amber-400 bg-amber-50/50 dark:bg-amber-500/10 border-l border-r border-amber-100 dark:border-white/5">
                                {{ is_numeric($p['avg']) ? number_format($p['avg'], 0, ',', '.') : $p['avg'] }}
                            </td>

                            <td class="px-4 py-3 text-center"><span
                                    class="text-[9px] font-bold text-slate-400">{{ $p['fix'] }}</span></td>

                            <td class="px-4 py-3 text-center font-bold text-slate-600 dark:text-slate-300">
                                {{ $p['ppn'] }}</td>

                            <td class="px-4 py-3 text-slate-400 text-[9px] uppercase">
                                <div class="truncate w-36">{{ $p['supplier'] }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                <i class="fas fa-search text-2xl mb-2 opacity-50"></i>
                                <p class="text-xs">Tidak ada produk ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
            <div
                class="px-6 py-4 border-t transition-colors bg-slate-50 border-slate-200 dark:bg-[#1a1a1a] dark:border-white/5">
                {{ $products->links() }}
            </div>
            @endif
        </div>

        @else
        <div class="flex flex-col items-center justify-center py-24 animate-pulse-slow">
            <div class="w-24 h-24 rounded-full shadow-2xl flex items-center justify-center mb-6 border transition-all
                    bg-white border-slate-100 dark:bg-[#1a1a1a] dark:border-white/5 dark:shadow-none">
                <i class="fas fa-hand-pointer text-4xl text-blue-500 dark:text-blue-400 animate-bounce"></i>
            </div>
            <h2 class="text-xl font-black uppercase tracking-tight text-slate-700 dark:text-white">
                Pilih Supplier Terlebih Dahulu
            </h2>
            <p class="text-sm mt-2 max-w-md text-center text-slate-400 dark:text-slate-500">
                Data produk sengaja disembunyikan. Silakan pilih satu atau lebih <strong>Supplier</strong> pada kolom di
                atas untuk menampilkan analisa stok.
            </p>
        </div>
        @endif

    </div>
</div>