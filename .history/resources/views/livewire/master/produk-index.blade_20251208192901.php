<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Master Data Produk</h2>
            <p class="text-sm text-slate-500">Kelola katalog barang, harga, dan stok gudang.</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="openImportModal"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-excel mr-2"></i> Import Excel
            </button>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">

        <div class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-400"
                    placeholder="Cari Nama Produk, SKU, atau C-Code...">
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full border-slate-200 rounded-lg text-xs focus:ring-indigo-500 text-slate-700">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Supplier</label>
                <select wire:model.live="filterSupplier"
                    class="w-full border-slate-200 rounded-lg text-xs focus:ring-indigo-500 text-slate-700">
                    <option value="">Semua Supplier</option>
                    @foreach($optSupplier as $s) <option value="{{ $s }}">{{Str::limit($s, 15)}}</option> @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Kategori</label>
                <select wire:model.live="filterKategori"
                    class="w-full border-slate-200 rounded-lg text-xs focus:ring-indigo-500 text-slate-700">
                    <option value="">Semua Kategori</option>
                    @foreach($optKategori as $k) <option value="{{ $k }}">{{ $k }}</option> @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Divisi</label>
                <select wire:model.live="filterDivisi"
                    class="w-full border-slate-200 rounded-lg text-xs focus:ring-indigo-500 text-slate-700">
                    <option value="">Semua Divisi</option>
                    @foreach($optDivisi as $d) <option value="{{ $d }}">{{ $d }}</option> @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Status Stok</label>
                <select wire:model.live="filterStok"
                    class="w-full border-slate-200 rounded-lg text-xs focus:ring-indigo-500 text-slate-700">
                    <option value="">Semua Status</option>
                    <option value="ready">✅ Ready Stock (>0)</option>
                    <option value="empty">❌ Kosong (0)</option>
                </select>
            </div>

            <div class="flex items-end">
                <button wire:click="resetFilter"
                    class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition-colors flex items-center justify-center gap-2 border border-slate-200"
                    title="Reset Filter">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 flex flex-col h-[70vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap min-w-max w-full">

                <thead
                    class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-4 py-3 border-r bg-slate-50">Nama Produk</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-center">Cabang</th>
                        <th class="px-4 py-3 border-r bg-slate-50">SKU / Kode</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Kategori</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Supplier</th>
                        <th class="px-4 py-3 border-r bg-blue-50 text-blue-700 text-right">Stok</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-center">Satuan</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Harga Beli</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Harga Jual</th>
                        <th class="px-4 py-3 bg-slate-50 text-center sticky right-0 z-20 border-l w-20">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($produks as $item)
                    <tr
                        class="hover:bg-indigo-50/50 transition-colors group {{ $item->stok <= 0 ? 'bg-red-50/30' : '' }}">

                        <td class="px-4 py-2 border-r font-bold text-slate-700 truncate max-w-[250px]"
                            title="{{ $item->name_item }}">
                            {{ $item->name_item }}
                            @if($item->is_duplicate) <span
                                class="ml-1 text-[9px] bg-red-100 text-red-600 px-1 rounded">DUP</span> @endif
                        </td>

                        <td class="px-4 py-2 border-r text-center text-slate-500">{{ $item->cabang }}</td>
                        <td class="px-4 py-2 border-r font-mono text-indigo-600">{{ $item->sku }}</td>
                        <td class="px-4 py-2 border-r text-slate-600">{{ $item->kategori }}</td>
                        <td class="px-4 py-2 border-r text-purple-600 truncate max-w-[150px]">{{ $item->supplier }}</td>

                        <td
                            class="px-4 py-2 border-r text-right font-bold {{ $item->stok <= 0 ? 'text-red-600' : 'text-emerald-600' }}">
                            {{ number_format((float)$item->stok, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 border-r text-center text-slate-500 text-[10px] uppercase">{{ $item->oum }}
                        </td>

                        <td class="px-4 py-2 border-r text-right text-slate-500">
                            {{ number_format((float)$item->buy, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border-r text-right font-medium text-slate-800">
                            {{ number_format((float)$item->sell, 0, ',', '.') }}</td>

                        <td
                            class="px-4 py-2 text-center sticky right-0 bg-white border-l z-10 group-hover:bg-indigo-50/50">
                            <button wire:click="delete({{ $item->id }})"
                                @click="$dispatch('confirm-delete', { method: 'deleteProduk', id: {{ $item->id }} })"
                                class="text-slate-300 hover:text-red-500 transition-colors" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-16 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="fas fa-box-open text-2xl text-slate-300"></i>
                                </div>
                                <p class="font-medium text-slate-600">Tidak ada produk ditemukan</p>
                                <p class="text-xs mt-1">Coba ubah filter atau kata kunci pencarian.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t bg-slate-50">
            {{ $produks->links() }}
        </div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                wire:click="closeImportModal"></div>
            <div
                class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Import Data Produk</h3>
                    <div
                        class="flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 cursor-pointer relative">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-slate-400"></i>
                            <p class="text-sm text-slate-500 mt-2">Klik untuk pilih file Excel</p>
                            <input wire:model="file" type="file"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                    </div>
                    <div wire:loading wire:target="file" class="text-center mt-2 text-xs text-indigo-600 font-bold">
                        Mengupload...</div>
                    @if($file) <p class="mt-2 text-center text-sm font-bold text-emerald-600">
                        {{ $file->getClientOriginalName() }}</p> @endif
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700">Import
                        Sekarang</button>
                    <button wire:click="closeImportModal"
                        class="w-full sm:w-auto bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-50">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>