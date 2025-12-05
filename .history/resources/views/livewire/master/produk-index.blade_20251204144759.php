<div class="space-y-4">

    <!-- AREA NOTIFIKASI -->
    <div>
        @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow mb-4">
            <p class="font-bold"><i class="fas fa-check-circle"></i> Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow mb-4">
            <p class="font-bold"><i class="fas fa-exclamation-triangle"></i> Gagal!</p>
            <p>{{ session('error') }}</p>
        </div>
        @endif

        @error('file')
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 shadow mb-4">
            <p class="font-bold">Peringatan:</p>
            <p>{{ $message }}</p>
        </div>
        @enderror
    </div>

    <!-- HEADER CONTROLS (Search & Import) -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

            <!-- Pencarian -->
            <div class="w-full md:w-1/3 relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari SKU atau Nama Item..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <!-- Form Upload -->
            <div class="w-full md:w-auto bg-gray-50 p-2 rounded-lg border border-dashed border-gray-300">
                <form wire:submit.prevent="import" class="flex flex-col md:flex-row gap-2 items-center">
                    <div class="relative group w-full md:w-auto">
                        <input type="file" wire:model="file" id="upload_{{ $iteration }}" class="block w-full text-xs text-slate-500
                            file:mr-2 file:py-1 file:px-2
                            file:rounded-full file:border-0
                            file:text-xs file:font-semibold
                            file:bg-blue-100 file:text-blue-700
                            hover:file:bg-blue-200 cursor-pointer" />
                    </div>

                    <button type="submit" wire:loading.attr="disabled" wire:target="file, import"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-4 py-1.5 rounded text-sm font-bold shadow flex items-center gap-2 whitespace-nowrap">
                        <span wire:loading.remove wire:target="import"><i class="fas fa-file-import"></i> Import</span>
                        <span wire:loading wire:target="import"><i class="fas fa-spinner fa-spin"></i> Proses...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- TABEL DATA 53 KOLOM (RESPONSIVE & SCROLLABLE) -->
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col">
        <!-- Kontainer Scroll Utama: Mengontrol Scroll Horizontal & Vertikal -->
        <!-- min-w-full pada div agar tabel dipaksa selebar mungkin. -->
        <div class="flex-1 overflow-x-auto overflow-y-auto min-w-full" style="max-height: calc(100vh - 200px);">
            <table class="min-w-max text-xs text-left border-collapse table-auto">
                <!-- HEADER (Sticky Top, TAPI Kolom Kiri TIDAK STICKY) -->
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-20">
                    <tr>
                        <!-- === Kolom Kiri NON-STICKY === -->
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Cabang</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[100px] whitespace-nowrap">SKU</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[200px] whitespace-nowrap">Nama Item
                        </th>

                        <!-- === Kolom Data Scrollable (50 Kolom) === -->
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">CCODE</th>
                        <th class="px-2 py-2 border-b border-r min-w-[120px] whitespace-nowrap">Kategori</th>
                        <th class="px-2 py-2 border-b border-r min-w-[90px] whitespace-nowrap">Expired</th>
                        <th
                            class="px-2 py-2 border-b border-r min-w-[60px] text-center font-bold bg-yellow-50 whitespace-nowrap">
                            Stok</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">OUM</th>

                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Good</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Good Konv</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">KTN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Good Amount</th>

                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Avg 3M OUM</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Avg 3M KTN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Avg 3M Val</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Not Move 3M</th>

                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Bad</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Bad Konv</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Bad KTN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Bad Amount</th>

                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">WRH1</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">WRH1 Konv</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">WRH1 Amt</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">WRH2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">WRH2 Konv</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">WRH2 Amt</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">WRH3</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">WRH3 Konv</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">WRH3 Amt</th>

                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Good Storage</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Sell/Week</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Blank</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Empty</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Min</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Re Qty</th>
                        <th class="px-2 py-2 border-b border-r min-w-[90px] whitespace-nowrap">Exp Info</th>

                        <th class="px-2 py-2 border-b border-r min-w-[100px] bg-blue-50 whitespace-nowrap">Buy</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Buy Disc</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Buy KTN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Avg</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total</th>

                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">UP</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Fix</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">PPN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Fix Exc PPN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Margin</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">% Margin</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Order</th>

                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">Supplier</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Mother SKU</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">Last Supplier</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Divisi</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Unique ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($produks as $item)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <!-- === Kolom Kiri NON-STICKY === -->
                        <td class="px-2 py-1 border-r font-bold text-blue-800 whitespace-nowrap">{{ $item->cabang }}
                        </td>
                        <td class="px-2 py-1 border-r font-mono whitespace-nowrap">{{ $item->sku }}</td>
                        <td class="px-2 py-1 border-r text-gray-900 truncate max-w-[200px] whitespace-nowrap"
                            title="{{ $item->name_item }}">
                            {{ $item->name_item }}
                        </td>

                        <!-- === Kolom Data Scrollable === -->
                        <td class="px-2 py-1 border-r text-gray-500 whitespace-nowrap">{{ $item->ccode }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kategori }}</td>
                        <td
                            class="px-2 py-1 border-r whitespace-nowrap {{ $item->expired_date && \Carbon\Carbon::parse($item->expired_date)->isPast() ? 'text-red-600 font-bold' : '' }}">
                            {{ $item->expired_date ? \Carbon\Carbon::parse($item->expired_date)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-2 py-1 border-r text-center font-bold bg-yellow-50 whitespace-nowrap">
                            {{ number_format($item->stok, 0) }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->oum }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->good, 0) }}
                        </td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->good_konversi }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->ktn, 2) }}
                        </td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->good_amount, 2) }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->avg_3m_in_oum, 2) }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->avg_3m_in_ktn, 2) }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->avg_3m_in_value, 2) }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->not_move_3m }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->bad, 0) }}
                        </td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->bad_konversi }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->bad_ktn, 2) }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->bad_amount, 2) }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->wrh1, 0) }}
                        </td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->wrh1_konversi }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->wrh1_amount, 2) }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->wrh2, 0) }}
                        </td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->wrh2_konversi }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->wrh2_amount, 2) }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->wrh3, 0) }}
                        </td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->wrh3_konversi }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->wrh3_amount, 2) }}</td>

                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->good_storage }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->sell_per_week, 2) }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->blank_field }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->empty_field }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->min, 0) }}
                        </td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->re_qty, 0) }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">
                            {{ $item->expired_info ? \Carbon\Carbon::parse($item->expired_info)->format('d-m-Y') : '-' }}
                        </td>

                        <td class="px-2 py-1 border-r text-right bg-blue-50 font-mono whitespace-nowrap">Rp
                            {{ number_format($item->buy, 0) }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->buy_disc, 0) }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->buy_in_ktn, 0) }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->avg, 0) }}
                        </td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->total, 0) }}
                        </td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->up, 0) }}
                        </td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->fix, 0) }}
                        </td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ number_format($item->ppn, 0) }}
                        </td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->fix_exc_ppn, 0) }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->margin, 0) }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->percent_margin, 2) }}%</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">
                            {{ number_format($item->order_qty, 0) }}</td>

                        <td class="px-2 py-1 border-r truncate max-w-[150px] whitespace-nowrap"
                            title="{{ $item->supplier }}">{{ $item->supplier }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->mother_sku }}</td>
                        <td class="px-2 py-1 border-r truncate max-w-[150px] whitespace-nowrap"
                            title="{{ $item->last_supplier }}">{{ $item->last_supplier }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->divisi }}</td>
                        <td class="px-2 py-1 border-r text-xs text-gray-400 whitespace-nowrap">{{ $item->unique_id }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="53" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-box-open fa-2x text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-600">Belum Ada Data Produk</h3>
                                <p class="text-sm max-w-sm">Silakan upload file Excel.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination di Bawah -->
        <div class="p-2 border-t bg-gray-50">
            {{ $produks->links() }}
        </div>
    </div>
</div>