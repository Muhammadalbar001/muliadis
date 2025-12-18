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
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-3 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-blue-500 py-2 shadow-sm placeholder-slate-400"
                        placeholder="Cari Sales / Kode...">
                </div>

                <button wire:click="syncCodes" wire:loading.attr="disabled"
                    class="px-3 py-2 bg-white border border-indigo-200 text-indigo-600 rounded-lg text-xs font-bold hover:bg-indigo-50 shadow-sm transition-all flex items-center gap-2">
                    <span wire:loading.remove wire:target="syncCodes"><i class="fas fa-sync"></i> Sync Kode</span>
                    <span wire:loading wire:target="syncCodes"><i class="fas fa-spinner fa-spin"></i></span>
                </button>

                <div class="hidden sm:block h-6 w-px bg-blue-200 mx-1"></div>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-white border border-blue-200 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-50 shadow-sm flex items-center gap-2">
                    <i class="fas fa-file-import"></i> <span class="hidden sm:inline">Import</span>
                </button>

                <button wire:click="create"
                    class="px-3 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fas fa-plus"></i> <span>Baru</span>
                </button>

                <div wire:loading wire:target="search, filterCity, filterDivisi" class="text-blue-600 ml-1">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" wire:loading.class="opacity-50">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200 text-xs">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Nama Sales / Kode</th>
                        <th class="px-6 py-4">Divisi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Target</th>
                        <th class="px-6 py-4">Kota</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
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
                            <div
                                class="text-[10px] font-mono font-bold {{ $item->sales_code ? 'text-indigo-600' : 'text-rose-400' }}">
                                {{ $item->sales_code ?: '[KODE KOSONG]' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->divisi ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $item->status == 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="openTargetModal({{ $item->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-bold border border-indigo-100 transition-colors">
                                <i class="fas fa-bullseye mr-1.5"></i> Target
                            </button>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <span
                                class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-50 border border-slate-100 text-xs">
                                <i class="fas fa-map-marker-alt text-slate-400"></i> {{ $item->city }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button wire:click="edit({{ $item->id }})"
                                    class="text-slate-400 hover:text-blue-600 transition-colors"><i
                                        class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                    class="text-slate-400 hover:text-rose-600 transition-colors"><i
                                        class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-slate-400 italic">Data sales tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $sales->links() }}</div>
    </div>

</div>