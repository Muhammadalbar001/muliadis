<div class="space-y-6">

    <div
        class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">

        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-2/3">
            <div class="relative w-full sm:w-64">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-9 pr-4 py-2 w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400"
                    placeholder="Cari Supplier...">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>

            <div class="w-full sm:w-48">
                <select wire:model.live="filterCabang"
                    class="w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 py-2 text-gray-600">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>
        </div>

        <div class="flex gap-2">
            <button wire:click="syncFromProducts" wire:loading.attr="disabled"
                class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-sm font-bold rounded-lg transition-all border border-indigo-200">
                <span wire:loading.remove wire:target="syncFromProducts"><i class="fas fa-sync-alt mr-2"></i> Sync
                    Produk</span>
                <span wire:loading wire:target="syncFromProducts"><i class="fas fa-spinner fa-spin mr-2"></i>
                    Syncing...</span>
            </button>

            <button wire:click="create"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all">
                <i class="fas fa-plus mr-2"></i> Baru
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-500 uppercase bg-gray-50 font-bold border-b border-gray-200 text-xs">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Cabang</th>
                        <th class="px-6 py-4">Nama Supplier</th>
                        <th class="px-6 py-4">Kontak (PIC)</th>
                        <th class="px-6 py-4">Telepon</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suppliers as $index => $item)
                    <tr class="hover:bg-indigo-50 transition-colors">
                        <td class="px-6 py-4 text-center text-gray-500">{{ $suppliers->firstItem() + $index }}</td>
                        <td class="px-6 py-4 text-indigo-600 font-medium text-xs uppercase tracking-wide">
                            {{ $item->cabang }}</td>
                        <td class="px-6 py-4 font-bold text-gray-700">{{ $item->supplier_name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->contact_person ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            @if($item->phone)
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-green-50 text-green-700 text-xs font-medium border border-green-100">
                                <i class="fas fa-phone text-[10px]"></i> {{ $item->phone }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-3">
                                <button wire:click="edit({{ $item->id }})"
                                    class="text-blue-600 hover:text-blue-800 transition transform hover:scale-110"><i
                                        class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                    class="text-red-500 hover:text-red-700 transition transform hover:scale-110"><i
                                        class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-truck fa-3x mb-3 text-gray-200"></i>
                            <p>Belum ada data supplier.</p>
                            <p class="text-xs mt-1">Klik 'Sync Produk' untuk mengambil dari data produk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $suppliers->links() }}
        </div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $supplierId ? 'Edit Supplier' : 'Tambah Supplier Baru' }}</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cabang</label>
                        <input type="text" wire:model="cabang"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 sm:text-sm">
                        @error('cabang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier</label>
                        <input type="text" wire:model="supplier_name"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 sm:text-sm">
                        @error('supplier_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PIC / Kontak</label>
                            <input type="text" wire:model="contact_person"
                                class="w-full rounded-lg border-gray-300 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                            <input type="text" wire:model="phone" class="w-full rounded-lg border-gray-300 sm:text-sm">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="store"
                        class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">Simpan</button>
                    <button wire:click="closeModal"
                        class="mt-2 sm:mt-0 w-full sm:w-auto border border-gray-300 bg-white px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>