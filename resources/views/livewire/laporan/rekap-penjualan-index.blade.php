<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-emerald-50/90 p-4 rounded-b-2xl shadow-sm border-b border-emerald-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600 shadow-sm">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-emerald-900 tracking-tight">Rekap Penjualan</h1>
                    <p class="text-xs text-emerald-600 font-medium mt-0.5">Lengkap Sesuai Excel
                        ({{ $penjualans->total() }} Baris)</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-3 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-emerald-500 py-2 shadow-sm placeholder-slate-400"
                        placeholder="Cari Faktur / SKU...">
                </div>

                <div
                    class="flex items-center gap-1 bg-white border border-white rounded-lg px-2 py-1 shadow-sm h-[34px]">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                    <span class="text-slate-300 text-[10px]">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                </div>

                <div class="relative w-full sm:w-32" x-data="{ open: false, selected: @entangle('filterCabang').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-emerald-50 transition-all">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Cabang' : 'Semua Cabang'"></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-48 bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto custom-scrollbar"
                        style="display: none;">
                        <div @click="selected = []"
                            class="px-2 py-1.5 text-xs text-rose-500 font-bold cursor-pointer hover:bg-rose-50 rounded mb-1 flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> Reset
                        </div>
                        @foreach($optCabang as $c)
                        <div @click="selected.includes('{{ $c }}') ? selected = selected.filter(i => i !== '{{ $c }}') : selected.push('{{ $c }}')"
                            class="flex items-center px-2 py-1.5 hover:bg-emerald-50 rounded cursor-pointer transition-colors group">
                            <div class="w-4 h-4 rounded border flex items-center justify-center mr-2"
                                :class="selected.includes('{{ $c }}') ? 'bg-emerald-500 border-emerald-500' : 'border-slate-300'">
                                <i x-show="selected.includes('{{ $c }}')"
                                    class="fas fa-check text-white text-[9px]"></i>
                            </div>
                            <span class="text-xs text-slate-600 truncate">{{ $c }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="relative w-full sm:w-32" x-data="{ open: false, selected: @entangle('filterSales').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-emerald-50 transition-all">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Sales' : 'Semua Sales'"></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-48 bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto custom-scrollbar"
                        style="display: none;">
                        <div @click="selected = []"
                            class="px-2 py-1.5 text-xs text-rose-500 font-bold cursor-pointer hover:bg-rose-50 rounded mb-1 flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> Reset
                        </div>
                        @foreach($optSales as $s)
                        <div @click="selected.includes('{{ $s }}') ? selected = selected.filter(i => i !== '{{ $s }}') : selected.push('{{ $s }}')"
                            class="flex items-center px-2 py-1.5 hover:bg-emerald-50 rounded cursor-pointer transition-colors group">
                            <div class="w-4 h-4 rounded border flex items-center justify-center mr-2"
                                :class="selected.includes('{{ $s }}') ? 'bg-emerald-500 border-emerald-500' : 'border-slate-300'">
                                <i x-show="selected.includes('{{ $s }}')"
                                    class="fas fa-check text-white text-[9px]"></i>
                            </div>
                            <span class="text-xs text-slate-600 truncate">{{ $s }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="hidden sm:block h-6 w-px bg-emerald-200 mx-1"></div>

                <button wire:click="resetFilter"
                    class="px-3 py-2 bg-white border border-emerald-200 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-50 shadow-sm"
                    title="Reset Filter">
                    <i class="fas fa-undo"></i>
                </button>

                <button wire:click="export" wire:loading.attr="disabled" wire:target="export"
                    class="px-3 py-2 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 shadow-md shadow-emerald-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <span wire:loading.remove wire:target="export"><i class="fas fa-file-excel"></i> Export</span>
                    <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin"></i> Proses...</span>
                </button>

                <div wire:loading
                    class="px-3 py-2 bg-white border border-emerald-200 text-emerald-600 rounded-lg shadow-sm flex items-center justify-center animate-pulse">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>

            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[85vh] overflow-hidden">
            <div class="overflow-auto flex-1 w-full custom-scrollbar">
                <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                    <thead
                        class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200 sticky top-0 z-20">
                        <tr>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-0 z-30 shadow-sm">
                                Cabang</th>
                            <th
                                class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-[60px] z-30 shadow-sm">
                                No Faktur</th>
                            <th class="px-3 py-3 border-r border-slate-200">Status</th>
                            <th class="px-3 py-3 border-r border-slate-200">Tanggal</th>
                            <th class="px-3 py-3 border-r border-slate-200">Period</th>
                            <th class="px-3 py-3 border-r border-slate-200">Jatuh Tempo</th>
                            <th class="px-3 py-3 border-r border-slate-200">Kode Pelanggan</th>
                            <th class="px-3 py-3 border-r border-slate-200 min-w-[200px]">Nama Pelanggan</th>
                            <th class="px-3 py-3 border-r border-slate-200">Kode Item</th>
                            <th class="px-3 py-3 border-r border-slate-200">SKU</th>
                            <th class="px-3 py-3 border-r border-slate-200">No Batch</th>
                            <th class="px-3 py-3 border-r border-slate-200">Expired Date</th>
                            <th class="px-3 py-3 border-r border-slate-200 min-w-[250px]">Nama Item</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right bg-emerald-50 text-emerald-700">
                                Qty</th>
                            <th class="px-3 py-3 border-r border-slate-200">Satuan Jual</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right">Nilai</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right">Rata2</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right">D1</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right">D2</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right">Diskon 1</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right">Diskon 2</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right">Disc Bawah</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right text-rose-600">Total Diskon</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right">Nilai Jual Net</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right">Total Harga Jual</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right">PPN Head</th>
                            <th
                                class="px-3 py-3 border-r border-slate-200 text-right font-bold bg-yellow-50 text-yellow-700">
                                Total Grand</th>
                            <th class="px-3 py-3 border-r border-slate-200 text-right text-blue-600">Margin</th>
                            <th class="px-3 py-3 border-r border-slate-200">Pembayaran</th>

                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-100 font-bold text-indigo-700">Kode
                                Sales</th>
                            <th class="px-3 py-3 border-r border-slate-200">Salesman</th>

                            <th class="px-3 py-3 border-r border-slate-200">Supplier</th>
                            <th class="px-3 py-3 border-r border-slate-200">Divisi</th>
                            <th class="px-3 py-3 border-r border-slate-200">Mother SKU</th>
                            <th class="px-3 py-3 border-r border-slate-200">City Code</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($penjualans as $item)
                        <tr class="hover:bg-emerald-50/20 transition-colors odd:bg-white even:bg-slate-50/30">
                            <td class="px-3 py-2 border-r border-slate-100 font-bold sticky left-0 bg-inherit z-10">
                                {{ $item->cabang }}</td>
                            <td
                                class="px-3 py-2 border-r border-slate-100 font-mono text-emerald-600 sticky left-[60px] bg-inherit z-10">
                                {{ $item->trans_no }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->status }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">
                                {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->period }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->jatuh_tempo }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->kode_pelanggan }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 font-bold truncate max-w-[200px]"
                                title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 font-mono">{{ $item->kode_item }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 font-mono">{{ $item->sku }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->no_batch }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->ed }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 truncate max-w-[250px]"
                                title="{{ $item->nama_item }}">{{ $item->nama_item }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right font-bold text-emerald-700">
                                {{ number_format($item->qty, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->satuan_jual }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format($item->nilai, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format($item->rata2, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format($item->d1, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format($item->d2, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format($item->diskon_1, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format($item->diskon_2, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format($item->diskon_bawah, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right text-rose-600">
                                {{ number_format($item->total_diskon, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format($item->nilai_jual_net, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format($item->total_harga_jual, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format($item->ppn_head, 0, ',', '.') }}</td>
                            <td
                                class="px-3 py-2 border-r border-slate-100 text-right font-bold bg-yellow-50/50 text-yellow-700">
                                {{ number_format($item->total_grand, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right text-blue-600">
                                {{ number_format($item->margin, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->pembayaran }}</td>

                            <td
                                class="px-3 py-2 border-r border-slate-100 font-mono font-bold text-indigo-600 bg-slate-50/50">
                                {{ $item->kode_sales }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->sales_name }}</td>

                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->supplier }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->divisi }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->mother_sku }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->city_code_outlet_program }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="37" class="px-6 py-12 text-center text-slate-400">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $penjualans->links() }}</div>
        </div>
    </div>

</div>