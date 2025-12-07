<div class="space-y-6">

    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">

        <div class="w-full md:w-1/2">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.500ms="search" type="text"
                    class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400 transition-colors shadow-sm"
                    placeholder="Cari Nama Produk, SKU, atau Kode...">
            </div>
        </div>

        <div class="flex gap-3 w-full md:w-auto justify-end">
            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                <i class="fas fa-file-excel mr-2"></i> Import Excel
            </button>
            <button wire:click="create"
                class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <i class="fas fa-plus mr-2"></i> Input
            </button>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center mb-3 gap-2 text-gray-700">
            <i class="fas fa-filter text-indigo-600"></i>
            <h3 class="text-sm font-bold uppercase tracking-wide">Filter Data</h3>

            @if($filterCabang || $filterKategori || $filterDivisi || $filterSupplier || $filterStok)
            <button
                wire:click="$set('filterCabang', '');$set('filterKategori', '');$set('filterDivisi', '');$set('filterSupplier', '');$set('filterStok', '')"
                class="ml-auto text-xs text-red-500 hover:text-red-700 underline cursor-pointer">
                Reset Filter
            </button>
            @endif
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full border-gray-200 rounded-lg text-xs focus:ring-indigo-500 focus:border-indigo-500 py-2">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $cab)
                    <option value="{{ $cab }}">{{ $cab }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                <select wire:model.live="filterKategori"
                    class="w-full border-gray-200 rounded-lg text-xs focus:ring-indigo-500 focus:border-indigo-500 py-2">
                    <option value="">Semua Kategori</option>
                    @foreach($optKategori as $kat)
                    <option value="{{ $kat }}">{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Divisi</label>
                <select wire:model.live="filterDivisi"
                    class="w-full border-gray-200 rounded-lg text-xs focus:ring-indigo-500 focus:border-indigo-500 py-2">
                    <option value="">Semua Divisi</option>
                    @foreach($optDivisi as $div)
                    <option value="{{ $div }}">{{ $div }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Supplier</label>
                <select wire:model.live="filterSupplier"
                    class="w-full border-gray-200 rounded-lg text-xs focus:ring-indigo-500 focus:border-indigo-500 py-2">
                    <option value="">Semua Supplier</option>
                    @foreach($optSupplier as $sup)
                    <option value="{{ $sup }}">{{ $sup }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status Stok</label>
                <select wire:model.live="filterStok"
                    class="w-full border-gray-200 rounded-lg text-xs focus:ring-indigo-500 focus:border-indigo-500 py-2">
                    <option value="">Semua Status</option>
                    <option value="ready" class="text-green-600 font-bold">Ready Stock (> 0)</option>
                    <option value="empty" class="text-red-600 font-bold">Stok Kosong (0)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[80vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="text-xs text-gray-600 uppercase bg-gray-100 sticky top-0 z-20 shadow-sm">
                    <tr>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[50px] text-center sticky left-0 z-30">
                            No</th>
                        <th
                            class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-center sticky left-[50px] z-30 font-bold">
                            Aksi</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Cabang</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">C-Code</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">SKU</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[150px]">Kategori</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[250px]">Nama Item</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[90px]">Expired</th>

                        <th
                            class="px-3 py-3 border-b border-r bg-blue-50 text-blue-900 min-w-[80px] text-center font-bold">
                            STOK</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[60px]">OUM</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Good</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Good Konv</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Ktn</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Good Amt</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right text-red-600">Bad</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Bad Konv</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right text-red-600">Bad Ktn</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right text-red-600">Bad Amt</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Buy Price</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Disc</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Total</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Margin</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[150px]">Supplier</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Divisi</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Unique ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($produks as $index => $item)
                    <tr
                        class="transition-colors group {{ $item->is_duplicate ? 'bg-yellow-50 hover:bg-yellow-100' : 'hover:bg-indigo-50' }}">
                        <td
                            class="px-3 py-2 border-r text-center text-gray-500 bg-white sticky left-0 z-10 group-hover:bg-inherit">
                            {{ $produks->firstItem() + $index }}
                        </td>
                        <td
                            class="px-3 py-2 border-r text-center bg-white sticky left-[50px] z-10 shadow-sm group-hover:bg-inherit">
                            <div class="flex justify-center gap-2">
                                <button wire:click="edit({{ $item->id }})" class="text-blue-600 hover:text-blue-800"><i
                                        class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                    class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </div>
                            @if($item->is_duplicate) <div class="text-[9px] text-red-600 font-bold mt-0.5">DUPLIKAT
                            </div> @endif
                        </td>

                        <td class="px-3 py-2 border-r text-indigo-600 font-medium">{{ $item->cabang }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->ccode }}</td>
                        <td class="px-3 py-2 border-r font-mono">{{ $item->sku }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->kategori }}</td>
                        <td class="px-3 py-2 border-r font-medium text-gray-800 truncate max-w-[250px]"
                            title="{{ $item->name_item }}">{{ $item->name_item }}</td>
                        <td class="px-3 py-2 border-r text-red-500">
                            {{ $item->expired_date ? \Carbon\Carbon::parse($item->expired_date)->format('d-m-Y') : '-' }}
                        </td>

                        <td
                            class="px-3 py-2 border-r text-center font-bold {{ (float)str_replace(',', '', $item->stok) > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $item->stok }}
                        </td>
                        <td class="px-3 py-2 border-r text-center">{{ $item->oum }}</td>

                        <td class="px-3 py-2 border-r text-right">{{ $item->good }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->good_konversi }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->ktn }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->good_amount }}</td>

                        <td class="px-3 py-2 border-r text-right text-red-500">{{ $item->bad }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->bad_konversi }}</td>
                        <td class="px-3 py-2 border-r text-right text-red-500">{{ $item->bad_ktn }}</td>
                        <td class="px-3 py-2 border-r text-right text-red-500">{{ $item->bad_amount }}</td>

                        <td class="px-3 py-2 border-r text-right">{{ $item->buy }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->buy_disc }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->total }}</td>
                        <td
                            class="px-3 py-2 border-r text-right font-bold {{ str_contains($item->margin, '-') ? 'text-red-600' : 'text-green-600' }}">
                            {{ $item->margin }}</td>

                        <td class="px-3 py-2 border-r truncate max-w-[150px]" title="{{ $item->supplier }}">
                            {{ $item->supplier }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->divisi }}</td>
                        <td class="px-3 py-2 border-r text-[10px] text-gray-400">{{ $item->unique_id }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="30" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <i class="fas fa-search fa-3x mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Data tidak ditemukan</p>
                            <p class="text-sm">Coba ubah filter atau kata kunci pencarian.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $produks->links() }}
        </div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                wire:click="closeImportModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">Import Master Produk</h3>
                    <div
                        class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 relative cursor-pointer">
                        <label for="file-upload-prod-{{ $iteration }}"
                            class="relative cursor-pointer font-medium text-emerald-600 hover:text-emerald-500">
                            <span>Pilih File</span>
                            <input id="file-upload-prod-{{ $iteration }}" wire:model="file" type="file" class="sr-only">
                        </label>
                    </div>
                    <div wire:loading wire:target="file" class="w-full mt-2 text-center text-sm text-emerald-600">
                        Mengupload...</div>
                    @error('file') <div class="mt-2 text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                        <span wire:loading.remove wire:target="import">Import</span>
                        <span wire:loading wire:target="import">Memproses...</span>
                    </button>
                    <button wire:click="closeImportModal"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isInputOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm"
                wire:click="closeInputModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-900">Input Manual</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div><label class="block text-sm text-gray-700">SKU</label><input type="text" wire:model="kode_item"
                            class="w-full rounded border-gray-300"></div>
                    <div><label class="block text-sm text-gray-700">Nama</label><input type="text"
                            wire:model="nama_item" class="w-full rounded border-gray-300"></div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="store"
                        class="w-full inline-flex justify-center rounded bg-indigo-600 text-white px-4 py-2 sm:ml-3 sm:w-auto">Simpan</button>
                    <button wire:click="closeInputModal"
                        class="mt-3 w-full inline-flex justify-center rounded border border-gray-300 bg-white px-4 py-2 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>