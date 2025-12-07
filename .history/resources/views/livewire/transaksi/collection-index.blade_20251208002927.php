<div class="space-y-6">

    <div
        class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-end gap-4">

        <div class="w-full md:w-3/4 grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="relative md:col-span-1">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-9 pr-4 py-2 w-full border-gray-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400"
                    placeholder="No Bukti, Invoice...">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm flex items-center justify-between hover:bg-gray-50">
                    <span
                        class="truncate">{{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang) . ' Terpilih' }}</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                </button>
                <div x-show="open"
                    class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg p-2 max-h-48 overflow-y-auto"
                    style="display: none;">
                    @foreach($optCabang as $opt)
                    <label class="flex items-center px-2 py-1 hover:bg-emerald-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $opt }}" wire:model.live="filterCabang"
                            class="rounded border-gray-300 text-emerald-600 mr-2">
                        <span class="text-xs text-gray-700">{{ $opt }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm flex items-center justify-between hover:bg-gray-50">
                    <span
                        class="truncate">{{ empty($filterPenagih) ? 'Semua Penagih' : count($filterPenagih) . ' Terpilih' }}</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                </button>
                <div x-show="open"
                    class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg p-2 max-h-48 overflow-y-auto"
                    style="display: none;">
                    @foreach($optPenagih as $opt)
                    <label class="flex items-center px-2 py-1 hover:bg-emerald-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $opt }}" wire:model.live="filterPenagih"
                            class="rounded border-gray-300 text-emerald-600 mr-2">
                        <span class="text-xs text-gray-700">{{ $opt }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm flex items-center justify-between hover:bg-gray-50">
                    <span
                        class="truncate">{{ empty($filterSales) ? 'Semua Sales' : count($filterSales) . ' Terpilih' }}</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                </button>
                <div x-show="open"
                    class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg p-2 max-h-48 overflow-y-auto"
                    style="display: none;">
                    @foreach($optSales as $opt)
                    <label class="flex items-center px-2 py-1 hover:bg-emerald-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $opt }}" wire:model.live="filterSales"
                            class="rounded border-gray-300 text-emerald-600 mr-2">
                        <span class="text-xs text-gray-700">{{ $opt }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <button wire:click="openImportModal"
            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all flex items-center">
            <i class="fas fa-file-import mr-2"></i> Import Collection
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[75vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200 uppercase">
                    <tr>
                        <th class="px-4 py-3 border-r">Cabang</th>
                        <th class="px-4 py-3 border-r">Receive No / Tgl</th>
                        <th class="px-4 py-3 border-r">Invoice</th>
                        <th class="px-4 py-3 border-r">Penagih</th>
                        <th class="px-4 py-3 border-r">Pelanggan</th>
                        <th class="px-4 py-3 border-r text-right bg-emerald-50 text-emerald-900">Nilai Bayar</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($collections as $item)
                    <tr class="hover:bg-emerald-50 transition-colors">
                        <td class="px-4 py-2 border-r text-emerald-600 font-medium">{{ $item->cabang }}</td>
                        <td class="px-4 py-2 border-r font-bold text-gray-800">
                            {{ $item->receive_no }}
                            <div class="text-[10px] text-gray-400 font-normal">
                                {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-4 py-2 border-r font-mono text-gray-600">{{ $item->invoice_no }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->penagih }}</td>
                        <td class="px-4 py-2 border-r">
                            <div class="font-medium text-gray-800">{{ $item->outlet_name }}</div>
                            <div class="text-[10px] text-gray-400">{{ $item->sales_name }}</div>
                        </td>
                        <td class="px-4 py-2 border-r text-right font-bold text-emerald-700 bg-emerald-50/50">
                            {{ number_format((float)$item->receive_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                class="text-gray-400 hover:text-red-600 transition"><i
                                    class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Data Collection Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $collections->links() }}</div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeImportModal"></div>
            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Import Collection</h3>
                    <div
                        class="mt-2 border-2 border-dashed rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50 relative">
                        <input type="file" wire:model="file"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-emerald-500 mb-2"></i>
                        <span class="block text-sm font-medium text-gray-700">Klik Upload Excel</span>
                    </div>
                    <div wire:loading wire:target="file" class="w-full mt-2 text-center text-xs text-emerald-600">
                        Uploading...</div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full sm:w-auto bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 disabled:opacity-50">Import</button>
                    <button wire:click="closeImportModal"
                        class="mt-2 sm:mt-0 w-full sm:w-auto border border-gray-300 bg-white px-4 py-2 rounded-lg hover:bg-gray-50">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>