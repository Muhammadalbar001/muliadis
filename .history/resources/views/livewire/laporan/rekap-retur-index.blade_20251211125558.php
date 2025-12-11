<div class="space-y-6 font-jakarta">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Rekap Retur</h2>
            <p class="text-sm text-slate-500 mt-1">Detail retur barang ({{ $returs->total() }} Baris).</p>
        </div>
        <button wire:click="export" wire:loading.attr="disabled"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-rose-500/30 transition-all">
            <span wire:loading.remove wire:target="export"><i class="fas fa-file-export mr-2"></i> Export Excel</span>
            <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin mr-2"></i> Processing...</span>
        </button>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pencarian</label>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="w-full border-slate-200 rounded-xl text-sm focus:ring-rose-500 placeholder-slate-400"
                placeholder="No Retur, Pelanggan...">
        </div>
        <div class="w-auto">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Periode</label>
            <div
                class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl border border-slate-200 hover:border-rose-300">
                <input type="date" wire:model.live="startDate"
                    class="bg-transparent border-none text-xs font-bold text-slate-700 w-32">
                <span class="text-slate-300">|</span>
                <input type="date" wire:model.live="endDate"
                    class="bg-transparent border-none text-xs font-bold text-slate-700 w-32">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[70vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap w-full">
                <thead
                    class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200 sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-0 z-30">Cabang</th>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-[60px] z-30">No Retur
                        </th>
                        <th class="px-3 py-3 border-r border-slate-200">Tanggal</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[150px]">Pelanggan</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[200px]">Barang</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right bg-rose-50 text-rose-700">Qty</th>
                        <th
                            class="px-3 py-3 border-r border-slate-200 text-right font-bold bg-yellow-50 text-yellow-700">
                            Total Retur</th>
                        <th class="px-3 py-3 border-r border-slate-200">Salesman</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($returs as $item)
                    <tr class="hover:bg-rose-50/20 transition-colors odd:bg-white even:bg-slate-50/50">
                        <td class="px-3 py-2 border-r border-slate-100 font-bold sticky left-0 bg-inherit z-10">
                            {{ $item->cabang }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 font-mono text-rose-600 sticky left-[60px] bg-inherit z-10">
                            {{ $item->no_retur }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ date('d/m/Y', strtotime($item->tgl_retur)) }}
                        </td>
                        <td class="px-3 py-2 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[200px]">
                            {{ $item->nama_pelanggan }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 truncate max-w-[250px]">{{ $item->nama_item }}
                        </td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 text-right font-bold bg-rose-50/30 text-rose-700">
                            {{ number_format($item->qty, 0, ',', '.') }} {{ $item->satuan_retur }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 text-right font-bold bg-yellow-50/50 text-yellow-700">
                            {{ number_format($item->total_grand, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->sales_name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400">Data tidak ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $returs->links() }}</div>
    </div>
</div>