<div class="space-y-6">

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row justify-between items-end gap-4">

            <div class="w-full md:w-3/4 grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="relative md:col-span-1">
                    <input wire:model.live.debounce.500ms="search" type="text"
                        class="pl-9 pr-4 py-2 w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400"
                        placeholder="Cari SKU, Nama Item...">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                </div>

                <div>
                    <select wire:model.live="filterCabang"
                        class="w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 py-2 text-gray-600">
                        <option value="">Semua Cabang</option>
                        @foreach($optCabang as $cab) <option value="{{ $cab }}">{{ $cab }}</option> @endforeach
                    </select>
                </div>

                <div>
                    <select wire:model.live="filterKategori"
                        class="w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 py-2 text-gray-600">
                        <option value="">Semua Kategori</option>
                        @foreach($optKategori as $kat) <option value="{{ $kat }}">{{ $kat }}</option> @endforeach
                    </select>
                </div>

                <div>
                    <select wire:model.live="filterDivisi"
                        class="w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 py-2 text-gray-600">
                        <option value="">Semua Divisi</option>
                        @foreach($optDivisi as $div) <option value="{{ $div }}">{{ $div }}</option> @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-2">
                <button wire:click="openImportModal"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all">
                    <i class="fas fa-file-excel mr-2"></i> Import
                </button>
                <button wire:click="create"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all">
                    <i class="fas fa-plus mr-2"></i> Input
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="text-gray-500 uppercase bg-gray-50 font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 min-w-[50px] text-center border-r">No</th>
                        <th class="px-4 py-3 min-w-[80px] text-center border-r">Aksi</th>

                        <th class="px-4 py-3 min-w-[120px] border-r">Cabang</th>
                        <th class="px-4 py-3 min-w-[100px] border-r">SKU</th>
                        <th class="px-4 py-3 min-w-[100px] border-r">C-Code</th>
                        <th class="px-4 py-3 min-w-[150px] border-r">Kategori</th>
                        <th class="px-4 py-3 min-w-[250px] border-r">Nama Produk</th>
                        <th class="px-4 py-3 min-w-[80px] text-center bg-blue-50 text-blue-800 border-r">Stok</th>
                        <th class="px-4 py-3 min-w-[60px] text-center border-r">Satuan</th>

                        <th class="px-4 py-3 border-r text-right">Harga Beli</th>
                        <th class="px-4 py-3 border-r text-right">Avg Price</th>
                        <th class="px-4 py-3 border-r">Expired</th>
                        <th class="px-4 py-3 border-r text-right">Good</th>
                        <th class="px-4 py-3 border-r text-right text-red-500">Bad</th>
                        <th class="px-4 py-3 border-r text-right">Min Qty</th>
                        <th class="px-4 py-3 border-r text-right">Re-Order</th>
                        <th class="px-4 py-3 border-r">Supplier</th>
                        <th class="px-4 py-3 border-r">Divisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($produks as $index => $item)
                    <tr class="hover:bg-indigo-50 transition-colors {{ $item->is_duplicate ? 'bg-yellow-50' : '' }}">
                        <td class="px-4 py-2 text-center text-gray-500 border-r">
                            {{ $produks->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-2 text-center border-r">
                            <div class="flex justify-center gap-3">
                                <button wire:click="edit({{ $item->id }})"
                                    class="text-blue-600 hover:text-blue-800 transition"><i
                                        class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                    class="text-red-500 hover:text-red-700 transition"><i
                                        class="fas fa-trash-alt"></i></button>
                            </div>
                            @if($item->is_duplicate)
                            <span class="block text-[9px] text-red-600 font-bold mt-1">DUPLIKAT</span>
                            @endif
                        </td>

                        <td class="px-4 py-2 font-medium text-indigo-600 border-r">{{ $item->cabang }}</td>
                        <td class="px-4 py-2 font-mono border-r">{{ $item->sku }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->ccode }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->kategori }}</td>
                        <td class="px-4 py-2 font-bold text-gray-700 border-r truncate max-w-xs"
                            title="{{ $item->name_item }}">{{ $item->name_item }}</td>
                        <td
                            class="px-4 py-2 text-center font-bold border-r {{ (float)$item->stok > 0 ? 'text-green-600 bg-green-50' : 'text-red-500 bg-red-50' }}">
                            {{ $item->stok }}
                        </td>
                        <td class="px-4 py-2 text-center text-gray-500 border-r">{{ $item->oum }}</td>

                        <td class="px-4 py-2 text-right border-r">{{ $item->buy }}</td>
                        <td class="px-4 py-2 text-right border-r">{{ $item->avg }}</td>
                        <td class="px-4 py-2 text-center border-r">
                            {{ $item->expired_date ? date('d-m-Y', strtotime($item->expired_date)) : '-' }}</td>
                        <td class="px-4 py-2 text-right border-r">{{ $item->good }}</td>
                        <td class="px-4 py-2 text-right text-red-500 border-r">{{ $item->bad }}</td>
                        <td class="px-4 py-2 text-right border-r">{{ $item->min }}</td>
                        <td class="px-4 py-2 text-right border-r">{{ $item->re_qty }}</td>
                        <td class="px-4 py-2 border-r truncate max-w-[150px]">{{ $item->supplier }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->divisi }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="18" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-box-open fa-3x mb-3 text-gray-200"></i>
                            <p class="text-sm font-medium">Belum ada data produk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $produks->links() }}
        </div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeImportModal"></div>
            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">Import Master Produk</h3>
                    <div
                        class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 cursor-pointer relative">
                        <label class="relative cursor-pointer font-medium text-indigo-600 hover:text-indigo-500">
                            <span>Pilih File Excel</span>
                            <input wire:model="file" type="file" class="sr-only">
                        </label>
                    </div>
                    <div wire:loading wire:target="file" class="w-full mt-2 text-center text-xs text-indigo-600">
                        Uploading...</div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 disabled:opacity-50">Import</button>
                    <button wire:click="closeImportModal"
                        class="mt-2 sm:mt-0 w-full sm:w-auto border border-gray-300 bg-white px-4 py-2 rounded-lg hover:bg-gray-50">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>