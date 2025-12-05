<div class="space-y-4">
    <style>
    [x-cloak] {
        display: none !important;
    }
    </style>

    <!-- Notifikasi -->
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
                <p class="font-bold">Gagal!</p>
                <p>{{ session('error') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-700 font-bold">&times;</button>
        </div>
        @endif
        @error('file')
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 shadow mb-4">
            <p class="font-bold">Validasi:</p>
            <p>{{ $message }}</p>
        </div>
        @enderror
    </div>

    <!-- Header & Upload -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="w-full md:w-1/3 relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No Bukti / Pelanggan..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm">
                <div class="absolute left-3 top-2.5 text-gray-400"><i class="fas fa-search"></i></div>
            </div>

            <div class="w-full md:w-auto bg-blue-50 p-3 rounded-lg border border-blue-200"
                x-data="{ isUploading: false, progress: 0, hasFile: false }"
                x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false"
                x-on:livewire-upload-error="isUploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">

                <div class="flex flex-col gap-2">
                    <div class="flex flex-col md:flex-row gap-2 items-center">
                        <input type="file" wire:model="file" id="upload_{{ $iteration }}"
                            x-on:change="hasFile = $event.target.files.length > 0"
                            class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-blue-600 file:text-white cursor-pointer" />

                        <button type="button" wire:click="import" wire:loading.attr="disabled"
                            :disabled="!hasFile || isUploading"
                            class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white px-4 py-1.5 rounded text-sm font-bold shadow flex items-center gap-2 whitespace-nowrap transition">
                            <span wire:loading.remove wire:target="import" x-show="!isUploading"><i
                                    class="fas fa-upload"></i> Import</span>
                            <span x-show="isUploading" x-cloak><i class="fas fa-spinner fa-spin"></i> Uploading... <span
                                    x-text="progress+'%'"></span></span>
                            <span wire:loading wire:target="import"><i class="fas fa-cog fa-spin"></i> Proses...</span>
                        </button>
                    </div>
                    <div x-show="isUploading" class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                            :style="`width: ${progress}%`"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Ringkas -->
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col overflow-hidden">
        <div class="flex-1 overflow-auto min-w-full">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-4 py-3 border-b">Tanggal</th>
                        <th class="px-4 py-3 border-b">No. Bukti</th>
                        <th class="px-4 py-3 border-b">Ref. Invoice</th>
                        <th class="px-4 py-3 border-b">Pelanggan</th>
                        <th class="px-4 py-3 border-b">Sales</th>
                        <th class="px-4 py-3 border-b">Penagih</th>
                        <th class="px-4 py-3 border-b text-right">Jumlah Terima</th>
                        <th class="px-4 py-3 border-b text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($collections as $item)
                    <tr class="hover:bg-blue-50">
                        <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                            {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3 font-bold text-blue-700">{{ $item->receive_no }}</td>
                        <td class="px-4 py-3 text-gray-600 font-mono text-xs">{{ $item->invoice_no }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $item->outlet_name }}<br><span
                                class="text-xs text-gray-500">{{ $item->code_customer }}</span></td>
                        <td class="px-4 py-3 text-gray-600">{{ $item->sales_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $item->penagih }}</td>
                        <td class="px-4 py-3 text-right font-bold text-green-700">
                            {{ number_format((float) str_replace(',', '', $item->receive_amount), 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center"><span
                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $item->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">Belum ada data Collection.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t bg-gray-50">{{ $collections->links() }}</div>
    </div>
</div>