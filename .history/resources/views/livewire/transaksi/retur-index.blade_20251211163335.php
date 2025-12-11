<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-white/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Retur Penjualan</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar pengembalian barang dari pelanggan.</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-8 w-full border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-rose-500 py-2 shadow-sm placeholder-slate-400 transition-all"
                        placeholder="No Retur / Pelanggan...">
                </div>

                <div
                    class="flex items-center gap-1 bg-white border border-slate-200 rounded-lg px-2 py-1 shadow-sm h-[34px]">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                    <span class="text-slate-300 text-[10px]">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                </div>

                <div class="w-full sm:w-36">
                    <select wire:model.live="filterCabang"
                        class="w-full border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-rose-500 py-2 shadow-sm cursor-pointer bg-white hover:border-rose-300 transition-colors">
                        <option value="">Semua Cabang</option>
                        @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
                </div>

                <div class="hidden sm:block h-6 w-px bg-slate-300 mx-1"></div>

                <button wire:click="resetFilter"
                    class="px-3 py-2 bg-white border border-rose-200 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-50 shadow-sm transition-all flex items-center gap-2"
                    title="Reset Filter">
                    <i class="fas fa-undo"></i>
                </button>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-gradient-to-r from-rose-600 to-pink-600 text-white rounded-lg text-xs font-bold hover:from-rose-700 hover:to-pink-700 shadow-md shadow-rose-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fas fa-file-import"></i> <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading class="text-rose-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div>
            </div>
        </div>
    </div>

    @if(isset($summary))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
            class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl p-5 text-white shadow-lg shadow-rose-500/20 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-rose-100 text-xs font-bold uppercase tracking-wider mb-1">Total Nilai Retur</p>
                <h3 class="text-2xl font-extrabold tracking-tight">Rp
                    {{ number_format($summary['total_nilai'], 0, ',', '.') }}</h3>
            </div>
            <div class="absolute right-4 top-4 text-white/20 group-hover:scale-110 transition-transform duration-500"><i
                    class="fas fa-undo-alt text-6xl rotate-12"></i></div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-rose-300 transition-colors">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Faktur Retur</p>
                <h3 class="text-2xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_faktur'], 0, ',', '.') }}</h3>
                <span
                    class="text-[10px] text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-full mt-1 inline-block">Transaksi</span>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i class="fas fa-file-invoice text-xl"></i></div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-orange-300 transition-colors">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Item Diretur</p>
                <h3 class="text-2xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_items'], 0, ',', '.') }}</h3>
                <span
                    class="text-[10px] text-orange-600 font-bold bg-orange-50 px-2 py-0.5 rounded-full mt-1 inline-block">Barang
                    / SKU</span>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:bg-orange-500 group-hover:text-white transition-all">
                <i class="fas fa-boxes text-xl"></i></div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[65vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Tanggal</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">No Retur</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 min-w-[200px]">
                            Pelanggan (Klik Detail)</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Salesman</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 text-center">
                            Cabang</th>
                        <th
                            class="px-6 py-4 font-bold text-rose-700 uppercase border-r border-slate-200 text-right bg-rose-50/50">
                            Total Retur</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase text-center bg-slate-50 sticky right-0 z-20 border-l border-slate-200">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($returs as $item)
                    <tr class="hover:bg-rose-50/20 transition-colors group">
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600 font-medium">
                            {{ date('d/m/Y', strtotime($item->tgl_retur)) }}</td>
                        <td
                            class="px-6 py-3 border-r border-slate-100 font-mono text-rose-600 font-bold group-hover:text-rose-700">
                            {{ $item->no_retur }}</td>

                        <td class="px-6 py-3 border-r border-slate-100">
                            <button wire:click="openDetail('{{ $item->no_retur }}')"
                                class="text-left font-bold text-slate-700 hover:text-rose-600 transition-colors flex items-center gap-2 w-full group/link relative">
                                <span class="truncate max-w-[250px]">{{ $item->nama_pelanggan }}</span>
                                <i
                                    class="fas fa-external-link-alt text-[10px] opacity-0 group-hover/link:opacity-100 text-slate-400 transition-opacity"></i>
                                <div wire:loading wire:target="openDetail('{{ $item->no_retur }}')"
                                    class="absolute right-0 text-rose-500"><i class="fas fa-spinner fa-spin"></i></div>
                            </button>
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">{{ $item->sales_name }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-center">
                            <span
                                class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600">{{ $item->cabang }}</span>
                        </td>
                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-extrabold text-slate-800 bg-rose-50/10 group-hover:bg-rose-50/20">
                            {{ number_format($item->total_retur, 0, ',', '.') }}
                        </td>
                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white group-hover:bg-rose-50/20 transition-colors">
                            <button wire:click="delete('{{ $item->no_retur }}')"
                                onclick="return confirm('Hapus Faktur Retur ini?') || event.stopImmediatePropagation()"
                                class="text-slate-300 hover:text-red-500 transition-colors"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7"
                            class="px-6 py-24 text-center text-slate-400 flex flex-col items-center justify-center">
                            <i class="fas fa-box-open text-4xl mb-2 text-slate-200"></i>
                            <p>Tidak ada data retur.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $returs->links() }}</div>
    </div>

    @if($isDetailOpen)
    <div class="fixed inset-0 z-[70] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" wire:click="closeDetail">
            </div>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full border border-white/20 relative">
                <div wire:loading.flex wire:target="closeDetail"
                    class="absolute inset-0 bg-white/50 z-50 items-center justify-center backdrop-blur-sm">
                    <span class="text-rose-600 font-bold"><i class="fas fa-spinner fa-spin mr-2"></i> Menutup...</span>
                </div>
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2"><i
                                class="fas fa-undo text-rose-600"></i> Rincian Retur</h3>
                        <p class="text-slate-500 text-xs mt-0.5 font-mono">No: {{ $selectedRetur }}</p>
                    </div>
                    <button wire:click="closeDetail"
                        class="w-8 h-8 rounded-full flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="px-6 py-6 bg-white max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 border-r border-slate-100">Kode Item</th>
                                <th class="px-4 py-3 border-r border-slate-100">Nama Barang</th>
                                <th class="px-4 py-3 text-right border-r border-slate-100">Qty</th>
                                <th class="px-4 py-3 text-right">Nilai Retur</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($detailItems as $d)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-2.5 font-mono text-rose-600 border-r border-slate-100">
                                    {{ $d->kode_item ?: '-' }}</td>
                                <td class="px-4 py-2.5 font-bold text-slate-700 border-r border-slate-100">
                                    {{ $d->nama_item ?: 'Tanpa Nama' }}</td>
                                <td class="px-4 py-2.5 text-right border-r border-slate-100">
                                    {{ number_format($d->qty, 0, ',', '.') }} {{ $d->satuan }}</td>
                                <td class="px-4 py-2.5 text-right font-bold text-rose-600">
                                    {{ number_format($d->total_grand, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50 border-t border-slate-200 font-bold sticky bottom-0">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-slate-600 uppercase">Total Retur</td>
                                <td class="px-4 py-3 text-right text-rose-700 bg-rose-50">Rp
                                    {{ number_format($detailItems->sum('total_grand'), 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="bg-slate-50 px-6 py-3 flex justify-end border-t border-slate-200">
                    <button wire:click="closeDetail"
                        class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-600 text-xs font-bold hover:bg-slate-50">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Import Retur', 'color' => 'rose'])
    @endif

</div>