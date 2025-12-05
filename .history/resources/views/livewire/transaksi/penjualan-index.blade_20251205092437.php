<div class="space-y-4">

    <!-- NOTIFIKASI -->
    <div>
        @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4 shadow">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if (session()->has('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4 shadow">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
        @endif
        @error('file')
        <div class="bg-yellow-100 text-yellow-700 p-4 rounded mb-4 shadow">
            <i class="fas fa-exclamation-circle"></i> {{ $message }}
        </div>
        @enderror
    </div>

    <!-- KONTROL UTAMA -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">

            <!-- Search -->
            <div class="w-full md:w-1/3 relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Transaksi..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>

            <!-- UPLOAD AREA (ALPINE + LIVEWIRE) -->
            <div class="w-full md:w-auto bg-blue-50 p-3 rounded-lg border border-blue-200"
                x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">

                <div class="flex gap-2 items-center">
                    <!-- INPUT FILE -->
                    <input type="file" wire:model="file" id="upload_{{ $iteration }}"
                        class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-blue-600 file:text-white cursor-pointer"
                        accept=".xlsx,.csv" />

                    <!-- TOMBOL IMPORT -->
                    <!-- Tombol MATI jika sedang uploading (Alpine) ATAU loading state Livewire aktif -->
                    <button type="button" wire:click="import" wire:loading.attr="disabled" :disabled="uploading"
                        class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-4 py-1.5 rounded text-sm font-bold shadow flex items-center gap-2 whitespace-nowrap transition">

                        <!-- Ikon Normal -->
                        <span wire:loading.remove wire:target="import" x-show="!uploading">
                            <i class="fas fa-upload"></i> Import
                        </span>

                        <!-- Ikon Uploading (Client) -->
                        <span x-show="uploading" x-cloak>
                            <i class="fas fa-spinner fa-spin"></i> <span x-text="progress+'%'"></span>
                        </span>

                        <!-- Ikon Processing (Server) -->
                        <span wire:loading wire:target="import">
                            <i class="fas fa-cog fa-spin"></i> Proses...
                        </span>
                    </button>
                </div>

                <!-- PROGRESS BAR -->
                <div x-show="uploading" class="w-full bg-gray-200 rounded-full h-2 mt-2 overflow-hidden">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                        :style="`width: ${progress}%`"></div>
                </div>
            </div>

        </div>
    </div>

    <!-- TABEL RINGKAS -->
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col overflow-hidden">
        <div class="flex-1 overflow-auto min-w-full" style="max-height: 600px;">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-4 py-3 border-b">Tanggal</th>
                        <th class="px-4 py-3 border-b">No. Transaksi</th>
                        <th class="px-4 py-3 border-b">Pelanggan</th>
                        <th class="px-4 py-3 border-b">Sales</th>
                        <th class="px-4 py-3 border-b">Nama Item</th>
                        <th class="px-4 py-3 border-b text-center">Qty</th>
                        <th class="px-4 py-3 border-b text-right">Harga</th>
                        <th class="px-4 py-3 border-b text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($penjualan as $item)
                    <tr class="hover:bg-blue-50">
                        <td class="px-4 py-2 whitespace-nowrap">{{ $item->tgl_penjualan }}</td>
                        <td class="px-4 py-2 font-bold text-blue-700 whitespace-nowrap">{{ $item->trans_no }}</td>
                        <td class="px-4 py-2">{{ $item->nama_pelanggan }}</td>
                        <td class="px-4 py-2">{{ $item->sales_name }}</td>
                        <td class="px-4 py-2 max-w-xs truncate">{{ $item->nama_item }}</td>
                        <td class="px-4 py-2 text-center font-bold">{{ $item->qty }}</td>
                        <td class="px-4 py-2 text-right">{{ $item->nilai }}</td>
                        <td class="px-4 py-2 text-right font-bold text-green-700">{{ $item->total_grand }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">Data kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t bg-gray-50">{{ $penjualan->links() }}</div>
    </div>
</div>