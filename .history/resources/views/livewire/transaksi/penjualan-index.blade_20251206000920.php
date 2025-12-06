<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
            class="md:col-span-3 bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h4 class="font-bold text-indigo-900">Tips Import</h4>
                    <p class="text-sm text-indigo-700">Pastikan format Excel sesuai template (Header: Cabang, Trans No,
                        Kode Pelanggan, dst).</p>
                </div>
            </div>
            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-file-excel mr-2 fa-lg"></i> Import Data Penjualan
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
                class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400 transition-colors"
                placeholder="Cari No Invoice, Pelanggan, atau Sales...">
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <input type="date" class="border-gray-200 rounded-lg text-sm text-gray-600 focus:ring-indigo-500 py-2.5">
            <span class="text-gray-400">-</span>
            <input type="date" class="border-gray-200 rounded-lg text-sm text-gray-600 focus:ring-indigo-500 py-2.5">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead
                    class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Tgl / Trans No</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Sales</th>
                        <th class="px-6 py-4 text-right">Total Net</th>
                        <th class="px-6 py-4 text-right">Total Grand</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($penjualans as $item)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $item->trans_no }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $item->tgl_penjualan ? \Carbon\Carbon::parse($item->tgl_penjualan)->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-indigo-600">{{ $item->nama_pelanggan }}</div>
                            <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $item->kode_pelanggan }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $item->sales_name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right text-gray-600">
                            Rp {{ number_format($item->nilai_jual_net ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">
                            Rp {{ number_format($item->total_grand ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status_pay == 'Lunas' || $item->sisa_piutang <= 0) <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Lunas
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Piutang
                                </span>
                                @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Yakin hapus data ini?') || event.stopImmediatePropagation()"
                                class="text-red-400 hover:text-red-600 transition" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-receipt text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium text-gray-500">Tidak ada data penjualan</p>
                                <p class="text-sm">Mulailah dengan mengimport file Excel.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $penjualans->links() }}
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
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-file-invoice text-blue-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                Import Transaksi Penjualan
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Upload file Excel berisi rekap penjualan. Sistem akan otomatis melakukan
                                    <i>upsert</i> (update jika ada, insert jika baru) berdasarkan <b>No Transaksi</b>.
                                </p>

                                <div
                                    class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition-colors relative">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                        <div class="text-sm text-gray-600">
                                            <label for="file-upload-sales-{{ $iteration }}"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                <span>Pilih File Excel</span>
                                                <input id="file-upload-sales-{{ $iteration }}" wire:model="file"
                                                    type="file" class="sr-only">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">XLSX, CSV up to 100MB</p>
                                    </div>
                                </div>

                                <div wire:loading wire:target="file" class="w-full mt-2 text-center">
                                    <span class="text-sm text-blue-600 font-medium animate-pulse">Mengupload
                                        file...</span>
                                </div>
                                <div wire:loading wire:target="import" class="w-full mt-2 text-center">
                                    <span class="text-sm text-indigo-600 font-medium animate-pulse">Sedang memproses
                                        data... (Bisa memakan waktu)</span>
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
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                        Proses Import
                    </button>
                    <button wire:click="closeImportModal" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>