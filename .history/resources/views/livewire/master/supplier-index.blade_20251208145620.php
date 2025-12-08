<div class="space-y-6 font-jakarta">

    <div
        class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-end gap-4">

        <div class="w-full md:w-1/2">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Daftar Supplier</h2>
            <p class="text-xs text-slate-500 mb-3">Data prinsipal atau pemasok barang.</p>
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 pr-4 py-2.5 w-full border-slate-200 rounded-xl text-sm focus:ring-pink-500 focus:border-pink-500 placeholder-slate-400 bg-slate-50"
                    placeholder="Cari Kode atau Nama Supplier...">
                <i class="fas fa-search absolute left-3.5 top-3 text-slate-400 text-sm"></i>
            </div>
        </div>

        <div class="flex gap-2">
            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5 hover:shadow-pink-200">
                <i class="fas fa-truck-loading mr-2"></i> Import Excel
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-100 text-xs">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Kode Supplier</th>
                        <th class="px-6 py-4">Nama Supplier (Principal)</th>
                        <th class="px-6 py-4">Cabang</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($suppliers as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3 text-center text-slate-400 text-xs">{{ $suppliers->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-3 font-mono text-xs text-pink-600 font-bold">{{ $item->kode_supplier }}</td>
                        <td class="px-6 py-3 font-bold text-slate-700">{{ $item->nama_supplier }}</td>
                        <td class="px-6 py-3 text-slate-600 text-xs">{{ $item->cabang }}</td>
                        <td class="px-6 py-3 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                @click="$dispatch('confirm-delete', { method: 'deleteSupplier', id: {{ $item->id }} })"
                                class="text-slate-300 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 bg-slate-50/50">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-truck text-4xl mb-3 text-slate-300"></i>
                                <p class="text-sm font-medium">Data supplier kosong.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $suppliers->links() }}
        </div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                wire:click="closeImportModal"></div>

            <div
                class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-pink-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-file-import text-pink-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-slate-900">Import Master Supplier</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 mb-4">Upload file Excel daftar supplier.</p>

                                <div
                                    class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:bg-pink-50 hover:border-pink-400 cursor-pointer transition-colors group relative">
                                    <div class="space-y-1 text-center">
                                        <i
                                            class="fas fa-cloud-upload-alt text-4xl text-slate-400 group-hover:text-pink-500 transition-colors"></i>
                                        <div class="flex text-sm text-slate-600 justify-center mt-2">
                                            <label for="file-upload-supp"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-pink-600 hover:text-pink-500 focus-within:outline-none">
                                                <span>Pilih File</span>
                                                <input id="file-upload-supp" wire:model="file" type="file"
                                                    class="sr-only">
                                            </label>
                                        </div>
                                    </div>
                                    @if($file)
                                    <div
                                        class="absolute inset-0 bg-pink-50 bg-opacity-90 flex flex-col items-center justify-center rounded-xl">
                                        <i class="fas fa-check text-pink-500 text-2xl mb-1"></i>
                                        <span
                                            class="text-xs font-bold text-slate-700">{{ $file->getClientOriginalName() }}</span>
                                    </div>
                                    @endif
                                </div>

                                <div class="mt-4 flex items-center p-3 bg-red-50 rounded-lg border border-red-100">
                                    <input id="resetData" type="checkbox" wire:model="resetData"
                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-slate-300 rounded cursor-pointer">
                                    <label for="resetData" class="ml-3 block text-sm text-red-800 cursor-pointer">
                                        <span class="font-bold">Reset Data?</span>
                                        <span class="text-xs block text-red-600">Hapus semua data supplier lama sebelum
                                            import.</span>
                                    </label>
                                </div>

                                <div wire:loading wire:target="import"
                                    class="w-full mt-2 text-center text-xs text-pink-600 font-bold animate-pulse">Proses
                                    Import...</div>
                                @error('file') <div class="mt-2 text-red-500 text-xs">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled" type="button"
                        class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-pink-600 text-base font-medium text-white hover:bg-pink-700 sm:ml-3 sm:w-auto sm:text-sm">Import</button>
                    <button wire:click="closeImportModal" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>