<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Transaksi Penjualan</h2>
            <p class="text-sm text-slate-500 mt-1">Rekapitulasi faktur penjualan harian.</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-excel mr-2"></i> Import Sales
            </button>
        </div>
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
                        class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 placeholder-slate-400 transition-all"
                        placeholder="No Faktur / Nama Toko...">
                </div>
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Periode</label>
                <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl border border-slate-200">
                    <input type="date" wire:model.live="startDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                    <span class="text-slate-300 font-light">|</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                </div>
            </div>

            <div class="w-full md:w-64" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Filter Cabang</label>
                <div class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 py-2.5 px-3.5 rounded-xl text-sm hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        <span class="truncate font-medium">
                            {{ count($filterCabang) > 0 ? count($filterCabang) . ' Dipilih' : 'Semua Cabang' }}
                        </span>
                        <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                    </button>

                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto p-2"
                        style="display: none;">
                        @foreach($optCabang as $cab)
                        <label
                            class="flex items-center px-2 py-2 hover:bg-emerald-50 rounded-lg cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3">
                            <span class="text-sm text-slate-700">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <button wire:click="resetFilter"
                class="w-full md:w-auto px-4 py-2.5 bg-white border border-slate-200 hover:border-rose-300 hover:text-rose-600 text-slate-500 rounded-xl transition-all shadow-sm h-[42px]"
                title="Reset Semua Filter">
                <i class="fas fa-undo"></i>
            </button>

        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[70vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">

                <thead
                    class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4 border-r border-slate-200">Tanggal</th>
                        <th class="px-6 py-4 border-r border-slate-200">No Faktur</th>
                        <th class="px-6 py-4 border-r border-slate-200 min-w-[200px]">Nama Pelanggan</th>
                        <th class="px-6 py-4 border-r border-slate-200">Salesman</th>
                        <th class="px-6 py-4 border-r border-slate-200 text-center">Cabang</th>
                        <th class="px-6 py-4 border-r border-slate-200 text-right bg-emerald-50/50 text-emerald-700">
                            Total (Rp)</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($penjualans as $item)
                    <tr class="hover:bg-emerald-50/30 transition-colors group">

                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}
                        </td>

                        <td
                            class="px-6 py-3 border-r border-slate-100 font-mono text-emerald-700 font-bold group-hover:text-emerald-800 transition-colors">
                            {{ $item->trans_no }}
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[250px]"
                            title="{{ $item->nama_pelanggan }}">
                            {{ $item->nama_pelanggan }}
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">
                            {{ $item->sales_name }}
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100 text-center">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600">
                                {{ $item->cabang }}
                            </span>
                        </td>

                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-bold text-slate-800 bg-emerald-50/10 group-hover:bg-emerald-50/20">
                            {{ number_format($item->total_grand, 0, ',', '.') }}
                        </td>

                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white group-hover:bg-emerald-50/30 transition-colors">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Yakin hapus data penjualan ini?') || event.stopImmediatePropagation()"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-white hover:bg-rose-500 transition-all shadow-sm"
                                title="Hapus Data">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="fas fa-search text-2xl text-slate-300"></i>
                                </div>
                                <p class="font-medium text-slate-600">Tidak ada data ditemukan</p>
                                <p class="text-xs mt-1 text-slate-400">Coba ubah filter tanggal atau pencarian Anda.</p>
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

    @if($isImportOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity"
                wire:click="closeImportModal"></div>
            <div
                class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/20">

                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4 border-b border-white/10">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-file-invoice-dollar"></i> Import Transaksi Penjualan
                    </h3>
                    <p class="text-emerald-100 text-xs mt-0.5">Upload file Excel data faktur penjualan.</p>
                </div>

                <div class="px-6 py-6">
                    <div class="mb-4">
                        <div
                            class="w-full flex justify-center px-6 pt-8 pb-8 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 cursor-pointer relative transition-all group hover:border-emerald-400 bg-slate-50/50">
                            <div class="space-y-2 text-center">
                                <div
                                    class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                                    <i class="fas fa-cloud-upload-alt text-emerald-500 text-xl"></i>
                                </div>
                                <div class="text-sm text-slate-600">
                                    <label for="file-upload-penjualan"
                                        class="relative cursor-pointer rounded-md font-bold text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                        <span>Klik Upload</span>
                                        <input id="file-upload-penjualan" wire:model="file" type="file" class="sr-only">
                                    </label>
                                    <span class="pl-1 font-medium">atau drag file</span>
                                </div>
                                <p class="text-xs text-slate-400">XLSX, CSV (Max 10MB)</p>
                            </div>
                        </div>
                    </div>

                    <div wire:loading wire:target="file" class="w-full text-center py-2">
                        <span class="inline-flex items-center text-xs text-emerald-600 font-bold animate-pulse"><i
                                class="fas fa-spinner fa-spin mr-2"></i> Mengupload File...</span>
                    </div>
                    <div wire:loading wire:target="import" class="w-full text-center py-2">
                        <span class="inline-flex items-center text-xs text-indigo-600 font-bold animate-pulse"><i
                                class="fas fa-cog fa-spin mr-2"></i> Memproses Data...</span>
                    </div>

                    @if($file)
                    <div
                        class="p-3 bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs rounded-lg flex items-center gap-2 mb-4">
                        <i class="fas fa-file-excel text-lg"></i> {{ $file->getClientOriginalName() }}
                    </div>
                    @endif
                </div>

                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-200">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full sm:w-auto bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/30 disabled:opacity-50 disabled:cursor-not-allowed">Import
                        Sekarang</button>
                    <button wire:click="closeImportModal"
                        class="w-full sm:w-auto bg-white border border-slate-300 text-slate-700 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>