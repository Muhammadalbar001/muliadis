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
        <div class="flex-1 overflow-x-auto overflow-y-auto min-w-full" style="max-height: calc(100vh - 200px);">
            <table class="min-w-max text-xs text-left border-collapse table-auto">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-20">
                    <tr>
                        <!-- === URUTAN KOLOM SESUAI EXCEL (1 - 8) === -->
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Cabang</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">CCODE</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[100px] whitespace-nowrap">SKU</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[100px] whitespace-nowrap">KATEGORI
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[200px] whitespace-nowrap">NAME ITEM
                        </th>
                        <th class="px-2 py-2 border-b border-r min-w-[90px] whitespace-nowrap">EXPIRED</th>
                        <th
                            class="px-2 py-2 border-b border-r min-w-[60px] text-center font-bold bg-yellow-50 whitespace-nowrap">
                            STOK</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">OUM</th>

                        <!-- === GOOD STOCK (9 - 12) === -->
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">GOOD</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">GOOD KONVERSI</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">KTN (Good)</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">GOOD AMOUNT</th>

                        <!-- === AVG 3M (13 - 16) === -->
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">AVG 3M OUM</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">AVG 3M KTN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">AVG 3M VAL</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">NOT MOVE 3M</th>

                        <!-- === BAD STOCK (17 - 20) === -->
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">BAD</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">BAD KONVERSI</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">KTN (Bad)</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">BAD AMOUNT</th>

                        <!-- === WAREHOUSES (21 - 29) === -->
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">WRH1</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">WRH1 Konv</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">WRH1 Amt</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">WRH2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">WRH2 Konv</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">WRH2 Amt</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">WRH3</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">WRH3 Konv</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">WRH3 Amt</th>

                        <!-- === SALES & STORAGE (30 - 36) === -->
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">GOOD STORAGE</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">SELL PER WEEK</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Blank</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">EMPTY</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">MIN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">RE QTY</th>
                        <th class="px-2 py-2 border-b border-r min-w-[90px] whitespace-nowrap">EXPIRED (Info)</th>

                        <!-- === BUYING (37 - 41) === -->
                        <th class="px-2 py-2 border-b border-r min-w-[100px] bg-blue-50 whitespace-nowrap">BUY</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">BUY - DISC</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">BUY in KTN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">AVG (Harga)</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">TOTAL (Harga)</th>

                        <!-- === MARGIN & ORDER (42 - 48) === -->
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">UP</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">FIX</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">PPN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">FIX (EXC PPN)</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">MARGIN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">% MARGIN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">ORDER NO</th>

                        <!-- === META (49 - 53) === -->
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">SUPPLIER</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">MOTHER SKU</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">LAST SUPPLIER</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">DIVISI</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Unique ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($produks as $item)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <!-- === KOLOM 1 - 8 (STRING/DATE/APA ADANYA) === -->
                        <td class="px-2 py-1 border-r font-bold text-blue-800 whitespace-nowrap">{{ $item->cabang }}
                        </td>
                        <td class="px-2 py-1 border-r text-gray-500 whitespace-nowrap">{{ $item->ccode }}</td>
                        <td class="px-2 py-1 border-r font-mono whitespace-nowrap">{{ $item->sku }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kategori }}</td>
                        <td class="px-2 py-1 border-r text-gray-900 truncate max-w-[200px] whitespace-nowrap"
                            title="{{ $item->name_item }}">
                            {{ $item->name_item }}
                        </td>
                        <td
                            class="px-2 py-1 border-r whitespace-nowrap {{ $item->expired_date && \Carbon\Carbon::parse($item->expired_date)->isPast() ? 'text-red-600 font-bold' : '' }}">
                            {{ $item->expired_date ? \Carbon\Carbon::parse($item->expired_date)->format('d-m-Y') : '-' }}
                        </td>
                        <!-- STOK: Tampil apa adanya dari DB (string) -->
                        <td class="px-2 py-1 border-r text-center font-bold bg-yellow-50 whitespace-nowrap">
                            {{ $item->stok }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->oum }}</td>

                        <!-- === GOOD STOCK (9 - 12) === -->
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->good }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->good_konversi }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->ktn }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->good_amount }}</td>

                        <!-- === AVG 3M (13 - 16) === -->
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->avg_3m_in_oum }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->avg_3m_in_ktn }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->avg_3m_in_value }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->not_move_3m }}</td>

                        <!-- === BAD STOCK (17 - 20) === -->
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->bad }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->bad_konversi }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->bad_ktn }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->bad_amount }}</td>

                        <!-- === WAREHOUSES (21 - 29) === -->
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->wrh1 }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->wrh1_konversi }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->wrh1_amount }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->wrh2 }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->wrh2_konversi }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->wrh2_amount }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->wrh3 }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->wrh3_konversi }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->wrh3_amount }}</td>

                        <!-- === SALES & STORAGE (30 - 36) === -->
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->good_storage }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->sell_per_week }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->blank_field }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->empty_field }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->min }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->re_qty }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">
                            {{ $item->expired_info ? \Carbon\Carbon::parse($item->expired_info)->format('d-m-Y') : '-' }}
                        </td>

                        <!-- === BUYING (37 - 41) === -->
                        <td class="px-2 py-1 border-r text-right bg-blue-50 font-mono whitespace-nowrap">Rp
                            {{ $item->buy }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->buy_disc }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->buy_in_ktn }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->avg }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total }}</td>

                        <!-- === MARGIN & ORDER (42 - 48) === -->
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->up }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->fix }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->ppn }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->fix_exc_ppn }}</td>

                        <!-- MARGIN (46) - Logic tampilkan kurung jika ada minus di data string -->
                        <td
                            class="px-2 py-1 border-r text-right whitespace-nowrap @if(str_contains($item->margin, '-')) text-red-600 font-bold @endif">
                            {{ $this->formatNegativeParentheses($item->margin) }}
                        </td>

                        <!-- % MARGIN (47) - Logic tampilkan kurung jika ada minus di data string -->
                        <td
                            class="px-2 py-1 border-r text-right whitespace-nowrap @if(str_contains($item->percent_margin, '-')) text-red-600 font-bold @endif">
                            {{ $this->formatNegativeParentheses($item->percent_margin) }}%
                        </td>

                        <!-- ORDER NO (String) -->
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->order_no }}</td>

                        <!-- === META (49 - 53) === -->
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

<script>
// Fungsi JavaScript untuk meniru format kurung Excel
function formatNegativeParentheses(value) {
    if (typeof value !== 'string') return value;

    if (value.startsWith('-')) {
        // Hapus tanda minus, bungkus dengan kurung
        return '(' + value.substring(1) + ')';
    }
    return value;
}
</script>

<style>
/* Livewire Component styles (Jika diperlukan) */
.text-red-600 {
    color: #dc2626;
}
</style>

@push('scripts')
<script>
// Livewire method untuk memanggil format kurung di PHP (Jika diperlukan)
Livewire.directive('format-excel', ({
    expression,
    component,
    el
}) => {
    const formatNegativeParentheses = (value) => {
        if (typeof value !== 'string') return value;
        if (value.startsWith('-')) {
            return '(' + value.substring(1) + ')';
        }
        return value;
    };
    el.innerText = formatNegativeParentheses(expression);
});
</script>
@endpush