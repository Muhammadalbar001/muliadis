<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-emerald-50/90 p-4 rounded-b-2xl shadow-sm border-b border-emerald-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600"><i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-emerald-900 tracking-tight">Transaksi Penjualan</h1>
                    <p class="text-xs text-emerald-600 font-medium mt-0.5">Rekapitulasi faktur harian.</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-3 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-emerald-500 py-2 shadow-sm placeholder-slate-400"
                        placeholder="Cari Faktur...">
                </div>

                <div
                    class="flex items-center gap-1 bg-white border border-white rounded-lg px-2 py-1 shadow-sm h-[34px]">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                    <span class="text-slate-300 text-[10px]">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false, selected: @entangle('filterCabang').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-emerald-50 transition-all">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Cabang' : 'Semua Cabang'"></span>
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
                        @foreach($optCabang as $c)
                        <div @click="selected.includes('{{ $c }}') ? selected = selected.filter(i => i !== '{{ $c }}') : selected.push('{{ $c }}')"
                            class="flex items-center px-2 py-1.5 hover:bg-emerald-50 rounded cursor-pointer transition-colors group">
                            <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors mr-2"
                                :class="selected.includes('{{ $c }}') ? 'bg-emerald-500 border-emerald-500' : 'border-slate-300 bg-white group-hover:border-emerald-400'">
                                <i x-show="selected.includes('{{ $c }}')"
                                    class="fas fa-check text-white text-[9px]"></i>
                            </div>
                            <span class="text-xs text-slate-600 truncate"
                                :class="selected.includes('{{ $c }}') ? 'font-bold text-emerald-700' : ''">{{ $c }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="hidden sm:block h-6 w-px bg-emerald-200 mx-1"></div>

                <button wire:click="resetFilter"
                    class="px-3 py-2 bg-white border border-emerald-200 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-50 shadow-sm"
                    title="Reset"><i class="fas fa-undo"></i></button>

                <button wire:click="openDeleteDateModal"
                    class="px-3 py-2 bg-white border border-rose-200 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-50 shadow-sm flex items-center gap-2"
                    title="Hapus per Tanggal">
                    <i class="fas fa-trash-alt"></i> <span class="hidden xl:inline">Hapus Tgl</span>
                </button>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg text-xs font-bold hover:from-emerald-700 hover:to-teal-700 shadow-md shadow-emerald-500/20 flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Import</span>
                </button>

                <!-- <div wire:loading class="text-emerald-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div> -->
            </div>
        </div>
    </div>

    @if(isset($summary))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-2">
        <div
            class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-4 text-white shadow-sm shadow-emerald-500/20 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Omzet</p>
                <h3 class="text-xl font-extrabold tracking-tight">Rp
                    {{ number_format($summary['total_omzet'], 0, ',', '.') }}</h3>
            </div>
            <i class="fas fa-chart-line absolute right-3 top-3 text-white/20 text-5xl rotate-12"></i>
        </div>
        <div
            class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-emerald-300">
            <div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Faktur</p>
                <h3 class="text-xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_faktur'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600"><i
                    class="fas fa-file-invoice text-lg"></i></div>
        </div>
        <div
            class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-orange-300">
            <div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Item Terjual</p>
                <h3 class="text-xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_items'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600"><i
                    class="fas fa-boxes-stacked text-lg"></i></div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[85vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Tanggal</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">No Faktur
                        </th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 min-w-[200px]">
                            Pelanggan</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Salesman</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 text-center">
                            Cabang</th>
                        <th
                            class="px-6 py-4 font-bold text-emerald-700 uppercase border-r border-slate-200 text-right bg-emerald-50/50">
                            Total (Rp)</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase text-center bg-slate-50 sticky right-0 z-20 border-l border-slate-200">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($penjualans as $item)
                    <tr class="hover:bg-emerald-50/20 transition-colors group">
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600 font-medium">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 font-mono text-emerald-700 font-bold">
                            {{ $item->trans_no }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[250px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">{{ $item->sales_name }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-center"><span
                                class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600">{{ $item->cabang }}</span>
                        </td>
                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-extrabold text-slate-800 bg-emerald-50/10">
                            {{ number_format($item->total_grand, 0, ',', '.') }}</td>
                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white border-l border-slate-100 z-10 group-hover:bg-emerald-50/40">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                class="text-slate-300 hover:text-rose-500"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-24 text-center text-slate-400">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $penjualans->links() }}</div>
    </div>

    @if($isImportOpen) @include('livewire.partials.import-modal', ['title' => 'Import Penjualan', 'color' => 'emerald'])
    @endif

    @if($isDeleteDateOpen)
    <div class="fixed inset-0 z-[70] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity"
                wire:click="closeDeleteDateModal"></div>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-white/20">
                <div class="bg-rose-50 px-6 py-4 border-b border-rose-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600"><i
                            class="fas fa-exclamation-triangle text-lg"></i></div>
                    <div>
                        <h3 class="text-lg font-bold text-rose-700">Hapus Data Harian</h3>
                        <p class="text-rose-500 text-xs">Penjualan per tanggal akan dihapus.</p>
                    </div>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5 ml-1">Pilih
                            Tanggal</label>
                        <input type="date" wire:model="deleteDateInput"
                            class="w-full pl-4 pr-4 py-2.5 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 font-bold text-slate-700">
                        @error('deleteDateInput') <span
                            class="text-rose-500 text-xs font-bold mt-1 block ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-200">
                    <button wire:click="closeDeleteDateModal"
                        class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-50">Batal</button>
                    <button wire:click="deleteByDate"
                        class="px-4 py-2 bg-rose-600 text-white rounded-xl text-sm font-bold hover:bg-rose-700">Hapus
                        Permanen</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>