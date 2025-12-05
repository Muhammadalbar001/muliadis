<div class="space-y-4">

    <!-- 1. NOTIFIKASI -->
    <div>
        @if (session()->has('success'))
        <div
            class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow mb-4 flex justify-between items-center">
            <div>
                <p class="font-bold"><i class="fas fa-check-circle"></i> Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.remove()"
                class="text-green-700 font-bold">&times;</button>
        </div>
        @endif

        @if (session()->has('error'))
        <div
            class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow mb-4 flex justify-between items-center">
            <div>
                <p class="font-bold"><i class="fas fa-exclamation-triangle"></i> Gagal!</p>
                <p>{{ session('error') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-700 font-bold">&times;</button>
        </div>
        @endif

        <!-- Error Validasi File -->
        @error('file')
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 shadow mb-4">
            <p class="font-bold">Peringatan Validasi:</p>
            <p>{{ $message }}</p>
        </div>
        @enderror
    </div>

    <!-- 2. HEADER CONTROLS -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

            <!-- Pencarian -->
            <div class="w-full {{ !$isLaporanMode ? 'md:w-1/3' : 'md:w-2/3' }} relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No Transaksi, Pelanggan..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
            </div>

            <!-- Upload Area (Hanya di Mode Transaksi) -->
            @if (!$isLaporanMode)
            <div class="w-full md:w-auto bg-blue-50 p-3 rounded-lg border border-blue-200"
                x-data="{ isUploading: false, progress: 0, hasFile: false }"
                x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false"
                x-on:livewire-upload-error="isUploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">

                <!-- PENTING: Gunakan DIV, bukan FORM agar tidak refresh halaman -->
                <div class="flex flex-col gap-2">
                    <div class="flex flex-col md:flex-row gap-2 items-center">
                        <div class="relative">
                            <!-- Input File: Deteksi perubahan file dengan @change -->
                            <input type="file" wire:model="file" id="upload_{{ $iteration }}"
                                x-on:change="hasFile = $event.target.files.length > 0"
                                class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer" />
                        </div>

                        <!-- Tombol Import -->
                        <!-- type="button": Mencegah refresh halaman -->
                        <!-- :disabled: Tombol mati jika tidak ada file ATAU sedang uploading -->
                        <button type="button" wire:click="import" wire:loading.attr="disabled"
                            :disabled="!hasFile || isUploading"
                            class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-4 py-1.5 rounded text-sm font-bold shadow flex items-center gap-2 whitespace-nowrap transition">

                            <!-- Teks Normal -->
                            <span wire:loading.remove wire:target="import" x-show="!isUploading">
                                <i class="fas fa-upload"></i> Import Penjualan
                            </span>

                            <!-- Teks Saat Uploading (Client -> Server) -->
                            <span x-show="isUploading" x-cloak>
                                <i class="fas fa-spinner fa-spin"></i> Uploading... <span x-text="progress+'%'"></span>
                            </span>

                            <!-- Teks Saat Processing (Server Database) -->
                            <span wire:loading wire:target="import">
                                <i class="fas fa-cog fa-spin"></i> Memproses...
                            </span>
                        </button>
                    </div>

                    <!-- Progress Bar Visual -->
                    <div x-show="isUploading"
                        class="w-full bg-gray-200 rounded-full h-2.5 mt-1 transition-all duration-300">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                            :style="`width: ${progress}%`"></div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- 3. KONTEN TABEL -->

    <!-- MODE TRANSAKSI (Ringkas) -->
    @if (!$isLaporanMode)
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col overflow-hidden">
        <div class="flex-1 overflow-auto min-w-full" style="max-height: calc(100vh - 200px);">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-4 py-3 border-b">Tanggal</th>
                        <th class="px-4 py-3 border-b">No. Transaksi</th>
                        <th class="px-4 py-3 border-b">Pelanggan</th>
                        <th class="px-4 py-3 border-b">Sales</th>
                        <th class="px-4 py-3 border-b">Nama Item</th>
                        <th class="px-4 py-3 border-b text-center">Qty</th>
                        <th class="px-4 py-3 border-b text-right">Harga Satuan</th>
                        <th class="px-4 py-3 border-b text-right">Total</th>
                        <th class="px-4 py-3 border-b text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($penjualan as $item)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                            {{ $item->tgl_penjualan ? \Carbon\Carbon::parse($item->tgl_penjualan)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 font-bold text-blue-700 whitespace-nowrap">{{ $item->trans_no }}<div
                                class="text-xs text-gray-400 font-normal">{{ $item->cabang }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $item->nama_pelanggan }}</div>
                            <div class="text-xs text-gray-500">{{ $item->kode_pelanggan }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $item->sales_name }}</td>
                        <td class="px-4 py-3 max-w-xs truncate" title="{{ $item->nama_item }}"><span
                                class="font-medium">{{ $item->nama_item }}</span><br><span
                                class="text-xs text-gray-500 bg-gray-100 px-1 rounded">{{ $item->sku }}</span></td>
                        <td class="px-4 py-3 text-center"><span
                                class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $item->qty }}
                                {{ $item->satuan_jual }}</span></td>
                        <td class="px-4 py-3 text-right whitespace-nowrap text-gray-600">{{ $item->nilai }}</td>
                        <td class="px-4 py-3 text-right font-bold text-green-700 whitespace-nowrap">
                            {{ $item->total_grand }}</td>
                        <td class="px-4 py-3 text-center">
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">{{ $item->status ?? 'N/A' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">Tidak ada data transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t bg-gray-50">{{ $penjualan->links() }}</div>
    </div>

    <!-- MODE REKAP (Lengkap 51 Kolom) -->
    @else
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col">
        <div class="flex-1 overflow-x-auto overflow-y-auto min-w-full" style="max-height: calc(100vh - 200px);">
            <table class="min-w-max text-xs text-left border-collapse table-auto">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-20">
                    <tr>
                        <!-- Header 51 Kolom (Sama seperti sebelumnya) -->
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[100px] whitespace-nowrap">Cabang</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[120px] whitespace-nowrap">Trans No
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Status</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[90px] whitespace-nowrap">Tgl Jual</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Period</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[90px] whitespace-nowrap">Jatuh Tempo
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Kode Pel</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[150px] whitespace-nowrap">Nama
                            Pelanggan</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Kode Item
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[100px] whitespace-nowrap">SKU</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[90px] whitespace-nowrap">No Batch</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[90px] whitespace-nowrap">ED</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[200px] whitespace-nowrap">Nama Item
                        </th>

                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Qty</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Satuan</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Qty I</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Satuan I</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Rata2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Up %</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai Up</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">N. Jual+Pemb</th>

                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">D1</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">D2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Disc 1</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Disc 2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Disc Bawah</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total Disc</th>

                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">N. Jual Net</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total Harga</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">PPN Head</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] bg-green-50 font-bold whitespace-nowrap">
                            TOTAL</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">PPN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total - PPN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">MARGIN</th>

                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Pembayaran</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Cash/Bank</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Kode Sales</th>
                        <th class="px-2 py-2 border-b border-r min-w-[120px] whitespace-nowrap">Sales</th>
                        <th class="px-2 py-2 border-b border-r min-w-[120px] whitespace-nowrap">Supplier</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Status Pay</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Trx ID</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Year</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Month</th>

                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">Last Supp</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Mother SKU</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Divisi</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Program</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">Outlet Sales</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">City Code</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">Sales Outlet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($penjualan as $item)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-2 py-1 border-r font-bold text-blue-800 whitespace-nowrap">{{ $item->cabang }}
                        </td>
                        <td class="px-2 py-1 border-r font-mono whitespace-nowrap">{{ $item->trans_no }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->status }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">
                            {{ $item->tgl_penjualan ? \Carbon\Carbon::parse($item->tgl_penjualan)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->period }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">
                            {{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kode_pelanggan }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap truncate max-w-[150px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kode_item }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->sku }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->no_batch }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">
                            {{ $item->ed ? \Carbon\Carbon::parse($item->ed)->format('d-m-Y') : '-' }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap truncate max-w-[200px]"
                            title="{{ $item->nama_item }}">{{ $item->nama_item }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->qty }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->satuan_jual }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->qty_i }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->satuan_i }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->rata2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->up_percent }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_up }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_jual_pembulatan }}
                        </td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->d1 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->d2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_1 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_bawah }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total_diskon }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_jual_net }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total_harga_jual }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->ppn_head }}</td>
                        <td class="px-2 py-1 border-r text-right font-bold bg-green-50 whitespace-nowrap">
                            {{ $item->total_grand }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->ppn_value }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total_min_ppn }}</td>
                        <td
                            class="px-2 py-1 border-r text-right whitespace-nowrap @if(str_contains($item->margin, '-')) text-red-600 font-bold @endif">
                            {{ $this->formatNegativeParentheses($item->margin) }}
                        </td>

                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->pembayaran }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->cash_bank }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kode_sales }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->sales_name }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->supplier }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->status_pay }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->trx_id }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->year }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->month }}</td>

                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->last_suppliers }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->mother_sku }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->divisi }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->program }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->outlet_code_sales_name }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->city_code_outlet_program }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->sales_name_outlet_code }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="51" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                <h3 class="text-lg font-bold text-gray-600">Belum Ada Data Penjualan</h3>
                                <p class="text-sm max-w-sm">Silakan upload file Excel Rekap Penjualan (Hanya tersedia di
                                    menu Transaksi > Order Penjualan).</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-2 border-t bg-gray-50">
            {{ $penjualan->links() }}
        </div>
    </div>
    @endif

</div>

<!-- Script Helper Format Kurung -->
<script>
function formatNegativeParentheses(value) {
    if (typeof value !== 'string') return value;
    if (value.startsWith('-')) {
        return '(' + value.substring(1) + ')';
    }
    return value;
}
</script>