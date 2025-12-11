<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-blue-50/90 p-4 rounded-b-2xl shadow-sm border-b border-blue-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-blue-100 rounded-lg text-blue-600 shadow-sm">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-blue-900 tracking-tight">Master Salesman</h1>
                    <p class="text-xs text-blue-600 font-medium mt-0.5">Kelola tim penjualan & target.</p>
                </div>
                <div
                    class="hidden md:flex px-3 py-1 bg-white text-blue-600 rounded-lg text-[10px] font-bold border border-blue-100 items-center gap-2 shadow-sm">
                    <i class="fas fa-users"></i> {{ $sales->total() }} Orang
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-8 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-blue-500 py-2 shadow-sm placeholder-slate-400 transition-all"
                        placeholder="Cari Sales...">
                </div>

                <div class="w-full sm:w-36">
                    <select wire:model.live="filterCity"
                        class="w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-blue-500 py-2 shadow-sm cursor-pointer bg-white hover:bg-blue-50 transition-colors">
                        <option value="">Semua Kota</option>
                        @foreach($optCity as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-36">
                    <select wire:model.live="filterDivisi"
                        class="w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-blue-500 py-2 shadow-sm cursor-pointer bg-white hover:bg-blue-50 transition-colors">
                        <option value="">Semua Divisi</option>
                        @foreach($optDivisi as $d) <option value="{{ $d }}">{{ $d }}</option> @endforeach
                    </select>
                </div>

                <div class="hidden sm:block h-6 w-px bg-blue-200 mx-1"></div>

                <button wire:click="resetAllTargets"
                    onclick="return confirm('Reset SEMUA target jadi 0?') || event.stopImmediatePropagation()"
                    class="px-3 py-2 bg-white border border-rose-200 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-50 shadow-sm transition-all"
                    title="Reset Target">
                    <i class="fas fa-eraser"></i>
                </button>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fas fa-file-import"></i> <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading class="text-blue-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200">
                    <tr>
                        <th
                            class="px-6 py-4 w-16 text-center font-bold text-slate-600 text-xs uppercase tracking-wider">
                            No</th>
                        <th class="px-6 py-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Nama Sales</th>
                        <th class="px-6 py-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Divisi</th>
                        <th class="px-6 py-4 text-center font-bold text-slate-600 text-xs uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-center font-bold text-slate-600 text-xs uppercase tracking-wider">
                            Target</th>
                        <th class="px-6 py-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Kota</th>
                        <th class="px-6 py-4 text-center font-bold text-slate-600 text-xs uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sales as $index => $item)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4 text-center text-slate-500 text-xs font-mono">
                            {{ $sales->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors">
                                {{ $item->sales_name }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->divisi ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if(strtolower($item->status) == 'active')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                ACTIVE
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">{{ strtoupper($item->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="openTargetModal({{ $item->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-bold border border-indigo-100 transition-colors">
                                <i class="fas fa-bullseye mr-1.5"></i> Set Target
                            </button>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium text-xs">
                            <span
                                class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-50 border border-slate-100">
                                <i class="fas fa-map-marker-alt text-slate-400"></i> {{ $item->city }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Yakin?') || event.stopImmediatePropagation()"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-slate-400">Tidak ada data sales.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $sales->links() }}</div>
    </div>

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Import Salesman', 'color' => 'blue'])
    @endif

</div>