<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta" x-data="{ filterOpen: false }">

    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div
                    class="p-2.5 rounded-xl shadow-lg dark:bg-indigo-500/20 bg-indigo-600 text-white dark:text-indigo-400">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Master <span class="text-indigo-500">Produk</span>
                    </h1>
                    <div class="flex items-center gap-2 mt-1.5">
                        <p
                            class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 dark:text-slate-400 text-slate-500">
                            Inventory Management</p>
                        <span
                            class="px-2 py-0.5 dark:bg-indigo-500/10 bg-indigo-50 dark:text-indigo-400 text-indigo-600 rounded text-[9px] font-black border dark:border-indigo-500/20 border-indigo-100 shadow-sm">
                            <i class="fas fa-cubes mr-1"></i> {{ $produks->total() }} SKU
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-64 group">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-10 pr-4 py-2.5 rounded-2xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-indigo-500/20 transition-all
                        dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner" placeholder="Cari Nama / SKU / C-Code...">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between border px-4 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm
                        dark:bg-black/40 dark:border-white/5 dark:text-slate-300 bg-white border-slate-200 text-slate-700 hover:border-indigo-400">
                        <span
                            class="truncate">{{ empty($filterCabang) ? 'Regional' : count($filterCabang).' Pilih' }}</span>
                        <i class="fas fa-filter opacity-40 text-[10px] transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="absolute z-50 mt-2 w-full border rounded-2xl shadow-2xl p-2 max-h-60 overflow-y-auto custom-scrollbar
                        dark:bg-slate-900 border-slate-800 bg-white border-slate-200" style="display: none;">
                        @foreach($optCabang as $c)
                        <label
                            class="flex items-center px-3 py-2.5 hover:bg-indigo-500/10 rounded-xl cursor-pointer transition-colors group">
                            <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                                class="rounded-full border-slate-500 text-indigo-600 focus:ring-indigo-500 h-3.5 w-3.5">
                            <span
                                class="ml-3 text-[10px] font-bold uppercase tracking-tight group-hover:text-indigo-400 dark:text-slate-400 text-slate-600">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button wire:click="openImportModal"
                    class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-600/20 transition-all transform active:scale-95">
                    <i class="fas fa-file-excel"></i>
                    <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading
                    class="w-10 h-10 rounded-xl flex items-center justify-center dark:bg-slate-800 bg-white border dark:border-white/5 border-slate-200 shadow-sm">
                    <i class="fas fa-circle-notch fa-spin text-indigo-500"></i>
                </div>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none"
        class="transition-opacity duration-300 max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="rounded-[2.5rem] border overflow-hidden transition-all duration-300 flex flex-col h-[75vh]
            dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-2xl shadow-slate-200/40 dark:shadow-black/40">

            <div class="overflow-auto flex-1 w-full custom-scrollbar">
                <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                    <thead class="sticky top-0 z-20 shadow-sm border-b dark:border-white/5 border-slate-200">
                        <tr
                            class="dark:bg-slate-900 bg-slate-50 text-slate-500 dark:text-slate-400 font-black uppercase tracking-widest">
                            <th
                                class="px-4 py-5 border-r dark:border-white/5 border-slate-200 sticky left-0 dark:bg-slate-900 bg-slate-50 z-30">
                                Cabang</th>
                            <th
                                class="px-4 py-5 border-r dark:border-white/5 border-slate-200 sticky left-[70px] dark:bg-slate-900 bg-slate-50 z-30">
                                C-Code</th>
                            <th
                                class="px-4 py-5 border-r dark:border-white/5 border-slate-200 sticky left-[140px] dark:bg-slate-900 bg-slate-50 z-30">
                                SKU</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200">Kategori</th>
                            <th
                                class="px-4 py-5 border-r dark:border-white/5 border-slate-200 min-w-[250px] sticky left-[210px] dark:bg-slate-800 bg-slate-100 z-30 text-indigo-500">
                                Nama Item</th>

                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200">Expired Date</th>
                            <th
                                class="px-4 py-5 border-r border-blue-500/20 dark:bg-blue-600/10 bg-blue-50 text-blue-600 text-right">
                                Stok</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-center">OUM</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Good</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Good Konv
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">KTN</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Good Amount
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Avg 3M (OUM)
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Avg 3M (KTN)
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Avg 3M (Val)
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200">Not Move 3M</th>
                            <th
                                class="px-4 py-5 border-r border-red-500/20 dark:bg-red-600/10 bg-red-50 text-red-600 text-right">
                                Bad</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Bad Konv</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Bad KTN</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Bad Amount
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Wrh 1</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Wrh 1 Konv
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Wrh 1 Amt
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Wrh 2</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Wrh 2 Konv
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Wrh 2 Amt
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Wrh 3</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Wrh 3 Konv
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Wrh 3 Amt
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200">Good Storage</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Sell/Week
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200">Blank</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200">Empty</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Min</th>
                            <th
                                class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right font-bold text-orange-500">
                                Re Qty</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200">Expired Info</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Buy</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Buy Disc</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Buy KTN</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Avg</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right font-bold">
                                Total</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">UP</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Fix</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">PPN</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-right">Fix Exc PPN
                            </th>
                            <th
                                class="px-4 py-5 border-r border-yellow-500/20 dark:bg-yellow-500/10 bg-yellow-50 text-right text-yellow-600">
                                Margin</th>
                            <th
                                class="px-4 py-5 border-r border-yellow-500/20 dark:bg-yellow-500/10 bg-yellow-50 text-right text-yellow-600">
                                % Margin</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200">Order No</th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-purple-500">Supplier
                            </th>
                            <th class="px-4 py-5 border-r dark:border-white/5 border-slate-200">Mother SKU</th>
                            <th
                                class="px-4 py-5 border-r dark:border-white/5 border-slate-200 text-xs font-bold bg-slate-100 border-l border-slate-200 sticky right-0 z-30">
                                Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                        @forelse($produks as $item)
                        <tr
                            class="transition-colors group
                            @if($item->is_duplicate) dark:bg-rose-500/5 bg-rose-50/50 @else dark:hover:bg-white/[0.02] hover:bg-slate-50 @endif">

                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-indigo-500 font-bold sticky left-0 bg-inherit z-10 uppercase tracking-tighter">
                                {{ $item->cabang }}
                            </td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-100 font-mono opacity-50 sticky left-[70px] bg-inherit z-10">
                                {{ $item->ccode }}
                            </td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-100 font-mono font-black text-slate-700 dark:text-slate-200 sticky left-[140px] bg-inherit z-10">
                                {{ $item->sku }}
                            </td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 opacity-60">
                                {{ $item->kategori }}
                            </td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 font-black text-slate-800 dark:text-white truncate max-w-[250px] sticky left-[210px] bg-inherit z-10 shadow-xl shadow-black/5"
                                title="{{ $item->name_item }}">
                                {{ $item->name_item }}
                                @if($item->is_duplicate)
                                <span
                                    class="ml-2 px-1.5 py-0.5 rounded text-[8px] font-black bg-rose-600 text-white animate-pulse">DUPLICATE</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 opacity-60">
                                {{ $item->expired_date }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right font-black text-blue-500 dark:bg-blue-500/5 bg-blue-50/50">
                                {{ number_format((float)$item->stok, 0, ',', '.') }}
                            </td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-center font-bold opacity-40 italic">
                                {{ $item->oum }}</td>

                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->good }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right font-mono opacity-60">
                                {{ $item->good_konversi }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->ktn }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ number_format((float)$item->good_amount, 0, ',', '.') }}</td>

                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->avg_3m_in_oum }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->avg_3m_in_ktn }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ number_format((float)$item->avg_3m_in_value, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100">{{ $item->not_move_3m }}
                            </td>

                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right text-rose-500 font-bold dark:bg-rose-500/5 bg-rose-50/50">
                                {{ $item->bad }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->bad_konversi }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->bad_ktn }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ number_format((float)$item->bad_amount, 0, ',', '.') }}</td>

                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->wrh1 }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->wrh1_konversi }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->wrh1_amount }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->wrh2 }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->wrh2_konversi }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->wrh2_amount }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->wrh3 }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->wrh3_konversi }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->wrh3_amount }}</td>

                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-100 uppercase font-black opacity-30">
                                {{ $item->good_storage }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ $item->sell_per_week }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100">{{ $item->blank_field }}
                            </td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-center">
                                {{ $item->empty_field }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right opacity-60">
                                {{ $item->min }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right font-black text-orange-500">
                                {{ $item->re_qty }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 italic opacity-40">
                                {{ $item->expired_info }}</td>

                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ number_format((float)$item->buy, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ number_format((float)$item->buy_disc, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ number_format((float)$item->buy_in_ktn, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ number_format((float)$item->avg, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right font-black">
                                {{ number_format((float)$item->total, 0, ',', '.') }}</td>

                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ number_format((float)$item->up, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right">
                                {{ number_format((float)$item->fix, 0, ',', '.') }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right font-mono opacity-50">
                                {{ number_format((float)$item->ppn, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-right italic">
                                {{ number_format((float)$item->fix_exc_ppn, 0, ',', '.') }}</td>

                            <td
                                class="px-4 py-3 border-r border-yellow-500/20 dark:bg-yellow-500/10 bg-yellow-50/50 text-right font-black text-yellow-600">
                                {{ number_format((float)$item->margin, 0, ',', '.') }}
                            </td>
                            <td
                                class="px-4 py-3 border-r border-yellow-500/20 dark:bg-yellow-500/10 bg-yellow-50/50 text-right font-black text-yellow-600">
                                {{ number_format((float)$item->percent_margin, 2, ',', '.') }}%
                            </td>

                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 opacity-40">
                                {{ $item->order_no }}</td>
                            <td
                                class="px-4 py-3 border-r dark:border-white/5 border-slate-100 text-purple-500 font-bold truncate max-w-[150px] italic">
                                {{ $item->supplier }}</td>
                            <td class="px-4 py-3 border-r dark:border-white/5 border-slate-100 font-mono opacity-50">
                                {{ $item->mother_sku }}</td>

                            <td
                                class="px-4 py-3 text-center sticky right-0 dark:bg-[#0a0a0a] bg-white border-l dark:border-white/10 border-slate-200 z-10">
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Hapus produk ini?') || event.stopImmediatePropagation()"
                                    class="w-8 h-8 rounded-xl flex items-center justify-center dark:text-slate-500 text-slate-400 dark:hover:text-white hover:text-white hover:bg-rose-600 dark:hover:bg-rose-500 transition-all shadow-sm">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="53" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i class="fas fa-box-open text-6xl mb-4"></i>
                                    <p class="text-xs font-black tracking-[0.4em]">Database Produk Kosong</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="px-6 py-4 border-t dark:border-white/5 border-slate-200 dark:bg-white/[0.02] bg-slate-50/50 shadow-inner">
                {{ $produks->links() }}
            </div>
        </div>
    </div>

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Sync Katalog Produk', 'color' => 'indigo'])
    @endif
</div>

<style>
/* CSS Khusus agar tabel terasa 'Luas' dan Premium */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(99, 102, 241, 0.2);
    border-radius: 10px;
}

/* Highlight sticky column shadow saat di scroll */
.sticky {
    transition: background-color 0.3s;
}

/* Animasi fade in row */
tbody tr {
    animation: fadeIn 0.3s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(5px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>