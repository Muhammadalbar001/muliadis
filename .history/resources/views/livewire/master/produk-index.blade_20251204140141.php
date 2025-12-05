<div class="space-y-6">

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
        
        <!-- Error Validasi Langsung -->
        @error('file') 
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 shadow mb-4">
                <p class="font-bold">Peringatan:</p>
                <p>{{ $message }}</p>
            </div>
        @enderror
    </div>

    <!-- HEADER CONTROLS -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            
            <!-- Pencarian -->
            <div class="w-full md:w-1/3 relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari SKU atau Nama Item..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <!-- Form Upload -->
            <div class="w-full md:w-auto bg-gray-50 p-3 rounded-lg border border-dashed border-gray-300">
                <form wire:submit.prevent="import" class="flex flex-col md:flex-row gap-3 items-center">
                    
                    <!-- Input File dengan Iteration ID untuk Reset -->
                    <div class="relative group">
                        <input type="file" 
                            wire:model="file" 
                            id="upload_{{ $iteration }}"
                            class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-100 file:text-blue-700
                            hover:file:bg-blue-200 transition cursor-pointer"
                        />
                        <!-- Loading Indicator saat Upload (Client Side) -->
                        <div wire:loading wire:target="file" class="absolute -bottom-5 left-0 text-xs text-blue-600">
                            <i class="fas fa-spinner fa-spin"></i> Uploading...
                        </div>
                    </div>
                    
                    <!-- Tombol Import -->
                    <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:target="file, import"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-6 py-2 rounded-lg font-bold shadow transition flex items-center gap-2">
                        
                        <!-- Tampilan Normal -->
                        <span wire:loading.remove wire:target="import">
                            <i class="fas fa-file-import"></i> Import Excel
                        </span>
                        
                        <!-- Tampilan Loading -->
                        <span wire:loading wire:target="import">
                            <i class="fas fa-circle-notch fa-spin"></i> Memproses...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg overflow-x-auto border border-gray-200">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3">SKU</th>
                    <th class="px-6 py-3">Nama Item</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3 text-center">Stok</th>
                    <th class="px-6 py-3 text-right">Harga Beli</th>
                    <th class="px-6 py-3 text-center">Tgl Masuk</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produks as $item)
                <tr class="bg-white border-b hover:bg-blue-50 transition duration-150">
                    <td class="px-6 py-4 font-bold text-gray-800">{{ $item->sku }}</td>
                    <td class="px-6 py-4 font-medium">{{ $item->name_item }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-500">
                            {{ $item->kategori }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 {{ $item->stok > 10 ? 'bg-green-100 text-green-800 border-green-400' : 'bg-red-100 text-red-800 border-red-400' }} border rounded-full text-xs font-bold">
                            {{ number_format($item->stok, 0) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-mono text-gray-700">Rp {{ number_format($item->buy, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center text-xs text-gray-500">
                        {{ $item->created_at->format('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-box-open fa-2x text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-600">Belum Ada Data Produk</h3>
                            <p class="text-sm max-w-sm">Silakan upload file Excel (Format .xlsx / .csv) melalui form di atas untuk memulai.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 bg-gray-50 border-t">
            {{ $produks->links() }}
        </div>
    </div>
</div>