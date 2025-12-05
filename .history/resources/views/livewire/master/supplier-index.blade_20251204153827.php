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
    </div>

    <!-- HEADER & KONTROL -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

            <!-- Pencarian -->
            <div class="w-full md:w-1/3 relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Nama Supplier..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-2 items-center">
                <!-- Tombol Sinkronisasi dari Produk -->
                <button wire:click="syncFromProducts" wire:loading.attr="disabled" wire:target="syncFromProducts"
                    class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white px-4 py-1.5 rounded text-sm font-bold shadow transition flex items-center gap-2 whitespace-nowrap">
                    <span wire:loading.remove wire:target="syncFromProducts"><i class="fas fa-sync-alt"></i>
                        Sinkronisasi dari Produk</span>
                    <span wire:loading wire:target="syncFromProducts"><i class="fas fa-spinner fa-spin"></i>
                        Sinkronisasi...</span>
                </button>

                <!-- Tombol Tambah Manual (Placeholder) -->
                <button
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded text-sm font-bold shadow transition flex items-center gap-2 whitespace-nowrap">
                    <i class="fas fa-plus"></i> Tambah Baru
                </button>
            </div>
        </div>
    </div>

    <!-- TABEL DATA SUPPLIER -->
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col">
        <div class="flex-1 overflow-x-auto overflow-y-auto min-w-full" style="max-height: calc(100vh - 200px);">
            <table class="min-w-max text-xs text-left border-collapse table-auto w-full">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-20">
                    <tr>
                        <th class="px-4 py-2 border-b border-r min-w-[50px]">#</th>
                        <th class="px-4 py-2 border-b border-r min-w-[300px]">Nama Supplier</th>
                        <th class="px-4 py-2 border-b border-r min-w-[150px]">Contact Person</th>
                        <th class="px-4 py-2 border-b border-r min-w-[150px]">Telepon</th>
                        <th class="px-4 py-2 border-b border-r min-w-[150px] text-center">Data Masuk</th>
                        <th class="px-4 py-2 border-b min-w-[100px] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($suppliers as $index => $supplier)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-4 py-2 border-r">{{ $suppliers->firstItem() + $index }}</td>
                        <td class="px-4 py-2 border-r font-medium text-gray-900">{{ $supplier->supplier_name }}</td>
                        <td class="px-4 py-2 border-r">{{ $supplier->contact_person ?? '-' }}</td>
                        <td class="px-4 py-2 border-r">{{ $supplier->phone ?? '-' }}</td>
                        <td class="px-4 py-2 border-r text-center text-gray-500">
                            {{ $supplier->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-2 text-center flex gap-2 justify-center">
                            <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="delete({{ $supplier->id }})"
                                wire:confirm="Yakin ingin menghapus supplier {{ $supplier->supplier_name }}?"
                                class="text-red-600 hover:text-red-800" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <i class="fas fa-truck-loading fa-3x mb-2"></i>
                            <h3 class="text-lg font-bold text-gray-600">Belum Ada Data Supplier</h3>
                            <p class="text-sm">Silakan klik tombol **Sinkronisasi dari Produk** untuk mengisi data
                                otomatis.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination di Bawah -->
        <div class="p-2 border-t bg-gray-50">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>