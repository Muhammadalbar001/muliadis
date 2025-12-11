<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Transaksi Penjualan</h2>
            <p class="text-sm text-slate-500 mt-1">Rekapitulasi faktur penjualan dan pencapaian sales harian.</p>
        </div>

        <button wire:click="openImportModal"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-file-excel mr-2"></i> Import Penjualan
        </button>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4 items-end">

            <div class="w-full md:flex-1 relative group">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Pencarian</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i
                            class="fas fa-search text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 placeholder-slate-400 transition-all group-hover:border-emerald-300"
                        placeholder="Cari No Faktur, Pelanggan, atau Sales...">
                </div>
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Periode
                    Transaksi</label>
                <div
                    class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl border border-slate-200 hover:border-emerald-300 transition-colors">
                    <input type="date" wire:model.live="startDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                    <span class="text-slate-300 font-light">|</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                </div>
            </div>

            <div class="w-full md:w-64 relative" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Filter Cabang</label>
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 py-2.5 px-3.5 rounded-xl text-xs font-bold hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-sm">
                    <span class="truncate">
                        {{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang) . ' Dipilih' }}
                    </span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"
                        :class="{'rotate-180': open}"></i>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto p-2"
                    style="display: none;">
                    @foreach($optCabang as $cab)
                    <label
                        class="flex items-center px-2 py-2 hover:bg-emerald-50 rounded-lg cursor-pointer transition-colors group">
                        <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                            class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3 cursor-pointer">
                        <span class="text-xs font-medium text-slate-600 group-hover:text-emerald-700">{{ $cab }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <button wire:click="resetFilter"
                class="w-full md:w-auto px-4 py-2.5 bg-white border border-slate-200 hover:border-rose-300 hover:text-rose-600 text-slate-500 rounded-xl transition-all shadow-sm h-[42px] flex items-center justify-center gap-2"
                title="Reset Semua Filter">
                <i class="fas fa-undo text-xs"></i> <span class="hidden md:inline text-xs font-bold">Reset</span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[70vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">

                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider border-r border-slate-200">
                            Tanggal</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider border-r border-slate-200">
                            No Faktur</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider border-r border-slate-200 min-w-[250px]">
                            Nama Pelanggan</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider border-r border-slate-200">
                            Salesman</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider border-r border-slate-200 text-center">
                            Cabang</th>
                        <th
                            class="px-6 py-4 font-bold text-emerald-700 uppercase tracking-wider border-r border-slate-200 text-right bg-emerald-50/50">
                            Total (Rp)</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider text-center bg-slate-50 sticky right-0 z-20 border-l border-slate-200 shadow-sm w-20">
                            Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($penjualans as $item)
                    <tr class="hover:bg-emerald-50/40 transition-colors group relative">

                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600 font-medium">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}
                        </td>

                        <td
                            class="px-6 py-3 border-r border-slate-100 font-mono text-emerald-700 font-bold group-hover:text-emerald-800 transition-colors">
                            {{ $item->trans_no }}
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[250px]"
                            title="{{ $item->nama_pelanggan }}">
                            {{ $item->nama_pelanggan }}
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">
                            {{ $item->sales_name }}
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100 text-center">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600 uppercase tracking-wide">
                                {{ $item->cabang }}
                            </span>
                        </td>

                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-bold text-slate-800 bg-emerald-50/10 group-hover:bg-emerald-50/20">
                            {{ number_format($item->total_grand, 0, ',', '.') }}
                        </td>

                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white border-l border-slate-100 z-10 group-hover:bg-emerald-50/40 transition-colors">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Yakin hapus data penjualan ini?') || event.stopImmediatePropagation()"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-white hover:bg-rose-500 transition-all shadow-sm"
                                title="Hapus Data">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 animate-pulse">
                                    <i class="fas fa-receipt text-4xl text-slate-200"></i>
                                </div>
                                <h3 class="text-slate-800 font-bold text-lg">Tidak ada data penjualan</h3>
                                <p class="text-slate-400 text-sm mt-1 mb-6 max-w-xs mx-auto">
                                    Belum ada transaksi yang sesuai dengan filter atau pencarian Anda.
                                </p>
                                <button wire:click="resetFilter"
                                    class="text-emerald-600 font-bold hover:underline text-sm flex items-center gap-2">
                                    <i class="fas fa-sync-alt"></i> Reset Filter
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $penjualans->links() }}
        </div>
    </div>

    @if($isImportOpen)
    @include('livewire.partials.import-modal', [
    'title' => 'Import Transaksi Penjualan',
    'color' => 'emerald' // Tema Hijau
    ])
    @endif

</div>