<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
            class="md:col-span-3 bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-900">Collection / Pembayaran</h4>
                    <p class="text-sm text-emerald-700">Data pelunasan piutang dari customer.</p>
                </div>
            </div>

            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-file-import mr-2"></i> Import Collection
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
                class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-400 transition-colors"
                placeholder="Cari Invoice, Outlet, Sales...">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead
                    class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Tgl Bayar</th>
                        <th class="px-6 py-4">No Bukti / Invoice</th>
                        <th class="px-6 py-4">Outlet / Customer</th>
                        <th class="px-6 py-4">Sales / Penagih</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Total Terima</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($collections as $item)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="text-gray-800">
                                {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-mono text-emerald-600 font-medium">{{ $item->receive_no }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $item->invoice_no }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $item->outlet_name }}</div>
                            <div class="text-xs text-gray-400 font-mono">{{ $item->code_customer }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $item->sales_name ?? '-' }}
                            <div class="text-xs text-gray-400">{{ $item->penagih ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status == 'C' || $item->status == 'Cleared')
                            <span
                                class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-bold">Cleared</span>
                            @else
                            <span
                                class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-600 text-xs">{{ $item->status ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">
                            Rp {{ number_format((float)$item->receive_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus data collection ini?') || event.stopImmediatePropagation()"
                                class="text-gray-400 hover:text-red-600 transition">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-wallet text-4xl mb-4 text-emerald-200"></i>
                            <p>Data Collection Kosong.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $collections->links() }}
        </div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                wire:click="closeImportModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-file-import text-emerald-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Import Data Collection</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">File Excel (.xlsx) rekap pembayaran.</p>

                                <div
                                    class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 cursor-pointer relative">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                                        <div class="text-sm text-gray-600">
                                            <label for="file-upload-col-{{ $iteration }}"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                                <span>Upload File</span>
                                                <input id="file-upload-col-{{ $iteration }}" wire:model="file"
                                                    type="file" class="sr-only">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div wire:loading wire:target="file"
                                    class="w-full mt-2 text-center text-sm text-emerald-600">Uploading...</div>
                                @error('file') <div class="mt-2 text-red-600 text-sm">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">Import</button>
                    <button wire:click="closeImportModal"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>