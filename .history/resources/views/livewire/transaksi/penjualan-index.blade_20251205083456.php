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

    <!-- 2. HEADER & SEARCH -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

            <!-- Pencarian -->
            <div class="w-full md:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Trans No, Pelanggan..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
            </div>

            <!-- Upload Form -->
            <div class="w-full md:w-auto bg-blue-50 p-3 rounded-lg border border-blue-200"
                x-data="{ isUploading: false, progress: 0, hasFile: false }"
                x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false"
                x-on:livewire-upload-error="isUploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">

                <!-- Menggunakan DIV bukan FORM agar tidak refresh halaman -->
                <div class="flex flex-col gap-2">
                    <div class="flex flex-col md:flex-row gap-2 items-center">
                        <div class="relative">
                            <input type="file" wire:model="file" id="upload_{{ $iteration }}"
                                x-on:change="hasFile = $event.target.files.length > 0"
                                class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer" />
                        </div>

                        <!-- Tombol Import -->
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
        </div>
    </div>

    <!-- 3. KONTEN TABEL (Ringkas) -->
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
</div>