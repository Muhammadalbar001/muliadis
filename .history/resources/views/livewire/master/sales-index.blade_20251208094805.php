<div class="space-y-6">
    <div
        class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-end gap-4">
        <div class="w-full md:w-3/4 grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-9 pr-4 py-2 w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500"
                    placeholder="Cari Nama Sales...">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>

            <select wire:model.live="filterCity"
                class="w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 text-gray-600">
                <option value="">Semua Kota</option>
                @foreach($optCity as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
            </select>

            <select wire:model.live="filterDivisi"
                class="w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 text-gray-600">
                <option value="">Semua Divisi</option>
                @foreach($optDivisi as $d) <option value="{{ $d }}">{{ $d }}</option> @endforeach
            </select>
        </div>

        <button wire:click="openImportModal"
            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all flex items-center">
            <i class="fas fa-file-excel mr-2"></i> Import Sales
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-500 uppercase bg-gray-50 font-bold border-b border-gray-200 text-xs">
                    <tr>
                        <th class="px-6 py-4">Nama Sales</th>
                        <th class="px-6 py-4">Divisi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Target IMS</th>
                        <th class="px-6 py-4 text-right">Target OA</th>
                        <th class="px-6 py-4">Kota/Cabang</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sales as $item)
                    <tr class="hover:bg-indigo-50 transition-colors">
                        <td class="px-6 py-3 font-bold text-gray-800">{{ $item->sales_name }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $item->divisi }}</td>
                        <td class="px-6 py-3 text-center">
                            <span
                                class="px-2 py-1 rounded text-[10px] font-bold {{ $item->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right font-mono">
                            {{ number_format((float)$item->target_ims, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-mono">
                            {{ number_format((float)$item->target_oa, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-indigo-600 font-medium">{{ $item->city }}</td>
                        <td class="px-6 py-3 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                class="text-red-500 hover:text-red-700 transition"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Data Sales Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t bg-gray-50">{{ $sales->links() }}</div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeImportModal"></div>
            <div
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Import Master Sales</h3>
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
                        class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 disabled:opacity-50">Import</button>
                    <button wire:click="closeImportModal"
                        class="mt-2 sm:mt-0 w-full sm:w-auto border border-gray-300 bg-white px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-700">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>