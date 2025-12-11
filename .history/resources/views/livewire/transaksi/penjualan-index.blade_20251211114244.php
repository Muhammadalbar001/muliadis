<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Transaksi Penjualan</h2>
            <p class="text-sm text-slate-500 mt-1">Rekapitulasi faktur penjualan dan pencapaian sales harian.</p>
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
                        <i
                            class="fas fa-search text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 placeholder-slate-400 transition-all group-hover:border-emerald-300"
                        placeholder="Cari No Faktur, Pelanggan, atau Sales...">
                </div>
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Periode
                    Transaksi</label>
                <div
                    class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl border border-slate-200 hover:border-emerald-300 transition-colors">
                    <input type="date" wire:model.live="startDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                    <span class="text-slate-300 font-light">|</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                </div>
            </div>

            <div class="w-full md:w-64 relative" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Filter Cabang</label>
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 py-2.5 px-3.5 rounded-xl text-xs font-bold hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-sm">
                    <span class="truncate">
                        {{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang) . ' Dipilih' }}
                    </span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"
                        :class="{'rotate-180': open}"></i>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto p-2"
                    style="display: none;">
                    @foreach($optCabang as $cab)
                    <label
                        class="flex items-center px-2 py-2 hover:bg-emerald-50 rounded-lg cursor-pointer transition-colors group">
                        <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                            class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3 cursor-pointer">
                        <span class="text-xs font-medium text-slate-600 group-hover:text-emerald-700">{{ $cab }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <button wire:click="resetFilter"
                class="w-full md:w-auto px-4 py-2.5 bg-white border border-slate-200 hover:border-rose-300 hover:text-rose-600 text-slate-500 rounded-xl transition-all shadow-sm h-[42px] flex items-center justify-center gap-2"
                title="Reset Semua Filter">
                <i class="fas fa-undo text-xs"></i> <span class="hidden md:inline text-xs font-bold">Reset</span>
            </button>
        </div>
    </div>

    @if(isset($summary))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
            class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-5 text-white shadow-lg shadow-emerald-500/20 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider mb-1">Total Omzet Penjualan</p>
                <h3 class="text-2xl font-extrabold tracking-tight">Rp
                    {{ number_format($summary['total_omzet'], 0, ',', '.') }}</h3>
            </div>
            <div class="absolute right-4 top-4 text-white/20 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-coins text-6xl transform rotate-12"></i>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-emerald-300 transition-colors">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Faktur</p>
                <h3 class="text-2xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_faktur'], 0, ',', '.') }}</h3>
                <span
                    class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-full mt-1 inline-block">Transaksi</span>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i class="fas fa-file-invoice text-xl"></i>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-orange-300 transition-colors">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Item Terjual</p>
                <h3 class="text-2xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_items'], 0, ',', '.') }}</h3>
                <span
                    class="text-[10px] text-orange-600 font-bold bg-orange-50 px-2 py-0.5 rounded-full mt-1 inline-block">Barang
                    / SKU</span>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:bg-orange-500 group-hover:text-white transition-all">
                <i class="fas fa-boxes-stacked text-xl"></i>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[65vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">

                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider border-r border-slate-200">
                            Tanggal</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider border-r border-slate-200">
                            No Faktur</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider border-r border-slate-200 min-w-[250px]">
                            Nama Pelanggan</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider border-r border-slate-200">
                            Salesman</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider border-r border-slate-200 text-center">
                            Cabang</th>
                        <th
                            class="px-6 py-4 font-bold text-emerald-700 uppercase tracking-wider border-r border-slate-200 text-right bg-emerald-50/50">
                            Total (Rp)</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider text-center bg-slate-50 sticky right-0 z-20 border-l border-slate-200 shadow-sm w-20">
                            Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($penjualans as $item)
                    <tr class="hover:bg-emerald-50/40 transition-colors group relative">

                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600 font-medium">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}
                        </td>

                        <td
                            class="px-6 py-3 border-r border-slate-100 font-mono text-emerald-700 font-bold group-hover:text-emerald-800 transition-colors">
                            {{ $item->trans_no }}
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100">
                            <button wire:click="openDetail('{{ $item->trans_no }}')"
                                class="text-left font-bold text-slate-700 hover:text-emerald-600 transition-colors flex items-center gap-2 group/link w-full">
                                <span class="truncate max-w-[250px]">{{ $item->nama_pelanggan }}</span>
                                <i
                                    class="fas fa-external-link-alt text-[10px] opacity-0 group-hover/link:opacity-100 transition-opacity text-slate-400"></i>
                            </button>
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">
                            {{ $item->sales_name }}
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100 text-center">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600 uppercase tracking-wide">
                                {{ $item->cabang }}
                            </span>
                        </td>

                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-extrabold text-slate-800 bg-emerald-50/10 group-hover:bg-emerald-50/20">
                            {{ number_format($item->total_invoice, 0, ',', '.') }}
                        </td>

                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white border-l border-slate-100 z-10 group-hover:bg-emerald-50/40 transition-colors">
                            <button wire:click="delete('{{ $item->trans_no }}')"
                                onclick="return confirm('Hapus SELURUH faktur {{ $item->trans_no }}? Data item di dalamnya juga akan terhapus.') || event.stopImmediatePropagation()"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-white hover:bg-rose-500 transition-all shadow-sm"
                                title="Hapus Faktur">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 animate-pulse">
                                    <i class="fas fa-receipt text-4xl text-slate-200"></i>
                                </div>
                                <h3 class="text-slate-800 font-bold text-lg">Tidak ada data penjualan</h3>
                                <p class="text-slate-400 text-sm mt-1 mb-6 max-w-xs mx-auto">
                                    Belum ada transaksi yang sesuai dengan filter atau pencarian Anda.
                                </p>
                                <button wire:click="resetFilter"
                                    class="text-emerald-600 font-bold hover:underline text-sm flex items-center gap-2">
                                    <i class="fas fa-sync-alt"></i> Reset Filter
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $penjualans->links() }}
        </div>
    </div>

    @if($isDetailOpen)
    <div class="fixed inset-0 z-[70] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" wire:click="closeDetail">
            </div>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full border border-white/20 scale-100">

                <div
                    class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i
                                    class="fas fa-receipt"></i></span>
                            Detail Faktur
                        </h3>
                        <p class="text-slate-500 text-xs mt-1 ml-10 font-mono">No: {{ $selectedFaktur }}</p>
                    </div>
                    <button wire:click="closeDetail"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="px-6 py-6 bg-white max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 border-r border-slate-100">Kode Item / SKU</th>
                                <th class="px-4 py-3 border-r border-slate-100">Nama Produk</th>
                                <th class="px-4 py-3 text-right border-r border-slate-100">Qty</th>
                                <th class="px-4 py-3 text-right border-r border-slate-100">Harga Satuan</th>
                                <th class="px-4 py-3 text-right border-r border-slate-100">Diskon</th>
                                <th class="px-4 py-3 text-right">Total Net</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($detailItems as $d)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-2.5 font-mono text-slate-500 border-r border-slate-100">
                                    {{ $d->sku ?? $d->kode_item ?? '-' }}</td>
                                <td class="px-4 py-2.5 font-bold text-slate-700 border-r border-slate-100">
                                    {{ $d->nama_item ?? 'Item Tanpa Nama' }}</td>
                                <td class="px-4 py-2.5 text-right border-r border-slate-100">
                                    {{ number_format($d->qty, 0, ',', '.') }}</td>
                                <td class="px-4 py-2.5 text-right border-r border-slate-100">
                                    {{ number_format($d->nilai_jual_net, 0, ',', '.') }}</td>
                                <td class="px-4 py-2.5 text-right border-r border-slate-100 text-rose-600">
                                    {{ number_format($d->total_diskon, 0, ',', '.') }}</td>
                                <td class="px-4 py-2.5 text-right font-bold text-emerald-600 bg-emerald-50/20">
                                    {{ number_format($d->total_grand, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50 border-t border-slate-200 font-bold">
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-right text-slate-600 uppercase tracking-wide">
                                    Total Faktur</td>
                                <td class="px-4 py-3 text-right text-emerald-700 text-sm bg-emerald-100/50">
                                    Rp {{ number_format($detailItems->sum('total_grand'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="bg-slate-50 px-6 py-4 flex justify-end border-t border-slate-200 rounded-b-2xl">
                    <button wire:click="closeDetail"
                        class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-100 transition shadow-sm">
                        Tutup Detail
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isImportOpen)
    @include('livewire.partials.import-modal', [
    'title' => 'Import Transaksi Penjualan',
    'color' => 'emerald'
    ])
    @endif

</div>