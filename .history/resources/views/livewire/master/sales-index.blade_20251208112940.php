<div class="space-y-6">

    <div
        class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-end gap-4">

        <div class="w-full md:w-3/4 grid grid-cols-1 md:grid-cols-3 gap-3">

            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-9 pr-4 py-2 w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400"
                    placeholder="Cari Nama Sales...">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>

            <div class="relative">
                <select wire:model.live="filterCity"
                    class="w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 text-gray-600 appearance-none cursor-pointer">
                    <option value="">Semua Kota / Cabang</option>
                    @foreach($optCity as $c)
                    <option value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 text-xs pointer-events-none"></i>
            </div>

            <div class="relative">
                <select wire:model.live="filterDivisi"
                    class="w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 text-gray-600 appearance-none cursor-pointer">
                    <option value="">Semua Divisi</option>
                    @foreach($optDivisi as $d)
                    <option value="{{ $d }}">{{ $d }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 text-xs pointer-events-none"></i>
            </div>
            <div class="flex gap-2">
                <button wire:click="resetAllTargets"
                    onclick="return confirm('Apakah Anda yakin ingin mereset SEMUA target sales menjadi 0?') || event.stopImmediatePropagation()"
                    class="inline-flex items-center px-4 py-2 bg-white text-red-600 border border-red-200 hover:bg-red-50 text-sm font-bold rounded-lg shadow-sm transition-all"
                    title="Hapus semua target IMS & OA">
                    <i class="fas fa-eraser mr-2"></i> Reset Target
                </button>

                <button wire:click="openImportModal"
                    class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-file-import mr-2"></i> Import Sales
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="text-gray-500 uppercase bg-gray-50 font-bold border-b border-gray-200 text-xs">
                            <tr>
                                <th class="px-6 py-4 w-16 text-center border-r">No</th>
                                <th class="px-6 py-4 border-r">Nama Sales</th>
                                <th class="px-6 py-4 border-r">Divisi</th>
                                <th class="px-6 py-4 border-r text-center">Status</th>
                                <th class="px-6 py-4 border-r text-center">Set Target</th>
                                <th class="px-6 py-4 border-r">Kota / Cabang</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($sales as $index => $item)
                            <tr class="hover:bg-indigo-50 transition-colors">
                                <td class="px-6 py-3 text-center text-gray-500 border-r text-xs">
                                    {{ $sales->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-3 border-r font-bold text-gray-800">
                                    {{ $item->sales_name }}
                                </td>
                                <td class="px-6 py-3 border-r text-gray-600">
                                    {{ $item->divisi ?? '-' }}
                                </td>
                                <td class="px-6 py-3 border-r text-center">
                                    @if(strtolower($item->status) == 'active')
                                    <span
                                        class="px-2 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                        ACTIVE
                                    </span>
                                    @else
                                    <span
                                        class="px-2 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                        {{ strtoupper($item->status) }}
                                    </span>
                                    @endif
                                </td>

                                <td class="px-6 py-3 border-r text-center">
                                    <button wire:click="openTargetModal({{ $item->id }})"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-bold border border-blue-200 transition-colors group">
                                        <i
                                            class="fas fa-bullseye mr-1.5 group-hover:scale-110 transition-transform"></i>
                                        Target
                                    </button>
                                </td>

                                <td class="px-6 py-3 border-r text-indigo-600 font-medium text-xs">
                                    {{ $item->city }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <button wire:click="delete({{ $item->id }})"
                                        onclick="return confirm('Yakin ingin menghapus sales ini?') || event.stopImmediatePropagation()"
                                        class="text-gray-400 hover:text-red-600 transition-colors p-1 rounded hover:bg-red-50"
                                        title="Hapus Data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-user-slash text-2xl text-gray-300"></i>
                                        </div>
                                        <p class="text-sm font-medium">Data Sales tidak ditemukan.</p>
                                        <p class="text-xs mt-1 text-gray-400">Silakan import data excel baru.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $sales->links() }}
                </div>
            </div>

            @if($isImportOpen)
            <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                        wire:click="closeImportModal"></div>

                    <div
                        class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-100">

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-file-import text-indigo-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-bold text-gray-900">Import Master Sales</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">Upload file Excel (.xlsx) berisi data
                                            salesman
                                            baru.</p>

                                        <div
                                            class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 cursor-pointer relative transition-colors group">
                                            <div class="space-y-1 text-center">
                                                <i
                                                    class="fas fa-cloud-upload-alt text-gray-400 text-3xl group-hover:text-indigo-500 transition-colors"></i>
                                                <div class="text-sm text-gray-600">
                                                    <label for="file-upload-sales"
                                                        class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                        <span>Pilih File</span>
                                                        <input id="file-upload-sales" wire:model="file" type="file"
                                                            class="sr-only">
                                                    </label>
                                                </div>
                                                <p class="text-xs text-gray-400">XLSX, CSV up to 10MB</p>
                                            </div>
                                        </div>

                                        <div
                                            class="mt-4 flex items-center p-3 bg-red-50 rounded-lg border border-red-100">
                                            <input id="resetData" type="checkbox" wire:model="resetData"
                                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded cursor-pointer">
                                            <label for="resetData"
                                                class="ml-2 block text-xs text-red-800 cursor-pointer">
                                                <span class="font-bold">Hapus Data Lama?</span> (Reset Table)<br>
                                                <span class="font-normal text-red-600">Centang jika ingin mengganti
                                                    total semua
                                                    data sales.</span>
                                            </label>
                                        </div>

                                        <div wire:loading wire:target="file" class="w-full mt-3 text-center">
                                            <span class="text-xs text-indigo-600 font-bold animate-pulse">Mengupload
                                                File...</span>
                                        </div>
                                        <div wire:loading wire:target="import" class="w-full mt-3 text-center">
                                            <span class="text-xs text-green-600 font-bold animate-pulse">Sedang
                                                Memproses
                                                Data...</span>
                                        </div>

                                        @error('file')
                                        <div
                                            class="mt-2 p-2 bg-red-100 border border-red-200 text-red-700 text-xs rounded">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button wire:click="import" wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                Mulai Import
                            </button>
                            <button wire:click="closeImportModal"
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($isTargetOpen)
            <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                        wire:click="closeTargetModal"></div>

                    <div
                        class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-gray-100">

                        <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg leading-6 font-bold text-white flex items-center gap-2">
                                    <i class="fas fa-bullseye"></i> Set Target Sales
                                </h3>
                                <p class="text-indigo-200 text-xs mt-1">Salesman: <span
                                        class="font-bold text-white">{{ $selectedSalesName }}</span></p>
                            </div>

                            <div class="relative">
                                <select wire:model.live="targetYear"
                                    class="text-sm rounded-lg border-indigo-400 bg-indigo-700 text-white font-bold cursor-pointer hover:bg-indigo-800 focus:ring-indigo-400 pr-8">
                                    @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++) <option value="{{ $y }}">
                                        {{ $y }}
                                        </option>
                                        @endfor
                                </select>
                            </div>
                        </div>

                        <div class="px-6 py-6 bg-gray-50 max-h-[60vh] overflow-y-auto">

                            <div class="grid grid-cols-12 gap-3 text-xs font-bold text-gray-500 uppercase mb-3 px-1">
                                <div class="col-span-2 pt-2">Bulan</div>
                                <div class="col-span-5 text-right">Target IMS (Omzet Rp)</div>
                                <div class="col-span-5 text-right">Target OA (Toko)</div>
                            </div>

                            <div class="space-y-2">
                                @foreach(range(1, 12) as $m)
                                <div
                                    class="grid grid-cols-12 gap-3 items-center bg-white p-3 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">

                                    <div class="col-span-2 font-bold text-gray-700 text-sm flex items-center gap-2">
                                        <span
                                            class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs border border-indigo-100">{{ $m }}</span>
                                        <span
                                            class="hidden sm:inline">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}</span>
                                    </div>

                                    <div class="col-span-5">
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-2 text-gray-400 text-xs font-bold">Rp</span>
                                            <input type="number" wire:model="targets.{{ $m }}.ims"
                                                class="w-full pl-8 pr-3 py-1.5 text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-right font-mono"
                                                placeholder="0">
                                        </div>
                                    </div>

                                    <div class="col-span-5">
                                        <div class="relative">
                                            <span class="absolute left-3 top-2 text-gray-400 text-xs"><i
                                                    class="fas fa-store"></i></span>
                                            <input type="number" wire:model="targets.{{ $m }}.oa"
                                                class="w-full pl-8 pr-3 py-1.5 text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-right font-mono"
                                                placeholder="0">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-white px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                            <button wire:click="closeTargetModal" type="button"
                                class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 focus:outline-none shadow-sm">
                                Batal
                            </button>
                            <button wire:click="saveTargets" type="button"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 focus:outline-none shadow-md flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Target
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>