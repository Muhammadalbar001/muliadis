<div class="space-y-6 font-jakarta">

    <div
        class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-end gap-4">

        <div class="w-full md:w-1/2">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Daftar Produk</h2>
            <p class="text-xs text-slate-500 mb-3">Kelola data master barang dagangan.</p>
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 pr-4 py-2.5 w-full border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-400 bg-slate-50"
                    placeholder="Cari Kode Item atau Nama Produk...">
                <i class="fas fa-search absolute left-3.5 top-3 text-slate-400 text-sm"></i>
            </div>
        </div>

        <div class="flex gap-2">
            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5 hover:shadow-indigo-200">
                <i class="fas fa-file-import mr-2"></i> Import Excel
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[75vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap min-w-max">

                <thead class="text-gray-600 uppercase bg-gray-100 font-bold sticky top-0 z-20 shadow-sm">
                    <tr>
                        <th class="px-4 py-3 text-center border-b border-r bg-gray-100 sticky left-0 z-30 w-12">No</th>
                        <th class="px-4 py-3 text-center border-b border-r bg-gray-100 sticky left-12 z-30 w-24">Aksi
                        </th>
                        <th class="px-4 py-3 border-b border-r bg-gray-100 sticky left-36 z-30 w-64 shadow-md">Nama
                            Produk</th>

                        <th class="px-4 py-3 border-b border-r min-w-[100px]">Cabang</th>
                        <th class="px-4 py-3 border-b border-r min-w-[100px]">C-Code</th>
                        <th class="px-4 py-3 border-b border-r min-w-[100px]">SKU</th>
                        <th class="px-4 py-3 border-b border-r min-w-[150px]">Kategori</th>
                        <th class="px-4 py-3 border-b border-r min-w-[100px]">Expired Date</th>

                        <th class="px-4 py-3 border-b border-r bg-blue-50 text-blue-800 text-right">Stok (All)</th>
                        <th class="px-4 py-3 border-b border-r text-center">OUM</th>

                        <th class="px-4 py-3 border-b border-r text-right">Good</th>
                        <th class="px-4 py-3 border-b border-r text-right">Good Konv</th>
                        <th class="px-4 py-3 border-b border-r text-right">KTN</th>
                        <th class="px-4 py-3 border-b border-r text-right">Good Amount</th>

                        <th class="px-4 py-3 border-b border-r text-right">Avg 3M (OUM)</th>
                        <th class="px-4 py-3 border-b border-r text-right">Avg 3M (KTN)</th>
                        <th class="px-4 py-3 border-b border-r text-right">Avg 3M (Value)</th>
                        <th class="px-4 py-3 border-b border-r">Not Move 3M</th>

                        <th class="px-4 py-3 border-b border-r bg-red-50 text-red-800 text-right">Bad</th>
                        <th class="px-4 py-3 border-b border-r text-right">Bad Konv</th>
                        <th class="px-4 py-3 border-b border-r text-right">Bad KTN</th>
                        <th class="px-4 py-3 border-b border-r text-right">Bad Amount</th>

                        <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 1</th>
                        <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 1 Konv</th>
                        <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 1 Amt</th>

                        <th class="px-4 py-3 border-b border-r text-right">Wrh 2</th>
                        <th class="px-4 py-3 border-b border-r text-right">Wrh 2 Konv</th>
                        <th class="px-4 py-3 border-b border-r text-right">Wrh 2 Amt</th>

                        <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 3</th>
                        <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 3 Konv</th>
                        <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 3 Amt</th>

                        <th class="px-4 py-3 border-b border-r">Good Storage</th>
                        <th class="px-4 py-3 border-b border-r text-right">Sell/Week</th>
                        <th class="px-4 py-3 border-b border-r">Blank Field</th>
                        <th class="px-4 py-3 border-b border-r">Empty Field</th>
                        <th class="px-4 py-3 border-b border-r text-right">Min</th>
                        <th class="px-4 py-3 border-b border-r text-right">Re Qty</th>
                        <th class="px-4 py-3 border-b border-r">Expired Info</th>

                        <th class="px-4 py-3 border-b border-r text-right bg-emerald-50 text-emerald-800">Buy Price</th>
                        <th class="px-4 py-3 border-b border-r text-right">Buy Disc</th>
                        <th class="px-4 py-3 border-b border-r text-right">Buy in KTN</th>
                        <th class="px-4 py-3 border-b border-r text-right">Avg Price</th>
                        <th class="px-4 py-3 border-b border-r text-right">Total</th>
                        <th class="px-4 py-3 border-b border-r text-right">UP</th>
                        <th class="px-4 py-3 border-b border-r text-right">Fix</th>
                        <th class="px-4 py-3 border-b border-r text-right">PPN</th>
                        <th class="px-4 py-3 border-b border-r text-right">Fix Exc PPN</th>
                        <th class="px-4 py-3 border-b border-r text-right bg-yellow-50">Margin</th>
                        <th class="px-4 py-3 border-b border-r text-right bg-yellow-50">% Margin</th>
                        <th class="px-4 py-3 border-b border-r">Order No</th>

                        <th class="px-4 py-3 border-b border-r">Supplier</th>
                        <th class="px-4 py-3 border-b border-r">Mother SKU</th>
                        <th class="px-4 py-3 border-b border-r">Last Supplier</th>
                        <th class="px-4 py-3 border-b border-r">Divisi</th>
                        <th class="px-4 py-3 border-b border-r">Unique ID</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($produks as $index => $item)
                    <tr class="hover:bg-indigo-50 transition-colors {{ $item->is_duplicate ? 'bg-red-50' : '' }}">

                        <td
                            class="px-4 py-2 text-center text-gray-500 border-r bg-inherit sticky left-0 z-10 font-mono">
                            {{ $produks->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-2 text-center border-r bg-inherit sticky left-12 z-10">
                            <div class="flex justify-center gap-2">
                                <button wire:click="edit({{ $item->id }})" class="text-blue-600 hover:text-blue-800"
                                    title="Edit"><i class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $item->id }})"
                                    @click="$dispatch('confirm-delete', { method: 'deleteProduk', id: {{ $item->id }} })"
                                    class="text-red-500 hover:text-red-700" title="Hapus"><i
                                        class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                        <td class="px-4 py-2 font-bold text-gray-800 border-r bg-inherit sticky left-36 z-10 shadow-md truncate max-w-[250px]"
                            title="{{ $item->name_item }}">
                            {{ $item->name_item }}
                            @if($item->is_duplicate)
                            <span class="ml-2 text-[10px] bg-red-100 text-red-600 px-1 rounded">DUPLIKAT</span>
                            @endif
                        </td>

                        <td class="px-4 py-2 border-r text-indigo-600 font-medium">{{ $item->cabang }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->ccode }}</td>
                        <td class="px-4 py-2 border-r font-mono font-bold">{{ $item->sku }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->kategori }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->expired_date }}</td>

                        <td
                            class="px-4 py-2 border-r text-right font-bold {{ $item->stok == 0 ? 'text-red-500' : 'text-green-600' }}">
                            {{ $item->stok }}</td>
                        <td class="px-4 py-2 border-r text-center">{{ $item->oum }}</td>

                        <td class="px-4 py-2 border-r text-right">{{ $item->good }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->good_konversi }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->ktn }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->good_amount }}</td>

                        <td class="px-4 py-2 border-r text-right">{{ $item->avg_3m_in_oum }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->avg_3m_in_ktn }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->avg_3m_in_value }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->not_move_3m }}</td>

                        <td class="px-4 py-2 border-r text-right text-red-500 bg-red-50/50">{{ $item->bad }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->bad_konversi }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->bad_ktn }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->bad_amount }}</td>

                        <td class="px-4 py-2 border-r text-right">{{ $item->wrh1 }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->wrh1_konversi }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->wrh1_amount }}</td>

                        <td class="px-4 py-2 border-r text-right">{{ $item->wrh2 }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->wrh2_konversi }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->wrh2_amount }}</td>

                        <td class="px-4 py-2 border-r text-right">{{ $item->wrh3 }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->wrh3_konversi }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->wrh3_amount }}</td>

                        <td class="px-4 py-2 border-r">{{ $item->good_storage }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->sell_per_week }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->blank_field }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->empty_field }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->min }}</td>
                        <td class="px-4 py-2 border-r text-right font-bold text-orange-600">{{ $item->re_qty }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->expired_info }}</td>

                        <td class="px-4 py-2 border-r text-right">{{ $item->buy }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->buy_disc }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->buy_in_ktn }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->avg }}</td>
                        <td class="px-4 py-2 border-r text-right font-bold">{{ $item->total }}</td>

                        <td class="px-4 py-2 border-r text-right">{{ $item->up }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->fix }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->ppn }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->fix_exc_ppn }}</td>
                        <td class="px-4 py-2 border-r text-right font-bold text-emerald-600">{{ $item->margin }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->percent_margin }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->order_no }}</td>

                        <td class="px-4 py-2 border-r text-purple-600 font-medium">{{ $item->supplier }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->mother_sku }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->last_supplier }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->divisi }}</td>
                        <td class="px-4 py-2 border-r text-xs text-gray-400">{{ $item->unique_id }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="55" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p class="text-sm font-medium">Tidak ada data produk ditemukan.</p>
                                <p class="text-xs">Pastikan filter sesuai atau import data baru.</p>
                            </div>
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
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                wire:click="closeImportModal"></div>

            <div
                class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-file-excel text-indigo-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-slate-900">Import Data Produk</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 mb-4">Upload file Excel (.xlsx).</p>

                                <div
                                    class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 cursor-pointer relative">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-slate-400"></i>
                                        <div class="flex text-sm text-slate-600 justify-center mt-2">
                                            <label
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                                <span>Pilih File</span>
                                                <input wire:model="file" type="file" class="sr-only">
                                            </label>
                                        </div>
                                    </div>
                                    @if($file)
                                    <div
                                        class="absolute inset-0 bg-indigo-50 bg-opacity-90 flex flex-col items-center justify-center rounded-xl">
                                        <span
                                            class="text-sm font-bold text-slate-700">{{ $file->getClientOriginalName() }}</span>
                                    </div>
                                    @endif
                                </div>

                                <div wire:loading wire:target="import" class="w-full mt-2 text-center">
                                    <span class="text-xs text-green-600 font-bold animate-pulse">Sedang memproses
                                        data...</span>
                                </div>
                                @error('file') <div class="mt-2 text-red-500 text-xs">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Mulai Import
                    </button>
                    <button wire:click="closeImportModal"
                        class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>