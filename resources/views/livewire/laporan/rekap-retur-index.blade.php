<div class="min-h-screen space-y-6 pb-20 transition-colors duration-300 font-jakarta">

    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2.5 rounded-xl shadow-lg dark:bg-rose-500/20 bg-rose-600 text-white dark:text-rose-400">
                    <i class="fas fa-undo-alt text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Rekap <span class="text-rose-500">Retur</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 mt-1.5 dark:text-slate-400 text-slate-500">
                        Audit Trail Returns ({{ number_format($returs->total(), 0, ',', '.') }} Rows)</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-rose-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-rose-500/20 transition-all
                        dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner" placeholder="No Retur / Item...">
                </div>

                <div
                    class="flex items-center gap-1.5 p-1.5 dark:bg-white/5 bg-white border dark:border-white/10 border-slate-200 rounded-xl shadow-sm h-[38px]">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-[10px] font-black uppercase dark:bg-transparent bg-transparent text-slate-700 dark:text-slate-300 focus:ring-0 p-0 w-24 cursor-pointer">
                    <span class="text-slate-300 dark:text-slate-600 text-[10px] font-black">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-[10px] font-black uppercase dark:bg-transparent bg-transparent text-slate-700 dark:text-slate-300 focus:ring-0 p-0 w-24 cursor-pointer">
                </div>

                <div class="relative w-full sm:w-32" x-data="{ open: false, selected: @entangle('filterCabang').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between border px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm
                        dark:bg-black/40 dark:border-white/5 dark:text-slate-300 bg-white border-slate-200 text-slate-700 hover:border-rose-400">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Regional' : 'CABANG'"></span>
                        <i class="fas fa-chevron-down opacity-40 text-[10px] transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="absolute z-50 mt-2 w-48 border rounded-2xl shadow-2xl p-2 max-h-60 overflow-y-auto custom-scrollbar
                        dark:bg-slate-900 border-slate-800 bg-white border-slate-200" style="display: none;">
                        <div @click="selected = []"
                            class="px-3 py-2 text-[10px] text-rose-500 font-black uppercase tracking-widest cursor-pointer hover:bg-rose-500/10 rounded-xl mb-1 flex items-center gap-2">
                            <i class="fas fa-times-circle"></i> Reset Filter
                        </div>
                        @foreach($optCabang as $c)
                        <label
                            class="flex items-center px-3 py-2.5 hover:bg-rose-500/10 rounded-xl cursor-pointer transition-colors group">
                            <input type="checkbox" value="{{ $c }}" x-model="selected"
                                class="rounded-full border-slate-500 text-rose-600 focus:ring-rose-500 h-3.5 w-3.5">
                            <span
                                class="ml-3 text-[10px] font-bold uppercase tracking-tight dark:text-slate-400 text-slate-600">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button wire:click="resetFilter"
                        class="px-4 py-2.5 dark:bg-white/5 bg-white border dark:border-white/10 border-slate-200 dark:text-slate-300 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-undo"></i>
                    </button>
                    <button wire:click="export" wire:loading.attr="disabled" wire:target="export"
                        class="flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/20 transition-all transform active:scale-95">
                        <span wire:loading.remove wire:target="export"><i class="fas fa-file-excel"></i> Export</span>
                        <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin"></i> Process</span>
                    </button>
                </div>

                <div wire:loading
                    class="w-10 h-10 rounded-xl flex items-center justify-center dark:bg-slate-800 bg-white border dark:border-white/5 border-slate-200 shadow-sm animate-pulse">
                    <i class="fas fa-circle-notch fa-spin text-rose-500"></i>
                </div>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none"
        class="transition-opacity duration-300 px-4 sm:px-6 lg:px-8">
        <div class="rounded-[2.5rem] border overflow-hidden transition-all duration-300 flex flex-col min-h-[80vh] mb-10
            dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-2xl dark:shadow-black/60">

            <div class="overflow-auto flex-1 w-full custom-scrollbar">
                <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full uppercase">
                    <thead>
                        <tr
                            class="dark:bg-white/5 bg-slate-50 text-slate-500 dark:text-slate-400 font-black tracking-[0.15em] border-b dark:border-white/5 border-slate-100">
                            <th
                                class="px-4 py-6 border-r dark:border-white/5 border-slate-100 bg-inherit sticky left-0 z-30 shadow-xl shadow-black/5">
                                Cabang</th>
                            <th
                                class="px-4 py-6 border-r dark:border-white/5 border-slate-100 bg-inherit sticky left-[60px] z-30 shadow-xl shadow-black/5 text-rose-500">
                                No Retur</th>

                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">Status</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">Tgl Retur</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">No Inv Asal</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-center">Cust Code
                            </th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 min-w-[200px]">Pelanggan
                            </th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">Kode Item</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 min-w-[250px]">Nama Item
                            </th>
                            <th
                                class="px-4 py-6 border-r border-rose-500/20 dark:bg-rose-600/10 bg-rose-50 text-rose-600 text-right">
                                Qty</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">Satuan</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">Nilai</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right italic">Rata2
                            </th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">Up %</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">Nilai Up</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">Pembulatan
                            </th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">D1</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">D2</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">Disc 1</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">Disc 2</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">Disc Bawah
                            </th>
                            <th
                                class="px-4 py-6 border-r border-rose-500/20 dark:bg-rose-600/5 bg-rose-50 text-rose-700 text-right">
                                Total Disc</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">Nilai Net
                            </th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">Total Harga
                            </th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">PPN Head</th>
                            <th
                                class="px-4 py-6 border-r border-yellow-500/20 dark:bg-yellow-600/10 bg-yellow-50 text-yellow-700 text-right font-black">
                                Total Grand</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">PPN Value
                            </th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-right">Min PPN</th>
                            <th
                                class="px-4 py-6 border-r border-blue-500/20 dark:bg-blue-600/10 bg-blue-50 text-blue-600 text-right">
                                Margin</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">Term</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">Salesman</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">Supplier</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">Year</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">Month</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100 text-center">Divisi</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">Program</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">City Code</th>
                            <th class="px-4 py-6 border-r dark:border-white/5 border-slate-100">Mother SKU</th>
                            <th class="px-4 py-6">Last Supp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                        @forelse($returs as $item)
                        <tr class="hover:bg-rose-500/[0.02] transition-colors group dark:bg-[#0a0a0a] bg-white">
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-50 font-black sticky left-0 bg-inherit z-10 text-slate-400">
                                {{ $item->cabang }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-50 font-mono font-black text-rose-600 dark:text-rose-400 sticky left-[60px] bg-inherit z-10 shadow-xl shadow-black/5">
                                {{ $item->no_retur }}</td>

                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-[9px] font-bold opacity-60 uppercase">
                                {{ $item->status }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 opacity-80">
                                {{ date('d/m/Y', strtotime($item->tgl_retur)) }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-50 font-mono text-[9px] opacity-60">
                                {{ $item->no_inv }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 font-mono text-center">
                                {{ $item->kode_pelanggan }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 font-black dark:text-white text-slate-800 truncate max-w-[200px]"
                                title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 font-mono opacity-50">
                                {{ $item->kode_item }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 font-bold dark:text-white text-slate-700 truncate max-w-[250px]"
                                title="{{ $item->nama_item }}">{{ $item->nama_item }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right font-black text-rose-600 bg-rose-500/[0.02]">
                                {{ number_format($item->qty, 0, ',', '.') }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-center opacity-40 italic">
                                {{ $item->satuan_retur }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right">
                                {{ number_format($item->nilai, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right opacity-50">
                                {{ number_format($item->rata2, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right opacity-60">
                                {{ number_format($item->up_percent, 2, ',', '.') }}%</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right">
                                {{ number_format($item->nilai_up, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right">
                                {{ number_format($item->nilai_retur_pembulatan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right">
                                {{ number_format($item->d1, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right">
                                {{ number_format($item->d2, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right">
                                {{ number_format($item->diskon_1, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right">
                                {{ number_format($item->diskon_2, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right">
                                {{ number_format($item->diskon_bawah, 0, ',', '.') }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right font-bold text-rose-500 bg-rose-500/[0.03]">
                                {{ number_format($item->total_diskon, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right opacity-80">
                                {{ number_format($item->nilai_retur_net, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right">
                                {{ number_format($item->total_harga_retur, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right opacity-40">
                                {{ number_format($item->ppn_head, 0, ',', '.') }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right font-black dark:text-yellow-400 text-yellow-700 bg-yellow-500/[0.02]">
                                {{ number_format($item->total_grand, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right opacity-40">
                                {{ number_format($item->ppn_value, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right opacity-40">
                                {{ number_format($item->total_min_ppn, 0, ',', '.') }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-right font-black text-blue-500 bg-blue-500/[0.01]">
                                {{ number_format($item->margin, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 opacity-60">
                                {{ $item->pembayaran }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-50 font-bold opacity-80 italic">
                                {{ $item->sales_name }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-purple-500 italic opacity-80">
                                {{ $item->supplier }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-center opacity-40">
                                {{ $item->year }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-center opacity-40">
                                {{ $item->month }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 text-center opacity-40">
                                {{ $item->divisi }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 opacity-40 italic">
                                {{ $item->program }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 font-mono opacity-30">
                                {{ $item->city_code }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-50 font-mono opacity-30">
                                {{ $item->mother_sku }}</td>
                            <td class="px-4 py-3 opacity-30">{{ $item->last_suppliers }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="40" class="px-6 py-32 text-center opacity-20">
                                <i class="fas fa-undo-alt text-8xl mb-4"></i>
                                <p class="text-sm font-black tracking-[0.4em]">Audit Trail Returns Empty</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="px-6 py-6 border-t dark:border-white/5 border-slate-100 dark:bg-white/[0.02] bg-slate-50/50 uppercase font-black text-[10px]">
                {{ $returs->links() }}
            </div>
        </div>
    </div>

</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(244, 63, 94, 0.3);
    border-radius: 10px;
}

tbody tr {
    animation: fadeIn 0.4s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(8px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>