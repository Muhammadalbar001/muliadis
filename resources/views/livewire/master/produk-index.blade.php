<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-indigo-50/90 p-4 rounded-b-2xl shadow-sm border-b border-indigo-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 shadow-sm">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-indigo-900 tracking-tight">Master Produk</h1>
                    <p class="text-xs text-indigo-600 font-medium mt-0.5">Kelola katalog barang, stok, & harga.</p>
                </div>
                <div
                    class="hidden md:flex px-3 py-1 bg-white text-indigo-600 rounded-lg text-[10px] font-bold border border-indigo-100 items-center gap-2 shadow-sm">
                    <i class="fas fa-cubes"></i> {{ $produks->total() }} SKU
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-8 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-indigo-500 py-2 shadow-sm placeholder-slate-400 transition-all"
                        placeholder="Cari Nama / SKU...">
                </div>

                <div class="relative w-full sm:w-36" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-indigo-50 transition-all">
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
                            class="flex items-center px-2 py-1.5 hover:bg-indigo-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 text-indigo-600 mr-2 h-3 w-3">
                            <span class="text-xs text-slate-600">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="relative w-full sm:w-48" x-data="{ 
                    open: false, 
                    search: '',
                    selected: @entangle('filterSupplier').live
                }">
                    <button @click="open = !open; $nextTick(() => $refs.searchInput.focus())"
                        @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-indigo-50 transition-all">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Supplier' : 'Semua Supplier'"></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>

                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-64 bg-white border border-slate-200 rounded-lg shadow-xl overflow-hidden p-2"
                        style="display: none;">

                        <div class="relative mb-2">
                            <i class="fas fa-search absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                            <input x-ref="searchInput" x-model="search" type="text"
                                class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-xs text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-400"
                                placeholder="Cari Supplier...">
                        </div>

                        <div @click="selected = []; search = ''"
                            class="px-2 py-1.5 rounded-md cursor-pointer text-xs font-bold text-rose-500 hover:bg-rose-50 flex items-center gap-2 mb-1 border-b border-slate-100 pb-2">
                            <i class="fas fa-times-circle"></i> Reset Pilihan
                        </div>

                        <div class="max-h-48 overflow-y-auto custom-scrollbar space-y-1">
                            @foreach($optSupplier as $s)
                            <div x-show="'{{ strtolower($s) }}'.includes(search.toLowerCase())" @click="
                                    if (selected.includes('{{ $s }}')) {
                                        selected = selected.filter(i => i !== '{{ $s }}');
                                    } else {
                                        selected.push('{{ $s }}');
                                    }
                                "
                                class="px-2 py-1.5 rounded-md cursor-pointer text-xs font-medium hover:bg-indigo-50 hover:text-indigo-700 flex items-center justify-between transition-colors group">

                                <div class="flex items-center gap-2 overflow-hidden">
                                    <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors"
                                        :class="selected.includes('{{ $s }}') ? 'bg-indigo-500 border-indigo-500' : 'border-slate-300 bg-white group-hover:border-indigo-400'">
                                        <i x-show="selected.includes('{{ $s }}')"
                                            class="fas fa-check text-white text-[9px]"></i>
                                    </div>
                                    <span class="truncate"
                                        :class="selected.includes('{{ $s }}') ? 'text-indigo-700 font-bold' : 'text-slate-600'">{{ $s }}</span>
                                </div>
                            </div>
                            @endforeach
                            <div x-show="$el.querySelectorAll('div[x-show]').length === 0"
                                class="px-2 py-2 text-center text-xs text-slate-400 italic">
                                Tidak ditemukan
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:block h-6 w-px bg-indigo-200 mx-1"></div>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading
                    class="px-3 py-2 bg-white border border-indigo-200 text-indigo-600 rounded-lg shadow-sm flex items-center justify-center animate-pulse">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>

            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[85vh] overflow-hidden">
            <div class="overflow-auto flex-1 w-full custom-scrollbar">

                <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                    <thead
                        class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-20 shadow-sm">
                        <tr>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-0 z-30 shadow-sm">
                                Cabang
                            </th>
                            <th
                                class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-[60px] z-30 shadow-sm">
                                C-Code</th>
                            <th
                                class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-[120px] z-30 shadow-sm">
                                SKU</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Kategori</th>
                            <th
                                class="px-3 py-3 border-r border-slate-200 bg-slate-100 min-w-[200px] text-slate-700 sticky left-[180px] z-30 shadow-sm">
                                Nama Item</th>

                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Expired Date</th>
                            <th
                                class="px-3 py-3 border-r border-blue-100 bg-blue-50 text-blue-700 text-right font-extrabold">
                                Stok</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-center">OUM</th>

                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Good</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Good Konv</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">KTN</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Good Amount</th>

                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Avg 3M (OUM)</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Avg 3M (KTN)</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Avg 3M (Val)</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Not Move 3M</th>

                            <th class="px-3 py-3 border-r border-red-100 bg-red-50 text-red-700 text-right">Bad</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Bad Konv</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Bad KTN</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Bad Amount</th>

                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Wrh 1</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Wrh 1 Konv</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Wrh 1 Amt</th>

                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Wrh 2</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Wrh 2 Konv</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Wrh 2 Amt</th>

                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Wrh 3</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Wrh 3 Konv</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Wrh 3 Amt</th>

                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Good Storage</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Sell/Week</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Blank</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Empty</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Min</th>
                            <th
                                class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right font-bold text-orange-500">
                                Re Qty</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Expired Info</th>

                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Buy</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Buy Disc</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Buy KTN</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Avg</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right font-bold">Total</th>

                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">UP</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Fix</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">PPN</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 text-right">Fix Exc PPN</th>
                            <th class="px-3 py-3 border-r border-yellow-200 bg-yellow-50 text-right text-yellow-700">
                                Margin
                            </th>
                            <th class="px-3 py-3 border-r border-yellow-200 bg-yellow-50 text-right text-yellow-700">%
                                Margin</th>

                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Order No</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Supplier</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Mother SKU</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Last Supplier</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Divisi</th>
                            <th class="px-3 py-3 border-r border-slate-200 bg-slate-50">Unique ID</th>

                            <th
                                class="px-3 py-3 text-center bg-slate-100 border-l border-slate-200 sticky right-0 z-30 w-16 font-bold text-slate-700">
                                Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($produks as $item)
                        <tr
                            class="hover:bg-emerald-50/20 transition-colors {{ $item->is_duplicate ? 'bg-red-50/50' : '' }} group odd:bg-white even:bg-slate-50/30">

                            <td
                                class="px-3 py-2 border-r border-slate-100 text-indigo-600 font-bold sticky left-0 bg-inherit z-10">
                                {{ $item->cabang }}</td>
                            <td
                                class="px-3 py-2 border-r border-slate-100 font-mono text-slate-500 sticky left-[60px] bg-inherit z-10">
                                {{ $item->ccode }}</td>
                            <td
                                class="px-3 py-2 border-r border-slate-100 font-mono font-bold text-slate-700 sticky left-[120px] bg-inherit z-10">
                                {{ $item->sku }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-slate-600">{{ $item->kategori }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[250px] sticky left-[180px] bg-inherit z-10 shadow-sm"
                                title="{{ $item->name_item }}">
                                {{ $item->name_item }}
                                @if($item->is_duplicate) <span
                                    class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-red-100 text-red-600 border border-red-200">DUP</span>
                                @endif
                            </td>

                            <td class="px-3 py-2 border-r border-slate-100 text-slate-600">{{ $item->expired_date }}
                            </td>
                            <td
                                class="px-3 py-2 border-r border-slate-100 text-right font-extrabold text-blue-700 bg-blue-50/20">
                                {{ number_format((float)$item->stok, 0, ',', '.') }}</td>
                            <td
                                class="px-3 py-2 border-r border-slate-100 text-center text-[10px] uppercase text-slate-400 font-bold">
                                {{ $item->oum }}</td>

                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->good }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->good_konversi }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->ktn }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->good_amount, 0, ',', '.') }}</td>

                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->avg_3m_in_oum }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->avg_3m_in_ktn }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->avg_3m_in_value, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->not_move_3m }}</td>

                            <td class="px-3 py-2 border-r border-slate-100 text-right text-red-600 font-bold">
                                {{ $item->bad }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->bad_konversi }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->bad_ktn }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->bad_amount, 0, ',', '.') }}</td>

                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->wrh1 }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->wrh1_konversi }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->wrh1_amount }}</td>

                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->wrh2 }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->wrh2_konversi }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->wrh2_amount }}</td>

                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->wrh3 }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->wrh3_konversi }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->wrh3_amount }}</td>

                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->good_storage }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->sell_per_week }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->blank_field }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->empty_field }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">{{ $item->min }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right font-bold text-orange-500">
                                {{ $item->re_qty }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->expired_info }}</td>

                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->buy, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->buy_disc, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->buy_in_ktn, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->avg, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right font-bold">
                                {{ number_format((float)$item->total, 0, ',', '.') }}</td>

                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->up, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->fix, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->ppn, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->fix_exc_ppn, 0, ',', '.') }}</td>

                            <td
                                class="px-3 py-2 border-r border-slate-100 text-right font-bold text-emerald-600 bg-emerald-50/20">
                                {{ number_format((float)$item->margin, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-right">
                                {{ number_format((float)$item->percent_margin, 2, ',', '.') }}%</td>

                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->order_no }}</td>
                            <td
                                class="px-3 py-2 border-r border-slate-100 text-purple-600 font-medium truncate max-w-[150px]">
                                {{ $item->supplier }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->mother_sku }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->last_supplier }}</td>
                            <td class="px-3 py-2 border-r border-slate-100">{{ $item->divisi }}</td>
                            <td class="px-3 py-2 border-r border-slate-100 text-[9px] text-slate-400 font-mono">
                                {{ $item->unique_id }}</td>

                            <td
                                class="px-3 py-2 text-center sticky right-0 bg-white border-l border-slate-100 z-10 group-hover:bg-emerald-50/20">
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Yakin ingin menghapus produk ini?') || event.stopImmediatePropagation()"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-rose-500 transition-all shadow-sm"
                                    title="Hapus Data">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="53" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-box-open text-3xl text-slate-300"></i>
                                    </div>
                                    <h3 class="text-slate-800 font-bold text-base">Data Produk Kosong</h3>
                                    <p class="text-slate-400 text-xs mt-1">Belum ada data produk yang diimport.</p>
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
        @include('livewire.partials.import-modal', ['title' => 'Import Data Produk', 'color' => 'indigo'])
        @endif

    </div>