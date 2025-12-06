<div class="space-y-6">

    <div
        class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">

        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 transition-colors"
                placeholder="Cari nama supplier...">
        </div>

        <div class="flex gap-3 w-full md:w-auto">

            <button wire:click="syncFromProducts" wire:loading.attr="disabled"
                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition-all shadow-md hover:shadow-lg">

                <i wire:loading.remove wire:target="syncFromProducts" class="fas fa-sync-alt mr-2"></i>
                <i wire:loading wire:target="syncFromProducts" class="fas fa-spinner fa-spin mr-2"></i>

                Sync dari Produk
            </button>

            <button wire:click="create"
                class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <i class="fas fa-plus mr-2"></i> Manual
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead
                    class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 w-16">No</th>
                        <th class="px-6 py-4">Nama Supplier</th>
                        <th class="px-6 py-4">Kontak Person</th>
                        <th class="px-6 py-4">Telepon</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suppliers as $index => $item)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 text-gray-500">{{ $suppliers->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800">{{ $item->supplier_name }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $item->contact_person ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            @if($item->phone)
                            <span class="inline-flex items-center px-2 py-1 rounded bg-blue-50 text-blue-700 text-xs">
                                <i class="fas fa-phone mr-1"></i> {{ $item->phone }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $item->id }})"
                                    class="text-blue-600 hover:text-blue-800 font-medium text-xs">Edit</button>
                                <button wire:click="delete({{ $item->id }})"
                                    class="text-red-600 hover:text-red-800 font-medium text-xs ml-2">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-truck text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium text-gray-500">Belum ada data supplier</p>
                                <p class="text-sm">Klik tombol <span class="font-bold text-indigo-600">Sync dari
                                        Produk</span> untuk mengambil data otomatis.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $suppliers->links() }}
        </div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm"
                wire:click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $supplierId ? 'Edit Supplier' : 'Tambah Supplier Baru' }}
                    </h3>
                </div>

                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier</label>
                        <input type="text" wire:model="supplier_name"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="PT. Maju Mundur">
                        @error('supplier_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person (PIC)</label>
                        <input type="text" wire:model="contact_person"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Bpk. Budi">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon / WA</label>
                        <input type="text" wire:model="phone"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="0812...">
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="store" type="button"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base