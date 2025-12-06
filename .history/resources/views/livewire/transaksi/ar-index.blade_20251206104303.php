<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
            class="md:col-span-3 bg-orange-50 border border-orange-100 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-orange-100 rounded-lg text-orange-600">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <h4 class="font-bold text-orange-900">Account Receivable (AR)</h4>
                    <p class="text-sm text-orange-700">Import data piutang untuk memantau tagihan yang belum terbayar.
                    </p>
                </div>
            </div>

            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-file-import mr-2"></i> Import AR
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
                class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-orange-500 focus:ring-orange-500 placeholder-gray-400 transition-colors"
                placeholder="Cari Invoice, Customer, Sales...">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead
                    class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Invoice / Tgl</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-center">Umur (Hari)</th>
                        <th class="px-6 py-4 text-right">Nilai Awal</th>
                        <th class="px-6 py-4 text-right">Sisa (Balance)</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ars as $item)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $item->no_inv }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $item->ar_date ? \Carbon\Carbon::parse($item->ar_date)->format('d/m/Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $item->customer_name }}</div>
                            <div class="text-xs text-gray-400">{{ $item->sales_name }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d M Y') : ($item->top ?? '-') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->umur > 60)
                            <span
                                class="px-2 py-1 rounded-full bg-red-100 text-red-700 font-bold text-xs">{{ $item->umur }}</span>
                            @elseif($item->umur > 30)
                            <span
                                class="px-2 py-1 rounded-full bg-orange-100 text-orange-700 font-bold text-xs">{{ $item->umur }}</span>
                            @else
                            <span
                                class="px-2 py-1 rounded-full bg-green-100 text-green-700 font-bold text-xs">{{ $item->umur }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-gray-500">
                            {{ number_format((float)$item->bill_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold {{ (float)$item->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                Rp {{ number_format((float)$item->balance, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus data AR ini?') || event.stopImmediatePropagation()"
                                class="text-gray-400 hover:text-red-600 transition">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-folder-open text-4xl mb-4 text-orange-200"></i>
                            <p>Data AR Kosong. Silakan Import.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $ars->links() }}
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
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-file-invoice-dollar text-orange-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Import Data AR</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">File Excel (.xlsx) data piutang berjalan.</p>

                                <div
                                    class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 cursor-pointer relative">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                                        <div class="text-sm text-gray-600">
                                            <label for="file-upload-ar-{{ $iteration }}"
                                                class="font-medium text-orange-600 hover:text-orange-500">
                                                <span>Upload File</span>
                                                <input id="file-upload-ar-{{ $iteration }}" wire:model="file"
                                                    type="file" class="sr-only">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div wire:loading wire:target="file"
                                    class="w-full mt-2 text-center text-sm text-orange-600">Uploading...</div>
                                @error('file') <div class="mt-2 text-red-600 text-sm">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 sm:ml-3 sm:w-auto sm:text-sm">Import</button>
                    <button wire:click="closeImportModal"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>