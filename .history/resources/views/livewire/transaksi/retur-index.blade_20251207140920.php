<div class="space-y-6">

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 pr-4 py-2 w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400"
                    placeholder="Cari No Retur, Pelanggan...">
            </div>

            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-md transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-import mr-2"></i> Import Retur
            </button>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <div class="flex items-center mb-2 gap-2">
                <i class="fas fa-filter text-indigo-600 text-sm"></i>
                <span class="text-xs font-bold uppercase text-gray-600 tracking-wider">Filter Lanjutan</span>

                @if(!empty($filterCabang) || !empty($filterSales) || !empty($filterSupplier) || !empty($filterStatus))
                <button wire:click="resetFilter"
                    class="ml-auto text-xs text-red-500 hover:text-red-700 underline flex items-center">
                    <i class="fas fa-times mr-1"></i> Reset
                </button>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg text-xs font-medium flex items-center justify-between hover:bg-gray-50">
                        <span class="truncate">
                            {{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang) . ' Terpilih' }}
                        </span>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg p-2 max-h-48 overflow-y-auto">
                        @foreach($optCabang as $opt)
                        <label class="flex items-center px-2 py-1 hover:bg-indigo-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $opt }}" wire:model.live="filterCabang"
                                class="rounded border-gray-300 text-indigo-600 mr-2 h-3 w-3">
                            <span class="text-xs text-gray-700">{{ $opt }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg text-xs font-medium flex items-center justify-between hover:bg-gray-50">
                        <span class="truncate">
                            {{ empty($filterSales) ? 'Semua Sales' : count($filterSales) . ' Terpilih' }}
                        </span>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg p-2 max-h-48 overflow-y-auto">
                        @foreach($optSales as $opt)
                        <label class="flex items-center px-2 py-1 hover:bg-indigo-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $opt }}" wire:model.live="filterSales"
                                class="rounded border-gray-300 text-indigo-600 mr-2 h-3 w-3">
                            <span class="text-xs text-gray-700">{{ $opt }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg text-xs font-medium flex items-center justify-between hover:bg-gray-50">
                        <span class="truncate">
                            {{ empty($filterSupplier) ? 'Semua Supplier' : count($filterSupplier) . ' Terpilih' }}
                        </span>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg p-2 max-h-48 overflow-y-auto">
                        @foreach($optSupplier as $opt)
                        <label class="flex items-center px-2 py-1 hover:bg-indigo-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $opt }}" wire:model.live="filterSupplier"
                                class="rounded border-gray-300 text-indigo-600 mr-2 h-3 w-3">
                            <span class="text-xs text-gray-700">{{ $opt }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-lg text-xs font-medium flex items-center justify-between hover:bg-gray-50">
                        <span class="truncate">
                            {{ empty($filterStatus) ? 'Semua Status' : count($filterStatus) . ' Terpilih' }}
                        </span>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg p-2 max-h-48 overflow-y-auto">
                        @foreach($optStatus as $opt)
                        <label class="flex items-center px-2 py-1 hover:bg-indigo-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $opt }}" wire:model.live="filterStatus"
                                class="rounded border-gray-300 text-indigo-600 mr-2 h-3 w-3">
                            <span class="text-xs text-gray-700">{{ $opt }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[75vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="text-xs text-gray-600 uppercase bg-gray-50 sticky top-0 z-20">
                    <tr>
                        <th class="px-4 py-3 font-bold border-b">No Retur / Tgl</th>
                        <th class="px-4 py-3 font-bold border-b">Pelanggan</th>
                        <th class="px-4 py-3 font-bold border-b">Item Barang</th>
                        <th class="px-4 py-3 font-bold border-b text-center">Qty</th>
                        <th class="px-4 py-3 font-bold border-b text-right">Nilai Net</th>
                        <th class="px-4 py-3 font-bold border-b">Status</th>
                        <th class="px-4 py-3 font-bold border-b">Sales / Supplier</th>
                        <th class="px-4 py-3 font-bold border-b">Cabang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($returs as $item)
                    <tr class="hover:bg-indigo-50 transition-colors">
                        <td class="px-4 py-2 border-r">
                            <div class="font-bold text-gray-800">{{ $item->no_retur }}</div>
                            <div class="text-[10px] text-gray-500">
                                {{ $item->tgl_retur ? \Carbon\Carbon::parse($item->tgl_retur)->format('d/m/Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-4 py-2 border-r">
                            <div class="font-medium text-gray-700 truncate max-w-[150px]"
                                title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</div>
                            <div class="text-[10px] text-gray-400">{{ $item->kode_pelanggan }}</div>
                        </td>
                        <td class="px-4 py-2 border-r truncate max-w-[200px]" title="{{ $item->nama_item }}">
                            {{ $item->nama_item }}
                        </td>
                        <td class="px-4 py-2 border-r text-center font-bold">{{ $item->qty }}</td>
                        <td class="px-4 py-2 border-r text-right font-mono">
                            {{ number_format((float)$item->nilai_retur_net, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border-r">
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">{{ $item->status }}</span>
                        </td>
                        <td class="px-4 py-2 border-r">
                            <div class="text-xs">{{ $item->sales_name }}</div>
                            <div class="text-[9px] text-gray-400 truncate max-w-[100px]">{{ $item->supplier }}</div>
                        </td>
                        <td class="px-4 py-2 text-center text-indigo-600 font-bold text-[10px]">{{ $item->cabang }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-inbox fa-3x mb-3 text-gray-200"></i>
                            <p>Data Retur Kosong</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-2 border-t bg-gray-50">
            {{ $returs->links() }}
        </div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeImportModal"></div>
            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Import Data Retur</h3>
                    <div
                        class="mt-2 border-2 border-dashed rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50 relative">
                        <input type="file" wire:model="file"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-indigo-500 mb-2"></i>
                        <span class="block text-sm font-medium text-gray-700">Klik Upload Excel</span>
                    </div>
                    <div wire:loading wire:target="file" class="w-full mt-2 text-center text-xs text-indigo-600">
                        Uploading...</div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                        <span wire:loading.remove wire:target="import">Mulai Import</span>
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