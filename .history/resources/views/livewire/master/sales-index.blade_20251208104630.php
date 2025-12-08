<div class="space-y-6">

    <div
        class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-end gap-4">
        <div class="w-full md:w-3/4 grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-9 pr-4 py-2 w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500"
                    placeholder="Cari Sales...">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>
            <select wire:model.live="filterCity" class="w-full border-gray-200 rounded-lg text-sm">
                <option value="">Semua Kota</option>@foreach($optCity as $c) <option value="{{ $c }}">{{ $c }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterDivisi" class="w-full border-gray-200 rounded-lg text-sm">
                <option value="">Semua Divisi</option>@foreach($optDivisi as $d) <option value="{{ $d }}">{{ $d }}
                </option> @endforeach
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
                            {{ $sales->firstItem() + $index }}</td>
                        <td class="px-6 py-3 border-r font-bold text-gray-800">{{ $item->sales_name }}</td>
                        <td class="px-6 py-3 border-r text-gray-600">{{ $item->divisi ?? '-' }}</td>
                        <td class="px-6 py-3 border-r text-center">
                            <span
                                class="px-2 py-1 rounded-full text-[10px] font-bold {{ strtolower($item->status) == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-500' }}">{{ $item->status }}</span>
                        </td>

                        <td class="px-6 py-3 border-r text-center">
                            <button wire:click="openTargetModal({{ $item->id }})"
                                class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-md text-xs font-bold border border-blue-200 transition-colors">
                                <i class="fas fa-bullseye mr-1.5"></i> Target
                            </button>
                        </td>

                        <td class="px-6 py-3 border-r text-indigo-600 font-medium text-xs">{{ $item->city }}</td>
                        <td class="px-6 py-3 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                class="text-gray-400 hover:text-red-600"><i class="fas fa-trash-alt"></i></button>
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
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $sales->links() }}</div>
    </div>

    @if($isTargetOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeTargetModal"></div>

            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">

                <div class="bg-indigo-600 px-4 py-3 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-bold text-white">Target Sales: {{ $selectedSalesName }}</h3>
                        <p class="text-indigo-200 text-xs">Isi target bulanan untuk sales ini.</p>
                    </div>
                    <select wire:model.live="targetYear"
                        class="text-sm rounded border-none focus:ring-0 bg-white/20 text-white font-bold cursor-pointer hover:bg-white/30">
                        @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++) <option value="{{ $y }}"
                            class="text-gray-800">{{ $y }}</option>
                            @endfor
                    </select>
                </div>

                <div class="px-4 py-5 sm:p-6 bg-gray-50 max-h-[60vh] overflow-y-auto">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="grid grid-cols-12 gap-2 text-xs font-bold text-gray-500 uppercase mb-2">
                            <div class="col-span-2">Bulan</div>
                            <div class="col-span-5 text-right">Target IMS (Rp)</div>
                            <div class="col-span-5 text-right">Target OA (Toko)</div>
                        </div>

                        @foreach(range(1, 12) as $m)
                        <div class="grid grid-cols-12 gap-2 items-center bg-white p-2 rounded border border-gray-200">
                            <div class="col-span-2 font-bold text-gray-700 text-sm">
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </div>

                            <div class="col-span-5">
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-400 text-xs">Rp</span>
                                    <input type="number" wire:model="targets.{{ $m }}.ims"
                                        class="w-full pl-8 py-1.5 text-sm border-gray-300 rounded focus:ring-indigo-500 text-right"
                                        placeholder="0">
                                </div>
                            </div>

                            <div class="col-span-5">
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-400 text-xs"><i
                                            class="fas fa-store"></i></span>
                                    <input type="number" wire:model="targets.{{ $m }}.oa"
                                        class="w-full pl-8 py-1.5 text-sm border-gray-300 rounded focus:ring-indigo-500 text-right"
                                        placeholder="0">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                    <button wire:click="saveTargets" type="button"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan Target
                    </button>
                    <button wire:click="closeTargetModal" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>