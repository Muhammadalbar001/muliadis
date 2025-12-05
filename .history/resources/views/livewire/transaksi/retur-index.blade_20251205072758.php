<div class="space-y-4">

    <!-- 1. AREA NOTIFIKASI -->
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
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Cari No Retur, Pelanggan, atau Invoice..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
            </div>

            <!-- Form Upload (Hanya Muncul di Mode Transaksi Retur) -->
            @if (!$isLaporanMode)
            <div class="w-full md:w-auto bg-blue-50 p-2 rounded-lg border border-blue-200"
                x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">

                <form wire:submit.prevent="import" class="flex flex-col gap-2">
                    <div class="flex flex-col md:flex-row gap-2 items-center">
                        <div class="relative">
                            <input type="file" wire:model="file" id="upload_{{ $iteration }}"
                                class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer" />
                        </div>

                        <button type="submit" wire:loading.attr="disabled" wire:target="file, import"
                            :disabled="isUploading"
                            class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-4 py-1.5 rounded text-sm font-bold shadow flex items-center gap-2 whitespace-nowrap transition">

                            <!-- Teks Normal -->
                            <span wire:loading.remove wire:target="import" x-show="!isUploading">
                                <i class="fas fa-upload"></i> Import
                            </span>

                            <!-- Teks Saat Uploading File (Client ke Server) -->
                            <span x-show="isUploading" x-cloak>
                                <i class="fas fa-spinner fa-spin"></i> Uploading...
                            </span>

                            <!-- Teks Saat Processing (Server Database) -->
                            <span wire:loading wire:target="import">
                                <i class="fas fa-cog fa-spin"></i> Memproses...
                            </span>
                        </button>
                    </div>

                    <!-- Progress Bar (Hanya muncul saat upload) -->
                    <div x-show="isUploading" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-1">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                            :style="`width: ${progress}%`"></div>
                        <p class="text-xs text-center mt-1 text-blue-700" x-text="`${progress}% Uploaded`"></p>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- 3. KONTEN UTAMA (KONDISIONAL) -->

    <!-- MODE RETUR PENJUALAN (Ringkas) -->
    @if (!$isLaporanMode)
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col overflow-hidden">
        <div class="flex-1 overflow-auto min-w-full" style="max-height: calc(100vh - 200px);">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-4 py-3 border-b">Tanggal</th>
                        <th class="px-4 py-3 border-b">No. Retur</th>
                        <th class="px-4 py-3 border-b">Ref. Invoice</th>
                        <th class="px-4 py-3 border-b">Pelanggan</th>
                        <th class="px-4 py-3 border-b">Nama Item</th>
                        <th class="px-4 py-3 border-b text-center">Qty</th>
                        <th class="px-4 py-3 border-b text-right">Nilai Retur</th>
                        <th class="px-4 py-3 border-b text-right">Total</th>
                        <th class="px-4 py-3 border-b text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($retur as $item)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                            {{ $item->tgl_retur ? \Carbon\Carbon::parse($item->tgl_retur)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 font-bold text-blue-700 whitespace-nowrap">
                            {{ $item->no_retur }}
                            <div class="text-xs text-gray-400 font-normal">{{ $item->cabang }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-600 font-mono text-xs">
                            {{ $item->no_inv }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $item->nama_pelanggan }}</div>
                            <div class="text-xs text-gray-500">{{ $item->kode_pelanggan }}</div>
                        </td>
                        <td class="px-4 py-3 max-w-xs truncate" title="{{ $item->nama_item }}">
                            <span class="font-medium">{{ $item->nama_item }}</span>
                            <br>
                            <span class="text-xs text-gray-500 bg-gray-100 px-1 rounded">{{ $item->kode_item }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                {{ $item->qty }} {{ $item->satuan_retur }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap text-gray-600">
                            {{ $item->nilai }}
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-red-700 whitespace-nowrap">
                            {{ $item->total_grand }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">
                                {{ $item->status ?? 'N/A' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <i class="fas fa-undo fa-3x text-gray-300"></i>
                                <h3 class="text-lg font-bold text-gray-600">Tidak Ada Data Retur</h3>
                                <p class="text-sm">Gunakan tombol Import di atas untuk memasukkan data retur.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t bg-gray-50">
            {{ $retur->links() }}
        </div>
    </div>

    <!-- MODE REKAP RETUR (Detail 39 Kolom) -->
    @else
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col">
        <div class="flex-1 overflow-x-auto overflow-y-auto min-w-full" style="max-height: calc(100vh - 200px);">
            <table class="min-w-max text-xs text-left border-collapse table-auto">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-20">
                    <tr>
                        <!-- Header 39 Kolom Lengkap -->
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[100px] whitespace-nowrap">Cabang</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[120px] whitespace-nowrap">No Retur
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Status</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[90px] whitespace-nowrap">Tgl Retur
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[120px] whitespace-nowrap">No Invoice
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Kode Pel</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[150px] whitespace-nowrap">Nama
                            Pelanggan</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Kode Item
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[200px] whitespace-nowrap">Nama Item
                        </th>

                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Qty</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Satuan</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Rata2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Up %</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai Up</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai+Pemb</th>

                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">D1</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">D2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Disc 1</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Disc 2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Disc Bawah</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total Disc</th>

                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai Net</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total Harga</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">PPN Head</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] bg-green-50 font-bold whitespace-nowrap">
                            TOTAL</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">PPN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total - PPN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">MARGIN</th>

                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Pembayaran</th>
                        <th class="px-2 py-2 border-b border-r min-w-[120px] whitespace-nowrap">Sales</th>
                        <th class="px-2 py-2 border-b border-r min-w-[120px] whitespace-nowrap">Supplier</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Year</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Month</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Divisi</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Program</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">City Code</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Mother SKU</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">Last Supp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($retur as $item)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-2 py-1 border-r font-bold text-blue-800 whitespace-nowrap">{{ $item->cabang }}
                        </td>
                        <td class="px-2 py-1 border-r font-mono whitespace-nowrap">{{ $item->no_retur }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->status }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">
                            {{ $item->tgl_retur ? \Carbon\Carbon::parse($item->tgl_retur)->format('d-m-Y') : '-' }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->no_inv }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kode_pelanggan }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap truncate max-w-[200px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kode_item }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap truncate max-w-[200px]"
                            title="{{ $item->nama_item }}">{{ $item->nama_item }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->qty }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->satuan_retur }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->rata2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->up_percent }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_up }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_retur_pembulatan }}
                        </td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->d1 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->d2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_1 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_bawah }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total_diskon }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_retur_net }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total_harga_retur }}</td>
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
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->sales_name }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->supplier }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->year }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->month }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->divisi }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->program }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->city_code }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->mother_sku }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->last_suppliers }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="39" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <i class="fas fa-undo fa-3x text-gray-300"></i>
                            <h3 class="text-lg font-bold text-gray-600">Belum Ada Data Rekap</h3>
                            <p class="text-sm max-w-sm">Data Rekap Retur akan muncul di sini setelah Anda melakukan
                                import di menu Retur Penjualan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t bg-gray-50">
            {{ $retur->links() }}
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