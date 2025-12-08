<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Transaksi Penjualan</h2>
            <p class="text-sm text-slate-500">Rekapitulasi faktur penjualan harian.</p>
        </div>
        <button wire:click="openImportModal"
            class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-file-excel mr-2"></i> Import Sales
        </button>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4 items-end">

            <div class="w-full md:flex-1 relative">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pencarian</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="No Faktur / Nama Toko...">
                </div>
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Periode</label>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="startDate" class="border-slate-200 rounded-xl text-xs w-32">
                    <span class="text-slate-400">-</span>
                    <input type="date" wire:model.live="endDate" class="border-slate-200 rounded-xl text-xs w-32">
                </div>
            </div>

            <div class="w-full md:w-64" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Filter Cabang</label>
                <div class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 py-2.5 px-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <span class="truncate">
                            {{ count($filterCabang) > 0 ? count($filterCabang) . ' Cabang Terpilih' : 'Semua Cabang' }}
                        </span>
                        <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                    </button>

                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto p-2">
                        @foreach($optCabang as $cab)
                        <label
                            class="flex items-center px-2 py-2 hover:bg-emerald-50 rounded-lg cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4 mr-3">
                            <span class="text-sm text-slate-700">{{ $cab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <button wire:click="resetFilter"
                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors"
                title="Reset Semua Filter">
                <i class="fas fa-undo"></i>
            </button>

        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 flex flex-col h-[70vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead
                    class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">No Faktur</th>
                        <th class="px-4 py-3">Nama Pelanggan</th>
                        <th class="px-4 py-3">Salesman</th>
                        <th class="px-4 py-3 text-center">Cabang</th>
                        <th class="px-4 py-3 text-right">Total (Rp)</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($penjualans as $item)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        <td class="px-4 py-2 border-r">{{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                        <td class="px-4 py-2 border-r font-mono text-emerald-700 font-bold">{{ $item->trans_no }}</td>
                        <td class="px-4 py-2 border-r font-medium text-slate-700 truncate max-w-[200px]">
                            {{ $item->nama_pelanggan }}</td>
                        <td class="px-4 py-2 border-r text-slate-500">{{ $item->sales_name }}</td>
                        <td class="px-4 py-2 border-r text-center">
                            <span
                                class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600">
                                {{ $item->cabang }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border-r text-right font-bold text-slate-800">
                            {{ number_format($item->total_grand, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                @click="$dispatch('confirm-delete', { method: 'deletePenjualan', id: {{ $item->id }} })"
                                class="text-slate-300 hover:text-red-500 transition-colors"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                            <i class="fas fa-search text-3xl mb-2 text-slate-200"></i>
                            <p>Data tidak ditemukan dengan filter ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-slate-50">{{ $penjualans->links() }}</div>
    </div>

    @if($isImportOpen) @include('livewire.partials.import-modal', ['title'=>'Import Penjualan', 'color'=>'emerald'])
    @endif
</div>