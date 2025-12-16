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
                        class="pl-3 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-blue-500 py-2 shadow-sm placeholder-slate-400 transition-all"
                        placeholder="Cari Sales...">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false, selected: @entangle('filterCity').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-blue-50 transition-all">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Kota Dipilih' : 'Semua Kota'"></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>

                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-48 bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto custom-scrollbar"
                        style="display: none;">
                        <div @click="selected = []"
                            class="px-2 py-1.5 text-xs text-rose-500 font-bold cursor-pointer hover:bg-rose-50 rounded mb-1 flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> Reset Filter
                        </div>
                        @foreach($optCity as $c)
                        <div @click="selected.includes('{{ $c }}') ? selected = selected.filter(i => i !== '{{ $c }}') : selected.push('{{ $c }}')"
                            class="flex items-center px-2 py-1.5 hover:bg-blue-50 rounded cursor-pointer transition-colors group">
                            <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors mr-2"
                                :class="selected.includes('{{ $c }}') ? 'bg-blue-500 border-blue-500' : 'border-slate-300 bg-white group-hover:border-blue-400'">
                                <i x-show="selected.includes('{{ $c }}')"
                                    class="fas fa-check text-white text-[9px]"></i>
                            </div>
                            <span class="text-xs text-slate-600 truncate"
                                :class="selected.includes('{{ $c }}') ? 'font-bold text-blue-700' : ''">{{ $c }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false, selected: @entangle('filterDivisi').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-blue-50 transition-all">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Divisi Dipilih' : 'Semua Divisi'"></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>

                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-48 bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto custom-scrollbar"
                        style="display: none;">
                        <div @click="selected = []"
                            class="px-2 py-1.5 text-xs text-rose-500 font-bold cursor-pointer hover:bg-rose-50 rounded mb-1 flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> Reset Filter
                        </div>
                        @foreach($optDivisi as $d)
                        <div @click="selected.includes('{{ $d }}') ? selected = selected.filter(i => i !== '{{ $d }}') : selected.push('{{ $d }}')"
                            class="flex items-center px-2 py-1.5 hover:bg-blue-50 rounded cursor-pointer transition-colors group">
                            <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors mr-2"
                                :class="selected.includes('{{ $d }}') ? 'bg-blue-500 border-blue-500' : 'border-slate-300 bg-white group-hover:border-blue-400'">
                                <i x-show="selected.includes('{{ $d }}')"
                                    class="fas fa-check text-white text-[9px]"></i>
                            </div>
                            <span class="text-xs text-slate-600 truncate"
                                :class="selected.includes('{{ $d }}') ? 'font-bold text-blue-700' : ''">{{ $d }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="hidden sm:block h-6 w-px bg-blue-200 mx-1"></div>

                <button wire:click="resetFilters"
                    class="px-3 py-2 bg-white border border-blue-200 text-slate-500 rounded-lg text-xs font-bold hover:bg-slate-50 shadow-sm transition-all"
                    title="Reset Semua Filter"><i class="fas fa-undo"></i></button>

                <button wire:click="resetAllTargets"
                    onclick="return confirm('Reset SEMUA target jadi 0?') || event.stopImmediatePropagation()"
                    class="px-3 py-2 bg-white border border-rose-200 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-50 shadow-sm transition-all"
                    title="Reset Target"><i class="fas fa-eraser"></i></button>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5"><i
                        class="fas fa-file-import"></i> <span class="hidden sm:inline">Import</span></button>
                <!-- <div wire:loading class="text-blue-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div> -->
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Nama Sales</th>
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
                            <div class="text-[10px] text-slate-400 font-mono">{{ $item->sales_code }}</div>
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
                            <button wire:click="edit({{ $item->id }})"
                                class="w-8 h-8 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all"><i
                                    class="fas fa-edit"></i></button>
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Yakin?') || event.stopImmediatePropagation()"
                                class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all"><i
                                    class="fas fa-trash-alt"></i></button>
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

    @if($isOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" wire:click="closeModal">
            </div>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/20">
                <div
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 border-b border-white/10 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">{{ $salesId ? 'Edit Salesman' : 'Tambah Salesman' }}</h3>
                    <button wire:click="closeModal" class="text-white/70 hover:text-white"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Sales</label>
                        <input type="text" wire:model="sales_name"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                        @error('sales_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kode</label>
                            <input type="text" wire:model="sales_code"
                                class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cabang</label>
                            <input type="text" wire:model="city"
                                class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Divisi</label>
                            <input type="text" wire:model="divisi"
                                class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Status</label>
                            <select wire:model="status"
                                class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-2">
                    <button wire:click="closeModal"
                        class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button wire:click="store"
                        class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 shadow-lg">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isTargetOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity"
                wire:click="closeTargetModal"></div>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-white/20">
                <div
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex justify-between items-center border-b border-white/10">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center gap-2"><i class="fas fa-bullseye"></i>
                            Set Target Sales</h3>
                        <p class="text-blue-100 text-xs mt-0.5">Sales: <span
                                class="font-bold text-white bg-white/20 px-2 py-0.5 rounded ml-1">{{ $selectedSalesName }}</span>
                        </p>
                    </div>
                    <div class="relative">
                        <select wire:model.live="targetYear"
                            class="text-sm rounded-lg border-blue-400 bg-blue-800/50 text-white font-bold cursor-pointer hover:bg-blue-700 focus:ring-2 focus:ring-white pr-8 py-1.5 outline-none">
                            @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++) <option value="{{ $y }}"
                                class="text-slate-800">{{ $y }}</option> @endfor
                        </select>
                    </div>
                </div>
                <div class="px-6 py-6 bg-slate-50 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="flex justify-between items-center mb-4 px-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bulan</span>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Target Omzet (Rp)</span>
                    </div>
                    <div class="space-y-3">
                        @foreach(range(1, 12) as $m)
                        <div
                            class="flex items-center gap-4 bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-300 transition-all group">
                            <div class="flex items-center gap-3 w-1/3">
                                <span
                                    class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold border border-slate-200 group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:border-blue-100 transition-colors">{{ $m }}</span>
                                <span
                                    class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}</span>
                            </div>
                            <div class="flex-1 relative">
                                <span
                                    class="absolute left-3 top-2.5 text-slate-400 text-xs font-bold pointer-events-none group-focus-within:text-blue-600 transition-colors">Rp</span>
                                <input type="number" wire:model="targets.{{ $m }}.ims"
                                    class="w-full pl-9 pr-3 py-2 text-sm font-bold text-right border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-800 transition-all placeholder-slate-300"
                                    placeholder="0">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="bg-white px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                    <button wire:click="closeTargetModal" type="button"
                        class="px-5 py-2.5 bg-white text-slate-700 border border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-50 focus:outline-none transition-all">Batal</button>
                    <button wire:click="saveTargets" type="button"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 focus:outline-none shadow-lg shadow-blue-500/30 flex items-center gap-2 transition-all transform hover:-translate-y-0.5"><i
                            class="fas fa-save"></i> Simpan Target</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isImportOpen) @include('livewire.partials.import-modal', ['title' => 'Import Salesman', 'color' => 'blue'])
    @endif

</div>