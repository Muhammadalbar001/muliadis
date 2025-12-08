<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Master Data Produk</h2>
            <p class="text-sm text-slate-500">Kelola katalog barang sesuai format Excel.</p>
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
                    class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition-colors flex items-center justify-center gap-2 border border-slate-200">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 flex flex-col h-[75vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap min-w-max">

                <thead
                    class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-20 shadow-sm">
                    <tr>
                        <th class="px-4 py-3 border-r bg-slate-50">Cabang</th>
                        <th class="px-4 py-3 border-r bg-slate-50">C-Code</th>
                        <th class="px-4 py-3 border-r bg-slate-50">SKU</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Kategori</th>
                        <th class="px-4 py-3 border-r bg-slate-100 min-w-[250px]">Nama Produk</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Expired Date</th>

                        <th class="px-4 py-3 border-r bg-blue-50 text-blue-700 text-right">Stok</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-center">OUM</th>

                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Good</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Good Konv</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">KTN</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Good Amount</th>

                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Avg 3M (OUM)</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Avg 3M (KTN)</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Avg 3M (Val)</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Not Move 3M</th>

                        <th class="px-4 py-3 border-r bg-red-50 text-red-700 text-right">Bad</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Bad Konv</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Bad KTN</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Bad Amount</th>

                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Wrh 1</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Wrh 1 Konv</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Wrh 1 Amt</th>

                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Wrh 2</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Wrh 2 Konv</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Wrh 2 Amt</th>

                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Wrh 3</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Wrh 3 Konv</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Wrh 3 Amt</th>

                        <th class="px-4 py-3 border-r bg-slate-50">Good Storage</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Sell/Week</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Blank Field</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Empty Field</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Min</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Re Qty</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Expired Info</th>

                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Buy</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Buy Disc</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Buy KTN</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Avg</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Total</th>

                        <th class="px-4 py-3 border-r bg-slate-50 text-right">UP</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Fix</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">PPN</th>
                        <th class="px-4 py-3 border-r bg-slate-50 text-right">Fix Exc PPN</th>
                        <th class="px-4 py-3 border-r bg-yellow-50 text-right">Margin</th>
                        <th class="px-4 py-3 border-r bg-yellow-50 text-right">% Margin</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Order No</th>

                        <th class="px-4 py-3 border-r bg-slate-50">Supplier</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Mother SKU</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Last Supplier</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Divisi</th>
                        <th class="px-4 py-3 border-r bg-slate-50">Unique ID</th>

                        <th class="px-4 py-3 text-center bg-slate-100 border-l sticky right-0 z-30 w-24 shadow-sm">Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($produks as $item)
                    <tr class="hover:bg-indigo-50 transition-colors {{ $item->is_duplicate ? 'bg-red-50' : '' }}">

                        <td class="px-4 py-2 border-r text-indigo-600 font-medium">{{ $item->cabang }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->ccode }}</td>
                        <td class="px-4 py-2 border-r font-mono font-bold">{{ $item->sku }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->kategori }}</td>
                        <td class="px-4 py-2 border-r font-bold text-slate-700 bg-slate-50/30 truncate max-w-[300px]"
                            title="{{ $item->name_item }}">
                            {{ $item->name_item }}
                            @if($item->is_duplicate) <span
                                class="ml-1 text-[9px] bg-red-100 text-red-600 px-1 rounded">DUP</span> @endif
                        </td>
                        <td class="px-4 py-2 border-r">{{ $item->expired_date }}</td>

                        <td
                            class="px-4 py-2 border-r text-right font-bold {{ $item->stok <= 0 ? 'text-red-500' : 'text-emerald-600' }}">
                            {{ number_format((float)$item->stok, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 border-r text-center text-[10px] uppercase">{{ $item->oum }}</td>

                        <td class="px-4 py-2 border-r text-right">{{ $item->good }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->good_konversi }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->ktn }}</td>
                        <td class="px-4 py-2 border-r text-right">
                            {{ number_format((float)$item->good_amount, 0, ',', '.') }}</td>

                        <td class="px-4 py-2 border-r text-right">{{ $item->avg_3m_in_oum }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->avg_3m_in_ktn }}</td>
                        <td class="px-4 py-2 border-r text-right">
                            {{ number_format((float)$item->avg_3m_in_value, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->not_move_3m }}</td>

                        <td class="px-4 py-2 border-r text-right text-red-500 bg-red-50/50">{{ $item->bad }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->bad_konversi }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ $item->bad_ktn }}</td>
                        <td class="px-4 py-2 border-r text-right">
                            {{ number_format((float)$item->bad_amount, 0, ',', '.') }}</td>

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

                        <td class="px-4 py-2 border-r text-right">{{ number_format((float)$item->buy, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 border-r text-right">
                            {{ number_format((float)$item->buy_disc, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border-r text-right">
                            {{ number_format((float)$item->buy_in_ktn, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border-r text-right">{{ number_format((float)$item->avg, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 border-r text-right font-medium">
                            {{ number_format((float)$item->total, 0, ',', '.') }}</td>

                        <td class="px-4 py-2 border-r text-right">{{ number_format((float)$item->up, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 border-r text-right">{{ number_format((float)$item->fix, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 border-r text-right">{{ number_format((float)$item->ppn, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 border-r text-right">
                            {{ number_format((float)$item->fix_exc_ppn, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border-r text-right font-bold text-emerald-600">
                            {{ number_format((float)$item->margin, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border-r text-right">
                            {{ number_format((float)$item->percent_margin, 2, ',', '.') }}%</td>
                        <td class="px-4 py-2 border-r">{{ $item->order_no }}</td>

                        <td class="px-4 py-2 border-r text-purple-600 truncate max-w-[150px]">{{ $item->supplier }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->mother_sku }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->last_supplier }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->divisi }}</td>
                        <td class="px-4 py-2 border-r text-[10px] text-gray-400">{{ $item->unique_id }}</td>

                        <td
                            class="px-4 py-2 text-center sticky right-0 bg-white border-l z-10 group-hover:bg-indigo-50">
                            <button wire:click="delete({{ $item->id }})"
                                @click="$dispatch('confirm-delete', { method: 'deleteProduk', id: {{ $item->id }} })"
                                class="text-slate-300 hover:text-red-500 transition-colors" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="53" class="px-6 py-16 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-box-open text-4xl mb-3 text-slate-300"></i>
                                <p class="font-medium text-slate-600">Tidak ada produk ditemukan</p>
                                <p class="text-xs mt-1">Import data Excel untuk melihat isi tabel.</p>
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