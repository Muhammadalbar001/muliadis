<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Data Piutang (AR)</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola tagihan pelanggan yang belum terbayar.</p>
        </div>
        <button wire:click="openImportModal"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-file-invoice-dollar mr-2"></i> Import AR
        </button>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:flex-1 relative group">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Pencarian</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i
                            class="fas fa-search text-slate-400 group-focus-within:text-orange-500 transition-colors"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 placeholder-slate-400 transition-all"
                        placeholder="No Faktur / Pelanggan...">
                </div>
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Periode</label>
                <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl border border-slate-200">
                    <input type="date" wire:model.live="startDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                    <span class="text-slate-300">|</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                </div>
            </div>

            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-slate-600 cursor-pointer">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>

            <button wire:click="resetFilter"
                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl transition-colors h-[42px]">
                <i class="fas fa-undo"></i>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[70vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead
                    class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4 border-r border-slate-200">Tanggal</th>
                        <th class="px-6 py-4 border-r border-slate-200">No Faktur</th>
                        <th class="px-6 py-4 border-r border-slate-200 min-w-[200px]">Pelanggan</th>
                        <th class="px-6 py-4 border-r border-slate-200">Salesman</th>
                        <th class="px-6 py-4 border-r border-slate-200">Jatuh Tempo</th>
                        <th class="px-6 py-4 border-r border-slate-200 text-right bg-orange-50 text-orange-700">Sisa
                            Piutang</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($asr as $item)
                    <tr class="hover:bg-orange-50/30 transition-colors group">
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 font-mono text-orange-600 font-bold">
                            {{ $item->no_faktur }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[250px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">{{ $item->sales_name }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">
                            {{ $item->jatuh_tempo ? date('d/m/Y', strtotime($item->jatuh_tempo)) : '-' }}
                        </td>
                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-bold text-orange-600 bg-orange-50/10">
                            {{ number_format($item->total_nilai, 0, ',', '.') }}
                        </td>
                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white group-hover:bg-orange-50/30 transition-colors">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus data piutang?') || event.stopImmediatePropagation()"
                                class="text-slate-300 hover:text-orange-500 transition-colors"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-slate-400">
                            <i class="fas fa-file-invoice text-3xl mb-2 text-slate-200"></i>
                            <p>Tidak ada data piutang.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $ar->links() }}</div>
    </div>

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Import Piutang', 'color' => 'orange'])
    @endif

</div>