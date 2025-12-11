<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Transaksi Penjualan</h2>
            <p class="text-sm text-slate-500 mt-1">Rekapitulasi faktur penjualan (Group by Invoice).</p>
        </div>
        <button wire:click="openImportModal"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-file-excel mr-2"></i> Import Sales
        </button>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:flex-1 relative group">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Pencarian</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 placeholder-slate-400 transition-all"
                        placeholder="No Faktur / Pelanggan...">
                </div>
            </div>
            
            <div class="w-full md:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Periode</label>
                <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl border border-slate-200">
                    <input type="date" wire:model.live="startDate" class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                    <span class="text-slate-300">|</span>
                    <input type="date" wire:model.live="endDate" class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                </div>
            </div>

            <div class="w-full md:w-64" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Cabang</label>
                <div class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 py-2.5 px-3.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <span class="truncate">{{ count($filterCabang) > 0 ? count($filterCabang).' Dipilih' : 'Semua Cabang' }}</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div x-show="open" class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto p-2" style="display: none;">
                        @foreach($optCabang as $cab)
                        <label class="flex items-center px-2 py-2 hover:bg-emerald-50 rounded-lg cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3">
                            <span class="text-sm text-slate-700">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <button wire:click="resetFilter" class="w-full md:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl transition-colors h-[42px]">
                <i class="fas fa-undo"></i>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[70vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4 border-r border-slate-200">Tanggal</th>
                        <th class="px-6 py-4 border-r border-slate-200">No Faktur</th>
                        <th class="px-6 py-4 border-r border-slate-200 min-w-[200px]">Pelanggan (Klik Detail)</th>
                        <th class="px-6 py-4 border-r border-slate-200">Salesman</th>
                        <th class="px-6 py-4 border-r border-slate-200 text-center">Item</th>
                        <th class="px-6 py-4 border-r border-slate-200 text-right bg-emerald-50/50 text-emerald-700">Total Faktur</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($penjualans as $item)
                    <tr class="hover:bg-emerald-50/20 transition-colors group">
                        
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}
                        </td>
                        
                        <td class="px-6 py-3 border-r border-slate-100 font-mono text-emerald-700 font-bold">
                            {{ $item->trans_no }}
                        </td>
                        
                        <td class="px-6 py-3 border-r border-slate-100">
                            <button wire:click="openDetail('{{ $item->trans_no }}')" 
                                class="text-left font-bold text-slate-700 hover:text-emerald-600 transition-colors flex items-center gap-2 group/link w-full">
                                <span class="truncate max-w-[200px]">{{ $item->nama_pelanggan }}</span>
                                <i class="fas fa-external-link-alt text-[10px] opacity-0 group-hover/link:opacity-100 transition-opacity"></i>
                            </button>
                        </td>
                        
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">
                            {{ $item->sales_name }}
                        </td>
                        
                        <td class="px-6 py-3 border-r border-slate-100 text-center">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold">
                                {{ $item->total_items }} Items
                            </span>
                        </td>
                        
                        <td class="px-6 py-3 border-r border-slate-100 text-right font-extrabold text-slate-800 bg-emerald-50/10">
                            {{ number_format($item->total_invoice, 0, ',', '.') }}
                        </td>
                        
                        <td class="px-6 py-3 text-center sticky right-0 bg-white group-hover:bg-emerald-50/20 transition-colors">
                            <button wire:click="delete('{{ $item->trans_no }}')"
                                onclick="return confirm('Hapus SELURUH faktur {{ $item->trans_no }}?') || event.stopImmediatePropagation()"
                                class="text-slate-300 hover:text-red-500 transition-colors" title="Hapus Faktur">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-search text-3xl mb-2 text-slate-200"></i>
                                <p>Tidak ada data penjualan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $penjualans->links() }}</div>
    </div>

    @if($isDetailOpen)
    <div class="fixed inset-0 z-[70] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" wire:click="closeDetail"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full border border-white/20">
                
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-receipt text-emerald-600"></i> Detail Faktur
                        </h3>
                        <p class="text-slate-500 text-xs mt-0.5 font-mono">{{ $selectedFaktur }}</p>
                    </div>
                    <button wire:click="closeDetail" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="px-6 py-6 bg-white max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Kode Item</th>
                                <th class="px-4 py-3">Nama Produk</th>
                                <th class="px-4 py-3 text-right">Qty</th>
                                <th class="px-4 py-3 text-right">Harga</th>
                                <th class="px-4 py-3 text-right">Diskon</th>
                                <th class="px-4 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($detailItems as $d)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-2 font-mono text-slate-500">{{ $d->sku ?? $d->kode_item ?? '-' }}</td>
                                <td class="px-4 py-2 font-bold text-slate-700">{{ $d->nama_item ?? 'Item Tanpa Nama' }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($d->qty, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($d->nilai_jual_net, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right text-rose-600">{{ number_format($d->total_diskon, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right font-bold text-emerald-600">{{ number_format($d->total_grand, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50 border-t border-slate-200 font-bold">
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-right text-slate-600 uppercase">Total Faktur</td>
                                <td class="px-4 py-3 text-right text-emerald-700 text-sm">
                                    {{ number_format($detailItems->sum('total_grand'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="bg-slate-50 px-6 py-4 flex justify-end">
                    <button wire:click="closeDetail" class="px-5 py-2 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-100 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isImportOpen)
        @include('livewire.partials.import-modal', ['title' => 'Import Penjualan', 'color' => 'emerald']) 
    @endif

</div>