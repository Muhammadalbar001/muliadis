<div class="space-y-6 font-jakarta">
    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-blue-50/90 p-4 rounded-b-2xl shadow-sm border-b border-blue-200 -mx-4 px-4 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-2 bg-blue-100 rounded-lg text-blue-600 shadow-sm"><i class="fas fa-user-tie text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-blue-900 tracking-tight">Master Salesman</h1>
                    <p class="text-xs text-blue-600 font-medium mt-0.5">Kelola data personil dan target bulanan.</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 items-center justify-end">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-3 w-full sm:w-48 border-white rounded-lg text-xs font-bold py-2 shadow-sm focus:ring-blue-500"
                    placeholder="Cari Sales...">

                <button wire:click="syncCodes" wire:loading.attr="disabled"
                    class="px-3 py-2 bg-white border border-indigo-200 text-indigo-600 rounded-lg text-xs font-bold hover:bg-indigo-50 flex items-center gap-2">
                    <i class="fas fa-sync" wire:loading.class="fa-spin" wire:target="syncCodes"></i> Sync Kode
                </button>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-white border border-blue-200 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-50 flex items-center gap-2">
                    <i class="fas fa-file-import"></i> Import
                </button>

                <button wire:click="create"
                    class="px-3 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 shadow-md flex items-center gap-2">
                    <i class="fas fa-plus"></i> Baru
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200 text-[10px]">
                    <tr>
                        <th class="px-6 py-4 text-indigo-600 bg-indigo-50/30 w-32 border-r border-slate-100">Kode Sales
                        </th>
                        <th class="px-6 py-4">Nama Salesman</th>
                        <th class="px-6 py-4">Divisi</th>
                        <th class="px-6 py-4">Kota</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center bg-slate-100 border-l border-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" wire:loading.class="opacity-50">
                    @forelse($sales as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td
                            class="px-6 py-4 font-mono font-bold text-indigo-600 bg-indigo-50/10 border-r border-slate-100">
                            {{ $item->sales_code ?: '-' }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $item->sales_name }}</td>
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $item->divisi ?: '-' }}</td>
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $item->city }}</td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $item->status == 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center bg-slate-50/50 border-l border-slate-200">
                            <div class="flex justify-center gap-1">
                                <button wire:click="openTargetModal({{ $item->id }})"
                                    class="p-1.5 text-indigo-500 hover:bg-indigo-100 rounded-lg transition-colors"
                                    title="Set Target">
                                    <i class="fas fa-bullseye"></i>
                                </button>
                                <button wire:click="edit({{ $item->id }})"
                                    class="p-1.5 text-blue-500 hover:bg-blue-100 rounded-lg transition-colors"
                                    title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="confirm('Hapus Sales ini?') || event.stopImmediatePropagation()"
                                    class="p-1.5 text-rose-500 hover:bg-rose-100 rounded-lg transition-colors"
                                    title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic text-xs">Data tidak
                            ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $sales->links() }}</div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" wire:click="closeModal"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
                <div class="bg-blue-600 px-6 py-4 text-white flex justify-between items-center">
                    <h3 class="font-bold">{{ $salesId ? 'Edit Salesman' : 'Tambah Salesman' }}</h3>
                    <button wire:click="closeModal"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Sales</label>
                        <input type="text" wire:model="sales_name"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kode Sales</label>
                            <input type="text" wire:model="sales_code"
                                class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kota</label>
                            <input type="text" wire:model="city"
                                class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-2">
                    <button wire:click="closeModal" class="px-4 py-2 text-xs font-bold text-slate-500">Batal</button>
                    <button wire:click="store"
                        class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isTargetOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" wire:click="closeTargetModal"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4 text-white flex justify-between items-center">
                    <div>
                        <h3 class="font-bold">Set Target Bulanan</h3>
                        <p class="text-[10px] opacity-80">{{ $selectedSalesName }}</p>
                    </div>
                    <select wire:model.live="targetYear" class="bg-indigo-700 border-none rounded text-xs">
                        @for($y = date('Y')-1; $y <= date('Y')+1; $y++) <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                    </select>
                </div>
                <div class="p-4 max-h-[60vh] overflow-y-auto custom-scrollbar space-y-2">
                    @foreach(range(1, 12) as $m)
                    <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-lg border border-slate-100">
                        <span
                            class="w-20 text-xs font-bold text-slate-600">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</span>
                        <input type="number" wire:model="targets.{{ $m }}.ims"
                            class="flex-1 text-right text-sm font-bold border-slate-200 rounded-md focus:ring-indigo-500"
                            placeholder="0">
                    </div>
                    @endforeach
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-2 border-t">
                    <button wire:click="closeTargetModal"
                        class="px-4 py-2 text-xs font-bold text-slate-500">Tutup</button>
                    <button wire:click="saveTargets"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold">Simpan Target</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Import Salesman', 'color' => 'blue'])
    @endif
</div>