<div class="space-y-6">

    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="w-full md:w-1/2">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.500ms="search" type="text"
                    class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400 transition-colors shadow-sm"
                    placeholder="Cari No Invoice, Pelanggan...">
            </div>
        </div>

        <div class="flex gap-3 w-full md:w-auto justify-end">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false"
                    class="bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2 hover:bg-gray-50">
                    <i class="fas fa-filter"></i> Filter Cabang
                </button>
                <div x-show="open"
                    class="absolute z-50 mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg p-2 max-h-60 overflow-y-auto">
                    @foreach($optCabang as $cab)
                    <label class="flex items-center px-2 py-1 hover:bg-indigo-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                            class="rounded border-gray-300 text-indigo-600 mr-2">
                        <span class="text-xs">{{ $cab }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                <i class="fas fa-file-excel mr-2"></i> Import Data
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[80vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="text-xs text-gray-600 uppercase bg-gray-100 sticky top-0 z-20 shadow-sm">
                    <tr>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] font-bold sticky left-0 z-30">
                            Cabang</th>
                        <th
                            class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px] sticky left-[100px] z-30 font-bold">
                            Trans No</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Status</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[90px]">Tanggal</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[200px]">Pelanggan</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[250px]">Item</th>
                        <th
                            class="px-3 py-3 border-b border-r bg-blue-50 text-blue-900 min-w-[80px] text-center font-bold">
                            Qty</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Harga Jual</th>
                        <th class="px-3 py-3 border-b border-r bg-green-50 text-green-900 text-right font-bold">Total
                        </th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-right">Margin</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100">Sales</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($penjualans as $item)
                    <tr class="hover:bg-indigo-50 transition-colors">
                        <td
                            class="px-3 py-2 border-r text-indigo-600 font-medium bg-white sticky left-0 z-10 group-hover:bg-inherit">
                            {{ $item->cabang }}</td>
                        <td
                            class="px-3 py-2 border-r font-mono bg-white sticky left-[100px] z-10 group-hover:bg-inherit">
                            {{ $item->trans_no }}</td>

                        <td class="px-3 py-2 border-r">{{ $item->status }}</td>
                        <td class="px-3 py-2 border-r">
                            {{ $item->tgl_penjualan ? \Carbon\Carbon::parse($item->tgl_penjualan)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-3 py-2 border-r font-medium text-gray-800 truncate max-w-[200px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-3 py-2 border-r truncate max-w-[250px]" title="{{ $item->nama_item }}">
                            {{ $item->nama_item }}</td>
                        <td class="px-3 py-2 border-r text-center font-bold">{{ $item->qty }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->nilai_jual_net }}</td>
                        <td class="px-3 py-2 border-r text-right font-bold text-green-700">{{ $item->total_grand }}</td>
                        <td
                            class="px-3 py-2 border-r text-right {{ str_contains($item->margin, '-') ? 'text-red-600' : 'text-blue-600' }}">
                            {{ $item->margin }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->sales_name }}</td>
                        <td class="px-3 py-2 border-r text-center">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <i class="fas fa-file-invoice fa-3x mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Belum Ada Data Penjualan</p>
                            <p class="text-sm">Import Excel untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $penjualans->links() }}
        </div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeImportModal"></div>
            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">Import Penjualan</h3>
                    <div
                        class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 relative cursor-pointer">
                        <label for="file-upload-sales-{{ $iteration }}"
                            class="relative cursor-pointer font-medium text-emerald-600 hover:text-emerald-500">
                            <span>Pilih File</span>
                            <input id="file-upload-sales-{{ $iteration }}" wire:model="file" type="file"
                                class="sr-only">
                        </label>
                    </div>
                    <div wire:loading wire:target="file" class="w-full mt-2 text-center text-sm text-emerald-600">
                        Mengupload...</div>
                    @error('file') <div class="mt-2 text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full sm:w-auto bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 disabled:opacity-50">
                        <span wire:loading.remove wire:target="import">Import</span>
                        <span wire:loading wire:target="import">Memproses...</span>
                    </button>
                    <button wire:click="closeImportModal"
                        class="mt-2 sm:mt-0 w-full sm:w-auto border border-gray-300 bg-white px-4 py-2 rounded-lg hover:bg-gray-50">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>