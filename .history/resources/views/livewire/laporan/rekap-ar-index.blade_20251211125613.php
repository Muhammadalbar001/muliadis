<div class="space-y-6 font-jakarta">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Rekap Piutang</h2>
            <p class="text-sm text-slate-500 mt-1">Detail piutang outstanding ({{ $ars->total() }} Baris).</p>
        </div>
        <button wire:click="export" wire:loading.attr="disabled"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/30 transition-all">
            <span wire:loading.remove wire:target="export"><i class="fas fa-file-export mr-2"></i> Export Excel</span>
            <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin mr-2"></i> Processing...</span>
        </button>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pencarian</label>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="w-full border-slate-200 rounded-xl text-sm focus:ring-orange-500 placeholder-slate-400"
                placeholder="No Invoice, Pelanggan...">
        </div>
        <div class="w-auto">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Status</label>
            <select wire:model.live="filterUmur" class="border-slate-200 rounded-xl text-sm focus:ring-orange-500">
                <option value="">Semua</option>
                <option value="lancar">Lancar (<= 30)</option>
                <option value="macet">Macet (> 30)</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[70vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap w-full">
                <thead
                    class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200 sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-0 z-30">Cabang</th>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-[60px] z-30">No Invoice
                        </th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[150px]">Pelanggan</th>
                        <th class="px-3 py-3 border-r border-slate-200">Tgl Faktur</th>
                        <th class="px-3 py-3 border-r border-slate-200">Jatuh Tempo</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-center">Umur</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Total Nilai</th>
                        <th
                            class="px-3 py-3 border-r border-slate-200 text-right font-bold bg-orange-50 text-orange-700">
                            Sisa Piutang</th>
                        <th class="px-3 py-3 border-r border-slate-200">Salesman</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($ars as $item)
                    <tr class="hover:bg-orange-50/20 transition-colors odd:bg-white even:bg-slate-50/50">
                        <td class="px-3 py-2 border-r border-slate-100 font-bold sticky left-0 bg-inherit z-10">
                            {{ $item->cabang }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 font-mono text-orange-600 sticky left-[60px] bg-inherit z-10">
                            {{ $item->no_penjualan }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-bold text-slate-700">
                            {{ $item->pelanggan_name }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">
                            {{ $item->jatuh_tempo ? date('d/m/Y', strtotime($item->jatuh_tempo)) : '-' }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-center">
                            <span
                                class="px-2 py-0.5 rounded {{ $item->umur_piutang > 30 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }} font-bold">{{ $item->umur_piutang }}</span>
                        </td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 text-right font-bold bg-orange-50/30 text-orange-700">
                            {{ number_format($item->nilai, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->sales_name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-slate-400">Data AR kosong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $ars->links() }}</div>
    </div>
</div>