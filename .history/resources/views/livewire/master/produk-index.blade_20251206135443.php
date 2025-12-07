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

            @if(!empty($filterCabang) || !empty($filterKategori) || !empty($filterDivisi) || !empty($filterSupplier) ||
            $filterStok)
            <button wire:click="resetFilter"
                class="ml-auto text-xs text-red-500 hover:text-red-700 underline cursor-pointer flex items-center">
                <i class="fas fa-times mr-1"></i> Reset Filter
            </button>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            <div x-data="{ open: false }" class="relative">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cabang</label>
                <button @click="open = !open" @click.outside="open = false" type="button"
                    class="w-full text-left bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs flex justify-between items-center focus:ring-2 focus:ring-indigo-500">
                    <span class="truncate">
                        @if(empty($filterCabang)) Semua Cabang
                        @else {{ count($filterCabang) }} Terpilih
                        @endif
                    </span>
                    <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                </button>
                <div x-show="open" x-transition
                    class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    <div class="p-2 space-y-1">
                        @foreach($optCabang as $cab)
                        <label class="flex items-center px-2 py-1 hover:bg-indigo-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2 h-4 w-4">
                            <span class="text-xs text-gray-700">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kategori</label>
                <button @click="open = !open" @click.outside="open = false" type="button"
                    class="w-full text-left bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs flex justify-between items-center focus:ring-2 focus:ring-indigo-500">
                    <span class="truncate">
                        @if(empty($filterKategori)) Semua Kategori
                        @else {{ count($filterKategori) }} Terpilih
                        @endif
                    </span>
                    <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                </button>
                <div x-show="open" x-transition
                    class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    <div class="p-2 space-y-1">
                        @foreach($optKategori as $kat)
                        <label class="flex items-center px-2 py-1 hover:bg-indigo-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $kat }}" wire:model.live="filterKategori"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2 h-4 w-4">
                            <span class="text-xs text-gray-700">{{ $kat }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Divisi</label>
                <button @click="open = !open" @click.outside="open = false" type="button"
                    class="w-full text-left bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs flex justify-between items-center focus:ring-2 focus:ring-indigo-500">
                    <span class="truncate">
                        @if(empty($filterDivisi)) Semua Divisi
                        @else {{ count($filterDivisi) }} Terpilih
                        @endif
                    </span>
                    <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                </button>
                <div x-show="open" x-transition
                    class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    <div class="p-2 space-y-1">
                        @foreach($optDivisi as $div)
                        <label class="flex items-center px-2 py-1 hover:bg-indigo-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $div }}" wire:model.live="filterDivisi"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2 h-4 w-4">
                            <span class="text-xs text-gray-700">{{ $div }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Supplier</label>
                <button @click="open = !open" @click.outside="open = false" type="button"
                    class="w-full text-left bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs flex justify-between items-center focus:ring-2 focus:ring-indigo-500">
                    <span class="truncate">
                        @if(empty($filterSupplier)) Semua Supplier
                        @else {{ count($filterSupplier) }} Terpilih
                        @endif
                    </span>
                    <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                </button>
                <div x-show="open" x-transition
                    class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    <div class="p-2 space-y-1">
                        @foreach($optSupplier as $sup)
                        <label class="flex items-center px-2 py-1 hover:bg-indigo-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $sup }}" wire:model.live="filterSupplier"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2 h-4 w-4">
                            <span class="text-xs text-gray-700">{{ $sup }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Status Stok</label>
                <select wire:model.live="filterStok"
                    class="w-full border-gray-200 rounded-lg text-xs focus:ring-indigo-500 py-2">
                    <option value="">Semua Status</option>
                    <option value="ready">Ready Stock (> 0)</option>
                    <option value="empty">Stok Kosong (0)</option>
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
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Avg 3M (OUM)</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Avg 3M (Ktn)</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Avg 3M (Rp)</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Not Move</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right text-red-600">Bad</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Bad Konv</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right text-red-600">Bad Ktn</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right text-red-600">Bad Amt</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">WRH 1</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">WRH 1 Konv</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">WRH 1 Amt</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">WRH 2</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">WRH 2 Konv</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">WRH 2 Amt</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">WRH 3</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">WRH 3 Konv</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">WRH 3 Amt</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Good Storage</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Sell/Week</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Blank</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Empty</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Min</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Re Qty</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Expired</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Buy Price</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Disc</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Buy Ktn</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Avg</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Total</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">UP</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Fix</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">PPN</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Fix Exc</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Margin</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">% Margin</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Order No</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[150px]">Supplier</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Mother SKU</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[150px]">Last Supp</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Divisi</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Unique ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($produks as $index => $item)
                    <tr
                        class="transition-colors group {{ $item->is_duplicate ? 'bg-yellow-50 hover:bg-yellow-100' : 'hover:bg-indigo-50' }}">
                        <td
                            class="px-3 py-2 border-r text-center text-gray-500 bg-white sticky left-0 z-10 {{ $item->is_duplicate ? 'bg-yellow-50 group-hover:bg-yellow-100' : 'group-hover:bg-indigo-50' }}">
                            {{ $produks->firstItem() + $index }}
                        </td>
                        <td
                            class="px-3 py-2 border-r text-center bg-white sticky left-[50px] z-10 shadow-sm {{ $item->is_duplicate ? 'bg-yellow-50 group-hover:bg-yellow-100' : 'group-hover:bg-indigo-50' }}">
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
                            {{ $item->stok }}</td>
                        <td class="px-3 py-2 border-r text-center">{{ $item->oum }}</td>

                        <td class="px-3 py-2 border-r text-right">{{ $item->good }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->good_konversi }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->ktn }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->good_amount }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->avg_3m_in_oum }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->avg_3m_in_ktn }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->avg_3m_in_value }}</td>
                        <td class="px-3 py-2 border-r text-center">{{ $item->not_move_3m }}</td>
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
                            {{ $item->margin }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->percent_margin }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->order_no }}</td>
                        <td class="px-3 py-2 border-r truncate max-w-[150px]" title="{{ $item->supplier }}">
                            {{ $item->supplier }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->mother_sku }}</td>
                        <td class="px-3 py-2 border-r truncate max-w-[150px]">{{ $item->last_supplier }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->divisi }}</td>
                        <td class="px-3 py-2 border-r text-[10px] text-gray-400">{{ $item->unique_id }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="53" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <i class="fas fa-box-open fa-3x mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Belum Ada Data Produk</p>
                            <p class="text-sm">Silakan klik tombol <span class="font-bold text-emerald-600">Import
                                    Excel</span> di atas.</p>
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
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75" wire:click="closeImportModal"></div>
            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Import Data Produk</h3>
                    <div
                        class="mt-2 border-2 border-dashed rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50 relative">
                        <label class="cursor-pointer block">
                            <i class="fas fa-cloud-upload-alt text-3xl text-emerald-500 mb-2"></i>
                            <span class="block text-sm font-medium text-gray-700">Klik untuk Upload Excel</span>
                            <input type="file" wire:model="file" class="hidden">
                        </label>
                    </div>
                    <div wire:loading wire:target="file" class="text-center text-xs text-emerald-600 mt-2">Uploading...
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import"
                        class="w-full sm:w-auto bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 disabled:opacity-50">
                        <span wire:loading.remove wire:target="import">Mulai Import</span>
                        <span wire:loading wire:target="import">Memproses...</span>
                    </button>
                    <button wire:click="closeImportModal"
                        class="mt-2 sm:mt-0 w-full sm:w-auto border border-gray-300 bg-white px-4 py-2 rounded-lg hover:bg-gray-50">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isInputOpen)
    @endif
</div>