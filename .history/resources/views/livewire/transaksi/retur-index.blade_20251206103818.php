<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-3 bg-red-50 border border-red-100 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-red-100 rounded-lg text-red-600">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <div>
                    <h4 class="font-bold text-red-900">Retur Barang</h4>
                    <p class="text-sm text-red-700">Data barang kembali mengurangi omzet penjualan.</p>
                </div>
            </div>

            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-file-excel mr-2 fa-lg"></i> Import Data Retur
            </button>
        </div>
    </div>

    <div
        class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-rose-500 focus:ring-rose-500 placeholder-gray-400 transition-colors"
                placeholder="Cari No Retur, Invoice Asal, atau Pelanggan...">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead
                    class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Tgl / No Retur</th>
                        <th class="px-6 py-4">Ref. Invoice</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Item</th>
                        <th class="px-6 py-4 text-center">Qty</th>
                        <th class="px-6 py-4 text-right">Nilai Retur</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($returs as $item)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $item->no_retur }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $item->tgl_retur ? \Carbon\Carbon::parse($item->tgl_retur)->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-600 text-xs font-mono">
                                <i class="fas fa-file-invoice mr-1 text-gray-400"></i>
                                {{ $item->no_inv ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $item->nama_pelanggan }}</div>
                            <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $item->kode_pelanggan }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $item->nama_item }}">
                            {{ $item->nama_item }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-gray-700">{{ $item->qty }}</span>
                            <span class="text-xs text-gray-400 ml-1">{{ $item->satuan_retur }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-rose-600">
                            - Rp {{ number_format((float)$item->total_grand, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus data retur ini?') || event.stopImmediatePropagation()"
                                class="text-gray-400 hover:text-red-600 transition" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-box-open text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium text-gray-500">Tidak ada data retur</p>
                                <p class="text-sm">Gunakan tombol Import untuk memasukkan data.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $returs->links() }}
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
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-undo text-rose-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">
                                Import Retur Penjualan
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Upload file rekap retur (.xlsx). Pastikan format kolom sesuai template.
                                </p>

                                <div
                                    class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition-colors relative">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                        <div class="text-sm text-gray-600">
                                            <label for="file-upload-retur-{{ $iteration }}"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-rose-600 hover:text-rose-500 focus-within:outline-none">
                                                <span>Pilih File</span>
                                                <input id="file-upload-retur-{{ $iteration }}" wire:model="file"
                                                    type="file" class="sr-only">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">XLSX, CSV up to 100MB</p>
                                    </div>
                                </div>

                                <div wire:loading wire:target="file" class="w-full mt-2 text-center">
                                    <span class="text-sm text-rose-600 font-medium animate-pulse">Mengupload
                                        file...</span>
                                </div>
                                <div wire:loading wire:target="import" class="w-full mt-2 text-center">
                                    <span class="text-sm text-indigo-600 font-medium animate-pulse">Memproses
                                        data...</span>
                                </div>

                                @error('file')
                                <div class="mt-2 text-sm text-red-600 bg-red-50 p-2 rounded border border-red-200">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled" type="button"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                        Proses Import
                    </button>
                    <button wire:click="closeImportModal" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>