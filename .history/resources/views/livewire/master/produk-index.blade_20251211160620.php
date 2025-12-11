<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-white/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Master Produk</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Kelola katalog barang, stok, & harga.</p>
                </div>
                <div
                    class="hidden md:flex px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold border border-emerald-100 items-center gap-2">
                    <i class="fas fa-box"></i> {{ $produks->total() }} SKU
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-8 w-full border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-emerald-500 py-2 shadow-sm placeholder-slate-400 transition-all"
                        placeholder="Cari Nama / SKU...">
                </div>

                <div class="relative w-full sm:w-36" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:border-emerald-300 transition-all">
                        <span
                            class="truncate">{{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang).' Dipilih' }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-48 overflow-y-auto"
                        style="display: none;">
                        @foreach($optCabang as $c)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-emerald-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 text-emerald-600 mr-2 h-3 w-3">
                            <span class="text-xs text-slate-600">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="relative w-full sm:w-36" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:border-emerald-300 transition-all">
                        <span
                            class="truncate">{{ empty($filterSupplier) ? 'Semua Supplier' : count($filterSupplier).' Dipilih' }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-48 overflow-y-auto"
                        style="display: none;">
                        @foreach($optSupplier as $s)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-emerald-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $s }}" wire:model.live="filterSupplier"
                                class="rounded border-slate-300 text-emerald-600 mr-2 h-3 w-3">
                            <span class="text-xs text-slate-600 truncate">{{ $s }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg text-xs font-bold hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading class="text-emerald-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[70vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap min-w-max w-full">
                <thead
                    class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-20 shadow-sm">
                    <tr>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Cabang</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">C-Code</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">SKU</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Kategori</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-100 min-w-[250px] text-slate-700">Nama
                            Produk</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Expired Date</th>
                        <th
                            class="px-4 py-3.5 border-r border-blue-100 bg-blue-50 text-blue-700 text-right font-extrabold">
                            Stok</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-center">OUM</th>

                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Good</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Bad</th>

                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Avg 3M (Val)</th>
                        <th class="px-4 py-3.5 border-r border-yellow-200 bg-yellow-50 text-right text-yellow-700">
                            Margin</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Supplier</th>
                        <th
                            class="px-4 py-3.5 text-center bg-slate-100 border-l border-slate-200 sticky right-0 z-30 w-24 font-bold text-slate-700">
                            Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($produks as $item)
                    <tr
                        class="hover:bg-emerald-50/20 transition-colors {{ $item->is_duplicate ? 'bg-red-50/50' : '' }} group">

                        <td class="px-4 py-2.5 border-r border-slate-100 text-indigo-600 font-medium">
                            {{ $item->cabang }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 font-mono text-slate-500">{{ $item->ccode }}
                        </td>
                        <td class="px-4 py-2.5 border-r border-slate-100 font-mono font-bold text-slate-700">
                            {{ $item->sku }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-slate-600">{{ $item->kategori }}</td>

                        <td class="px-4 py-2.5 border-r border-slate-100 font-bold text-slate-700 bg-slate-50/30 truncate max-w-[300px]"
                            title="{{ $item->name_item }}">
                            {{ $item->name_item }}
                            @if($item->is_duplicate)
                            <span
                                class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-red-100 text-red-600 border border-red-200">DUP</span>
                            @endif
                        </td>

                        <td class="px-4 py-2.5 border-r border-slate-100 text-slate-600">{{ $item->expired_date }}</td>

                        <td
                            class="px-4 py-2.5 border-r border-slate-100 text-right font-bold {{ $item->stok <= 0 ? 'text-rose-500 bg-rose-50/30' : 'text-emerald-600 bg-emerald-50/30' }}">
                            {{ number_format((float)$item->stok, 0, ',', '.') }}
                        </td>

                        <td
                            class="px-4 py-2.5 border-r border-slate-100 text-center text-[10px] uppercase text-slate-400 font-bold">
                            {{ $item->oum }}</td>

                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->good }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right text-rose-600 font-medium">
                            {{ $item->bad }}</td>

                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->avg_3m_in_value, 0, ',', '.') }}</td>

                        <td
                            class="px-4 py-2.5 border-r border-slate-100 text-right font-bold text-emerald-600 bg-emerald-50/20">
                            {{ number_format((float)$item->margin, 0, ',', '.') }}
                        </td>

                        <td
                            class="px-4 py-2.5 border-r border-slate-100 text-purple-600 font-medium truncate max-w-[150px]">
                            {{ $item->supplier }}</td>

                        <td
                            class="px-4 py-2.5 text-center sticky right-0 bg-white border-l border-slate-100 z-10 group-hover:bg-emerald-50/20">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Yakin ingin menghapus produk ini?') || event.stopImmediatePropagation()"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-rose-500 transition-all shadow-sm"
                                title="Hapus Data">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-box-open text-4xl text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg">Data Produk Kosong</h3>
                                <p class="text-slate-500 text-sm mt-1 mb-6">Belum ada data produk yang diimport atau
                                    sesuai filter.</p>
                                <button wire:click="openImportModal"
                                    class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/30">
                                    Import Sekarang
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $produks->links() }}
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
                        <i class="fas fa-file-excel"></i> Import Data Produk
                    </h3>
                    <p class="text-emerald-100 text-xs mt-0.5">Upload file Excel untuk memperbarui katalog.</p>
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
                                    <label for="file-upload-produk"
                                        class="relative cursor-pointer rounded-md font-bold text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                        <span>Klik Upload</span>
                                        <input id="file-upload-produk" wire:model="file" type="file" class="sr-only">
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