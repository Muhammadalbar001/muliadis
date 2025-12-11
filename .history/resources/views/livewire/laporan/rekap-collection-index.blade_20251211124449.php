<div class="space-y-6 font-jakarta">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Rekap Collection</h2>
            <p class="text-sm text-slate-500 mt-1">Detail pelunasan piutang ({{ $collections->total() }} Baris).</p>
        </div>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pencarian</label>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="w-full border-slate-200 rounded-xl text-sm focus:ring-cyan-500"
                placeholder="No Bukti, Pelanggan...">
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[70vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap w-full">
                <thead
                    class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200 sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-0 z-30">Cabang</th>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-[60px] z-30">No Bukti
                        </th>
                        <th class="px-3 py-3 border-r border-slate-200">Status</th>
                        <th class="px-3 py-3 border-r border-slate-200">Tanggal</th>
                        <th class="px-3 py-3 border-r border-slate-200">Penagih</th>
                        <th class="px-3 py-3 border-r border-slate-200">No Invoice</th>
                        <th class="px-3 py-3 border-r border-slate-200">Kode Cust</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[150px]">Nama Outlet</th>
                        <th class="px-3 py-3 border-r border-slate-200">Salesman</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right bg-cyan-50 text-cyan-700">Jumlah Bayar
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($collections as $item)
                    <tr class="hover:bg-cyan-50/20 transition-colors odd:bg-white even:bg-slate-50/50">
                        <td class="px-3 py-2 border-r border-slate-100 font-bold sticky left-0 bg-inherit z-10">
                            {{ $item->cabang }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 font-mono text-cyan-600 sticky left-[60px] bg-inherit z-10">
                            {{ $item->receive_no }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->status }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->tanggal }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->penagih }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->invoice_no }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->code_customer }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[200px]">
                            {{ $item->outlet_name }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->sales_name }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 text-right font-bold bg-cyan-50/30 text-cyan-700">
                            {{ number_format($item->receive_amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-slate-400">Data collection kosong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $collections->links() }}</div>
    </div>
</div>