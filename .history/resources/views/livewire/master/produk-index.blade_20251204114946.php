<div class="space-y-6">

    <!-- Flash Message Success -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Flash Message Error -->
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Gagal!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header Controls -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-lg shadow">
        
        <!-- Search Bar -->
        <div class="w-full md:w-1/3 relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Nama Item atau SKU..." 
                class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="absolute left-3 top-2.5 text-gray-400">
                <i class="fas fa-search"></i>
            </div>
        </div>

        <!-- Import Form -->
        <div class="w-full md:w-auto">
            <div class="flex flex-col gap-1"
                 x-data="{ isUploading: false, progress: 0 }" 
                 x-on:livewire-upload-start="isUploading = true"
                 x-on:livewire-upload-finish="isUploading = false"
                 x-on:livewire-upload-error="isUploading = false"
                 x-on:livewire-upload-progress="progress = $event.detail.progress">
                
                <form wire:submit.prevent="import" class="flex items-center gap-2">
                    <input type="file" wire:model="file" class="text-sm text-slate-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100
                    "/>
                    
                    <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 disabled:opacity-50 shadow-md transition"
                        wire:loading.attr="disabled" wire:target="import">
                        <span wire:loading.remove wire:target="import"><i class="fas fa-file-import"></i> Import</span>
                        <span wire:loading wire:target="import"><i class="fas fa-spinner fa-spin"></i> Proses...</span>
                    </button>
                </form>

                <!-- ERROR MESSAGE DISINI (Penting) -->
                @error('file') 
                    <span class="text-red-500 text-xs italic">{{ $message }}</span> 
                @enderror

                <!-- Progress Bar -->
                <div x-show="isUploading" class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                    <div class="bg-blue-600 h-1.5 rounded-full" :style="`width: ${progress}%`"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Data -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg overflow-x-auto border border-gray-200">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3">SKU</th>
                    <th class="px-6 py-3">Nama Item</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3 text-center">Stok</th>
                    <th class="px-6 py-3">Satuan</th>
                    <th class="px-6 py-3 text-right">Harga Beli</th>
                    <th class="px-6 py-3 text-center">Supplier</th>
                    <th class="px-6 py-3 text-center">Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produks as $item)
                <tr class="bg-white border-b hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->sku }}</td>
                    <td class="px-6 py-4">{{ $item->name_item }}</td>
                    <td class="px-6 py-4">{{ $item->kategori }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 {{ $item->stok > 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full text-xs font-bold">
                            {{ number_format($item->stok, 0) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $item->oum }}</td>
                    <td class="px-6 py-4 text-right">Rp {{ number_format($item->buy, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">{{ $item->supplier }}</td>
                    <td class="px-6 py-4 text-center text-xs text-gray-400">
                        {{ $item->updated_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-gray-400 bg-gray-50">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-box-open fa-3x mb-3 text-gray-300"></i>
                            <p class="text-lg font-semibold">Belum ada data produk</p>
                            <p class="text-sm">Silakan upload file Excel untuk memulai.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="p-4 bg-gray-50 border-t">
            {{ $produks->links() }}
        </div>
    </div>
</div>