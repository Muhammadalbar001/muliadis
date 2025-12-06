<div class="space-y-6">

    <div
        class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">

        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400 transition-colors"
                placeholder="Cari SKU atau Nama Produk...">
        </div>

        <div class="flex gap-3 w-full md:w-auto">
            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-file-excel mr-2 fa-lg"></i> Import Excel
            </button>

            <button wire:click="create"
                class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <i class="fas fa-plus mr-2"></i> Input Manual
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
                        <th class="px-6 py-4">SKU / Kode</th>
                        <th class="px-6 py-4">Nama Produk</th>
                        <th class="px-6 py-4">Satuan</th>
                        <th class="px-6 py-4 text-center">Stok (String)</th>
                        <th class="px-6 py-4 text-right">Harga Beli</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($produks as $index => $item)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 text-gray-500">{{ $produks->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-mono font-medium text-indigo-600">{{ $item->sku }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $item->name_item }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $item->kategori ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->oum ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @php $stokVal = (float)$item->stok; @endphp
                            @if($stokVal > 0)
                            <span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-700">
                                {{ $item->stok }}
                            </span>
                            @else
                            <span class="px-2 py-1 rounded text-xs font-bold bg-red-100 text-red-700">
                                {{ $item->stok }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-gray-700">
                            Rp {{ number_format((float)$item->buy, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="edit({{ $item->id }})"
                                class="text-blue-600 hover:text-blue-800 font-medium text-xs">Edit</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-file-excel text-4xl mb-4 text-emerald-200"></i>
                                <p class="text-lg font-medium text-gray-500">Belum ada data</p>
                                <p class="text-sm">Silakan klik tombol <span class="font-bold text-emerald-600">Import
                                        Excel</span> di atas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $produks->links() }}
        </div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                wire:click="closeImportModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-file-import text-emerald-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                Import Data Produk
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Pilih file Excel (.xlsx / .xls) untuk melakukan rekap data produk massal.
                                </p>

                                <div
                                    class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition-colors relative">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                        <div class="text-sm text-gray-600">
                                            <label for="file-upload-{{ $iteration }}"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                                <span>Upload a file</span>
                                                <input id="file-upload-{{ $iteration }}" wire:model="file" type="file"
                                                    class="sr-only">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">XLSX, XLS up to 100MB</p>
                                    </div>
                                </div>

                                <div wire:loading wire:target="file" class="w-full mt-2 text-center">
                                    <span class="text-sm text-emerald-600 font-medium animate-pulse">Sedang memproses
                                        file...</span>
                                </div>
                                <div wire:loading wire:target="import" class="w-full mt-2 text-center">
                                    <span class="text-sm text-indigo-600 font-medium animate-pulse">Sedang import ke
                                        database... (Mohon tunggu)</span>
                                </div>

                                @error('file')
                                <div class="mt-2 text-sm text-red-600 bg-red-50 p-2 rounded border border-red-200">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled" type="button"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none disabled:opacity-50 disabled:cursor-wait sm:ml-3 sm:w-auto sm:text-sm">
                        Mulai Import
                    </button>
                    <button wire:click="closeImportModal" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isInputOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm"
                wire:click="closeInputModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $productId ? 'Edit Data Produk' : 'Input Manual (Koreksi)' }}
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SKU</label>
                        <input type="text" wire:model="kode_item"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('kode_item') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input type="text" wire:model="nama_item"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Satuan (OUM)</label>
                            <input type="text" wire:model="satuan_jual"
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga Beli</label>
                            <input type="number" wire:model="harga_jual"
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="store"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                    <button wire:click="closeInputModal"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>