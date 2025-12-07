<div class="space-y-6">

    <div
        class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-end gap-4">
        <div class="w-full md:w-2/3 grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="relative md:col-span-1">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-9 pr-4 py-2 w-full border-gray-200 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 placeholder-gray-400"
                    placeholder="Invoice, Pelanggan...">
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
                    <label class="flex items-center px-2 py-1 hover:bg-orange-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $opt }}" wire:model.live="filterCabang"
                            class="rounded border-gray-300 text-orange-600 mr-2">
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
                    <label class="flex items-center px-2 py-1 hover:bg-orange-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $opt }}" wire:model.live="filterSales"
                            class="rounded border-gray-300 text-orange-600 mr-2">
                        <span class="text-xs text-gray-700">{{ $opt }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <button wire:click="openImportModal"
            class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all flex items-center">
            <i class="fas fa-file-import mr-2"></i> Import AR
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[75vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200 uppercase">
                    <tr>
                        <th class="px-4 py-3 border-r">Cabang</th>
                        <th class="px-4 py-3 border-r">Invoice</th>
                        <th class="px-4 py-3 border-r">Pelanggan</th>
                        <th class="px-4 py-3 border-r">Jatuh Tempo</th>
                        <th class="px-4 py-3 border-r text-center">Umur (Hari)</th>
                        <th class="px-4 py-3 border-r text-right">Nilai Awal</th>
                        <th class="px-4 py-3 border-r text-right bg-orange-50 text-orange-900">Sisa Piutang</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ars as $item)
                    <tr class="hover:bg-orange-50 transition-colors">
                        <td class="px-4 py-2 border-r text-orange-600 font-medium">{{ $item->cabang }}</td>
                        <td class="px-4 py-2 border-r font-mono text-gray-800">
                            {{ $item->no_penjualan }}
                            <div class="text-[10px] text-gray-400 font-normal">
                                {{ $item->tgl_penjualan ? \Carbon\Carbon::parse($item->tgl_penjualan)->format('d/m/Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-4 py-2 border-r">
                            <div class="font-medium text-gray-800">{{ $item->pelanggan_name }}</div>
                            <div class="text-[10px] text-gray-400">{{ $item->sales_name }}</div>
                        </td>
                        <td
                            class="px-4 py-2 border-r {{ $item->jatuh_tempo && \Carbon\Carbon::parse($item->jatuh_tempo)->isPast() ? 'text-red-600 font-bold' : '' }}">
                            {{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-2 border-r text-center">
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold {{ (int)$item->umur_piutang > 30 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }}">{{ $item->umur_piutang }}</span>
                        </td>
                        <td class="px-4 py-2 border-r text-right text-gray-500">
                            {{ number_format((float)$item->total_nilai, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border-r text-right font-bold text-gray-800">
                            {{ number_format((float)$item->nilai, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                class="text-gray-400 hover:text-red-600 transition"><i
                                    class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">Data Piutang Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $ars->links() }}</div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeImportModal"></div>
            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Import Data AR</h3>
                    <div
                        class="mt-2 border-2 border-dashed rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50 relative">
                        <input type="file" wire:model="file"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-orange-500 mb-2"></i>
                        <span class="block text-sm font-medium text-gray-700">Klik Upload Excel</span>
                    </div>
                    <div wire:loading wire:target="file" class="w-full mt-2 text-center text-xs text-orange-600">
                        Uploading...</div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full sm:w-auto bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 disabled:opacity-50">Import</button>
                    <button wire:click="closeImportModal"
                        class="mt-2 sm:mt-0 w-full sm:w-auto border border-gray-300 bg-white px-4 py-2 rounded-lg hover:bg-gray-50">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>