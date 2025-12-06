<div class="space-y-6">

    <div
        class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">

        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400 transition-colors"
                placeholder="Cari SKU atau Nama Produk...">
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
            <button wire:click="import" wire:loading.attr="disabled" type="button"
                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-wait">

                <span wire:loading.remove wire:target="import">Mulai Import</span>

                <span wire:loading wire:target="import" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Sedang Memproses...
                </span>
            </button>

            <button wire:click="closeImportModal" ...>Batal</button>
        </div>

        <button wire:click="create"
            class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            <i class="fas fa-plus mr-2"></i> Input
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[80vh]">
    <div class="flex-1 overflow-auto">
        <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
            <thead class="text-xs text-gray-600 uppercase bg-gray-100 sticky top-0 z-20 shadow-sm">
                <tr>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[50px] text-center">No</th>
                    <th
                        class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-center sticky left-0 z-30 font-bold">
                        Aksi</th>

                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Cabang</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">C-Code</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">SKU</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Kategori</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[250px]">Nama Item</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[90px]">Expired Date</th>

                    <th class="px-3 py-3 border-b border-r bg-blue-50 text-blue-900 min-w-[80px] text-center font-bold">
                        STOK</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[60px]">OUM</th>

                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">Good</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Good Konv</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">Ktn</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Good Amount</th>

                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">Avg 3M (OUM)</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">Avg 3M (Ktn)</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Avg 3M (Rp)</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Not Move 3M</th>

                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right text-red-600">Bad
                    </th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Bad Konv</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">Bad Ktn</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Bad Amount</th>

                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">WRH 1</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">WRH 1 Konv</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">WRH 1 Amt</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">WRH 2</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">WRH 2 Konv</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">WRH 2 Amt</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">WRH 3</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">WRH 3 Konv</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">WRH 3 Amt</th>

                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Good Storage</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">Sell/Week</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Blank Field</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Empty Field</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">Min</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">Re Qty</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[90px]">Exp Info</th>

                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Buy (H. Beli)</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">Buy Disc</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Buy In Ktn</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Avg</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Total</th>

                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">UP</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Fix</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">PPN</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Fix Exc PPN</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Margin</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">% Margin</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Order No</th>

                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">Supplier</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Mother SKU</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">Last Supplier</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Divisi</th>
                    <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[150px]">Unique ID</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($produks as $index => $item)
                <tr class="hover:bg-indigo-50 transition-colors">
                    <td class="px-3 py-2 border-r text-center text-gray-500">{{ $produks->firstItem() + $index }}
                    </td>
                    <td class="px-3 py-2 border-r text-center bg-white sticky left-0 z-10 shadow-sm">
                        <div class="flex justify-center gap-2">
                            <button wire:click="edit({{ $item->id }})" class="text-blue-600 hover:text-blue-800"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus produk ini?') || event.stopImmediatePropagation()"
                                class="text-red-500 hover:text-red-700" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>

                    <td class="px-3 py-2 border-r text-indigo-600 font-medium">{{ $item->cabang }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->ccode }}</td>
                    <td class="px-3 py-2 border-r font-mono text-gray-700">{{ $item->sku }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->kategori }}</td>
                    <td class="px-3 py-2 border-r font-medium text-gray-800">{{ $item->name_item }}</td>
                    <td class="px-3 py-2 border-r">
                        {{ $item->expired_date ? \Carbon\Carbon::parse($item->expired_date)->format('d-m-Y') : '-' }}
                    </td>

                    <td
                        class="px-3 py-2 border-r text-center font-bold {{ (float)$item->stok > 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }}">
                        {{ $item->stok }}
                    </td>
                    <td class="px-3 py-2 border-r">{{ $item->oum }}</td>

                    <td class="px-3 py-2 border-r text-right">{{ $item->good }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->good_konversi }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->ktn }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->good_amount }}</td>

                    <td class="px-3 py-2 border-r text-right">{{ $item->avg_3m_in_oum }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->avg_3m_in_ktn }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->avg_3m_in_value }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->not_move_3m }}</td>

                    <td class="px-3 py-2 border-r text-right text-red-500">{{ $item->bad }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->bad_konversi }}</td>
                    <td class="px-3 py-2 border-r text-right text-red-500">{{ $item->bad_ktn }}</td>
                    <td class="px-3 py-2 border-r text-right text-red-500">{{ $item->bad_amount }}</td>

                    <td class="px-3 py-2 border-r text-right">{{ $item->wrh1 }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->wrh1_konversi }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->wrh1_amount }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->wrh2 }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->wrh2_konversi }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->wrh2_amount }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->wrh3 }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->wrh3_konversi }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->wrh3_amount }}</td>

                    <td class="px-3 py-2 border-r">{{ $item->good_storage }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->sell_per_week }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->blank_field }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->empty_field }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->min }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->re_qty }}</td>
                    <td class="px-3 py-2 border-r">
                        {{ $item->expired_info ? \Carbon\Carbon::parse($item->expired_info)->format('d-m-Y') : '-' }}
                    </td>

                    <td class="px-3 py-2 border-r text-right">{{ $item->buy }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->buy_disc }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->buy_in_ktn }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->avg }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->total }}</td>

                    <td class="px-3 py-2 border-r text-right">{{ $item->up }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->fix }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->ppn }}</td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->fix_exc_ppn }}</td>
                    <td
                        class="px-3 py-2 border-r text-right font-bold {{ str_contains($item->margin, '-') ? 'text-red-600' : 'text-green-600' }}">
                        {{ $item->margin }}
                    </td>
                    <td class="px-3 py-2 border-r text-right">{{ $item->percent_margin }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->order_no }}</td>

                    <td class="px-3 py-2 border-r">{{ $item->supplier }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->mother_sku }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->last_supplier }}</td>
                    <td class="px-3 py-2 border-r">{{ $item->divisi }}</td>
                    <td class="px-3 py-2 border-r text-gray-400 text-[10px]">{{ $item->unique_id }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="53" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                        <i class="fas fa-box-open fa-3x mb-3 text-gray-300"></i>
                        <p class="text-lg font-medium">Belum Ada Data Produk</p>
                        <p class="text-sm">Silakan Import Excel untuk mengisi data.</p>
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
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-file-import text-emerald-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Import Master Produk</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">Pilih file Excel (.xlsx) untuk update data produk.
                            </p>
                            <div
                                class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition-colors relative">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                    <div class="text-sm text-gray-600">
                                        <label for="file-upload-prod-{{ $iteration }}"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                            <span>Pilih File</span>
                                            <input id="file-upload-prod-{{ $iteration }}" wire:model="file" type="file"
                                                class="sr-only">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div wire:loading wire:target="file"
                                class="w-full mt-2 text-center text-sm text-emerald-600">Mengupload file...</div>
                            @error('file') <div class="mt-2 text-sm text-red-600">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button wire:click="import" wire:loading.attr="disabled" type="button"
                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                    Proses Import
                </button>
                <button wire:click="closeImportModal" type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                    Batal
                </button>
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
                <h3 class="text-lg font-bold text-gray-900">
                    {{ $productId ? 'Edit Data Produk' : 'Tambah Produk Baru' }}
                </h3>
                <p class="text-xs text-gray-500 mt-1">Hanya data utama yang bisa diedit manual. Untuk data lengkap
                    gunakan Import Excel.</p>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">SKU</label>
                    <input type="text" wire:model="kode_item"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 sm:text-sm">
                    @error('kode_item') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" wire:model="nama_item"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Satuan</label>
                        <input type="text" wire:model="satuan_jual"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga Beli</label>
                        <input type="number" wire:model="harga_jual"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button wire:click="store"
                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                <button wire:click="closeInputModal"
                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
            </div>
        </div>
    </div>
</div>
@endif

</div>