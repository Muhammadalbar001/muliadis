<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Master Data Produk</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola katalog barang, stok, dan harga sesuai format Excel.</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-excel mr-2"></i> Import Excel
            </button>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">

        <div class="mb-5">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 pr-4 py-2.5 w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 placeholder-slate-400 transition-all group-hover:border-indigo-300"
                    placeholder="Cari Nama Produk, SKU, atau C-Code...">
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

            <div class="relative">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full pl-3 pr-8 py-2 border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-700 appearance-none cursor-pointer bg-slate-50/50 hover:bg-white transition-colors">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
                <div class="absolute right-3 bottom-2.5 text-slate-400 pointer-events-none"><i
                        class="fas fa-chevron-down text-[10px]"></i></div>
            </div>

            <div class="relative">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Supplier</label>
                <select wire:model.live="filterSupplier"
                    class="w-full pl-3 pr-8 py-2 border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-700 appearance-none cursor-pointer bg-slate-50/50 hover:bg-white transition-colors">
                    <option value="">Semua Supplier</option>
                    @foreach($optSupplier as $s) <option value="{{ $s }}">{{Str::limit($s, 15)}}</option> @endforeach
                </select>
                <div class="absolute right-3 bottom-2.5 text-slate-400 pointer-events-none"><i
                        class="fas fa-chevron-down text-[10px]"></i></div>
            </div>

            <div class="relative">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Kategori</label>
                <select wire:model.live="filterKategori"
                    class="w-full pl-3 pr-8 py-2 border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-700 appearance-none cursor-pointer bg-slate-50/50 hover:bg-white transition-colors">
                    <option value="">Semua Kategori</option>
                    @foreach($optKategori as $k) <option value="{{ $k }}">{{ $k }}</option> @endforeach
                </select>
                <div class="absolute right-3 bottom-2.5 text-slate-400 pointer-events-none"><i
                        class="fas fa-chevron-down text-[10px]"></i></div>
            </div>

            <div class="relative">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Divisi</label>
                <select wire:model.live="filterDivisi"
                    class="w-full pl-3 pr-8 py-2 border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-700 appearance-none cursor-pointer bg-slate-50/50 hover:bg-white transition-colors">
                    <option value="">Semua Divisi</option>
                    @foreach($optDivisi as $d) <option value="{{ $d }}">{{ $d }}</option> @endforeach
                </select>
                <div class="absolute right-3 bottom-2.5 text-slate-400 pointer-events-none"><i
                        class="fas fa-chevron-down text-[10px]"></i></div>
            </div>

            <div class="relative">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Status Stok</label>
                <select wire:model.live="filterStok"
                    class="w-full pl-3 pr-8 py-2 border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-700 appearance-none cursor-pointer bg-slate-50/50 hover:bg-white transition-colors">
                    <option value="">Semua Status</option>
                    <option value="ready">✅ Ready Stock</option>
                    <option value="empty">❌ Stok Kosong</option>
                </select>
                <div class="absolute right-3 bottom-2.5 text-slate-400 pointer-events-none"><i
                        class="fas fa-chevron-down text-[10px]"></i></div>
            </div>

            <div class="flex items-end">
                <button wire:click="resetFilter"
                    class="w-full py-2 bg-white hover:bg-rose-50 text-slate-500 hover:text-rose-600 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-2 border border-slate-200 hover:border-rose-200 shadow-sm h-[38px]">
                    <i class="fas fa-undo"></i> Reset Filter
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[75vh] overflow-hidden">
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
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Good Konv</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">KTN</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Good Amount</th>

                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Avg 3M (OUM)</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Avg 3M (KTN)</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Avg 3M (Val)</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Not Move 3M</th>

                        <th class="px-4 py-3.5 border-r border-red-100 bg-red-50 text-red-700 text-right">Bad</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Bad Konv</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Bad KTN</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Bad Amount</th>

                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Wrh 1</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Wrh 1 Konv</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Wrh 1 Amt</th>

                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Wrh 2</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Wrh 2 Konv</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Wrh 2 Amt</th>

                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Wrh 3</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Wrh 3 Konv</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Wrh 3 Amt</th>

                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Good Storage</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Sell/Week</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Blank Field</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Empty Field</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Min</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Re Qty</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Expired Info</th>

                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Buy</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Buy Disc</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Buy KTN</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Avg</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Total</th>

                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">UP</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Fix</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">PPN</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50 text-right">Fix Exc PPN</th>
                        <th class="px-4 py-3.5 border-r border-yellow-200 bg-yellow-50 text-right text-yellow-700">
                            Margin</th>
                        <th class="px-4 py-3.5 border-r border-yellow-200 bg-yellow-50 text-right text-yellow-700">%
                            Margin</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Order No</th>

                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Supplier</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Mother SKU</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Last Supplier</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Divisi</th>
                        <th class="px-4 py-3.5 border-r border-slate-200 bg-slate-50">Unique ID</th>

                        <th
                            class="px-4 py-3.5 text-center bg-slate-100 border-l border-slate-200 sticky right-0 z-30 w-24 shadow-sm font-bold text-slate-700">
                            Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($produks as $item)
                    <tr
                        class="hover:bg-indigo-50/50 transition-colors {{ $item->is_duplicate ? 'bg-red-50/50' : '' }} group">

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
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->good_konversi }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->ktn }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->good_amount, 0, ',', '.') }}</td>

                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->avg_3m_in_oum }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->avg_3m_in_ktn }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->avg_3m_in_value, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100">{{ $item->not_move_3m }}</td>

                        <td
                            class="px-4 py-2.5 border-r border-slate-100 text-right text-rose-600 font-medium bg-rose-50/20">
                            {{ $item->bad }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->bad_konversi }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->bad_ktn }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->bad_amount, 0, ',', '.') }}</td>

                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->wrh1 }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->wrh1_konversi }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->wrh1_amount }}</td>

                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->wrh2 }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->wrh2_konversi }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->wrh2_amount }}</td>

                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->wrh3 }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->wrh3_konversi }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->wrh3_amount }}</td>

                        <td class="px-4 py-2.5 border-r border-slate-100">{{ $item->good_storage }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->sell_per_week }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100">{{ $item->blank_field }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100">{{ $item->empty_field }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">{{ $item->min }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right font-bold text-orange-500">
                            {{ $item->re_qty }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100">{{ $item->expired_info }}</td>

                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->buy, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->buy_disc, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->buy_in_ktn, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->avg, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right font-medium">
                            {{ number_format((float)$item->total, 0, ',', '.') }}</td>

                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->up, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->fix, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->ppn, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->fix_exc_ppn, 0, ',', '.') }}</td>

                        <td
                            class="px-4 py-2.5 border-r border-slate-100 text-right font-bold text-emerald-600 bg-emerald-50/20">
                            {{ number_format((float)$item->margin, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-right">
                            {{ number_format((float)$item->percent_margin, 2, ',', '.') }}%</td>

                        <td class="px-4 py-2.5 border-r border-slate-100">{{ $item->order_no }}</td>
                        <td
                            class="px-4 py-2.5 border-r border-slate-100 text-purple-600 font-medium truncate max-w-[150px]">
                            {{ $item->supplier }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100">{{ $item->mother_sku }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100">{{ $item->last_supplier }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100">{{ $item->divisi }}</td>
                        <td class="px-4 py-2.5 border-r border-slate-100 text-[9px] text-slate-400 font-mono">
                            {{ $item->unique_id }}</td>

                        <td
                            class="px-4 py-2.5 text-center sticky right-0 bg-white border-l border-slate-100 z-10 group-hover:bg-indigo-50/50">
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
                        <td colspan="53" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-box-open text-4xl text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg">Data Produk Kosong</h3>
                                <p class="text-slate-500 text-sm mt-1 mb-6">Belum ada data produk yang diimport atau
                                    sesuai filter.</p>
                                <button wire:click="openImportModal"
                                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">
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
                        <span class="inline-flex items-center text-xs text-emerald-600 font-bold animate-pulse">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Mengupload File...
                        </span>
                    </div>
                    <div wire:loading wire:target="import" class="w-full text-center py-2">
                        <span class="inline-flex items-center text-xs text-indigo-600 font-bold animate-pulse">
                            <i class="fas fa-cog fa-spin mr-2"></i> Memproses Data...
                        </span>
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
                        class="w-full sm:w-auto bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
                        Import Sekarang
                    </button>
                    <button wire:click="closeImportModal"
                        class="w-full sm:w-auto bg-white border border-slate-300 text-slate-700 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>