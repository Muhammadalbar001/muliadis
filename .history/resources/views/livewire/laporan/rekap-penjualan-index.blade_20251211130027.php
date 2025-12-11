<div class="space-y-6 font-jakarta">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Rekap Penjualan</h2>
            <p class="text-sm text-slate-500 mt-1">Data detail penjualan sesuai format Excel ({{ $penjualans->total() }}
                Baris).</p>
        </div>
        <button wire:click="export" wire:loading.attr="disabled"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all">
            <span wire:loading.remove wire:target="export"><i class="fas fa-file-export mr-2"></i> Export Excel</span>
            <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin mr-2"></i> Processing...</span>
        </button>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pencarian</label>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="w-full border-slate-200 rounded-xl text-sm focus:ring-emerald-500 placeholder-slate-400"
                placeholder="Cari No Faktur, Pelanggan, SKU...">
        </div>
        <div class="w-auto">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Periode</label>
            <div
                class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl border border-slate-200 hover:border-emerald-300 transition-colors">
                <input type="date" wire:model.live="startDate"
                    class="bg-transparent border-none text-xs font-bold text-slate-700 w-32 cursor-pointer">
                <span class="text-slate-300">|</span>
                <input type="date" wire:model.live="endDate"
                    class="bg-transparent border-none text-xs font-bold text-slate-700 w-32 cursor-pointer">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[70vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap w-full">
                <thead
                    class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200 sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-0 z-30 shadow-sm">Cabang
                        </th>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-[60px] z-30 shadow-sm">No
                            Faktur</th>

                        <th class="px-3 py-3 border-r border-slate-200">Status</th>
                        <th class="px-3 py-3 border-r border-slate-200">Tanggal</th>
                        <th class="px-3 py-3 border-r border-slate-200">Period</th>
                        <th class="px-3 py-3 border-r border-slate-200">Jatuh Tempo</th>
                        <th class="px-3 py-3 border-r border-slate-200">Kode Pelanggan</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[200px]">Nama Pelanggan</th>

                        <th class="px-3 py-3 border-r border-slate-200">Kode Item</th>
                        <th class="px-3 py-3 border-r border-slate-200">SKU</th>
                        <th class="px-3 py-3 border-r border-slate-200">No Batch</th>
                        <th class="px-3 py-3 border-r border-slate-200">Expired Date</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[200px]">Nama Item</th>

                        <th class="px-3 py-3 border-r border-slate-200 text-right bg-emerald-50 text-emerald-700">Qty
                        </th>
                        <th class="px-3 py-3 border-r border-slate-200">Satuan Jual</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Qty I</th>
                        <th class="px-3 py-3 border-r border-slate-200">Satuan I</th>

                        <th class="px-3 py-3 border-r border-slate-200 text-right">Nilai</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Rata2</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Up %</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Nilai Up</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Nilai Jual (Bulat)</th>

                        <th class="px-3 py-3 border-r border-slate-200 text-right">D1</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">D2</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Diskon 1</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Diskon 2</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Disc Bawah</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right text-rose-600">Total Diskon</th>

                        <th class="px-3 py-3 border-r border-slate-200 text-right">Nilai Jual Net</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Total Harga Jual</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">PPN Head</th>
                        <th
                            class="px-3 py-3 border-r border-slate-200 text-right font-bold bg-yellow-50 text-yellow-700">
                            Total Grand</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">PPN Value</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Total Min PPN</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right text-blue-600">Margin</th>

                        <th class="px-3 py-3 border-r border-slate-200">Pembayaran</th>
                        <th class="px-3 py-3 border-r border-slate-200">Cash Bank</th>
                        <th class="px-3 py-3 border-r border-slate-200">Kode Sales</th>
                        <th class="px-3 py-3 border-r border-slate-200">Sales Name</th>
                        <th class="px-3 py-3 border-r border-slate-200">Supplier</th>
                        <th class="px-3 py-3 border-r border-slate-200">Status Pay</th>
                        <th class="px-3 py-3 border-r border-slate-200">Trx ID</th>
                        <th class="px-3 py-3 border-r border-slate-200">Year</th>
                        <th class="px-3 py-3 border-r border-slate-200">Month</th>
                        <th class="px-3 py-3 border-r border-slate-200">Last Suppliers</th>
                        <th class="px-3 py-3 border-r border-slate-200">Mother SKU</th>
                        <th class="px-3 py-3 border-r border-slate-200">Divisi</th>
                        <th class="px-3 py-3 border-r border-slate-200">Program</th>
                        <th class="px-3 py-3 border-r border-slate-200">Outlet Sales</th>
                        <th class="px-3 py-3 border-r border-slate-200">City Code</th>
                        <th class="px-3 py-3 border-r border-slate-200">Sales Outlet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($penjualans as $item)
                    <tr class="hover:bg-emerald-50/20 transition-colors odd:bg-white even:bg-slate-50/50">
                        <td
                            class="px-3 py-2 border-r border-slate-100 font-bold text-slate-700 sticky left-0 bg-inherit z-10">
                            {{ $item->cabang }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 font-mono text-emerald-600 sticky left-[60px] bg-inherit z-10">
                            {{ $item->trans_no }}</td>

                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->status }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->period }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->jatuh_tempo }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->kode_pelanggan }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-bold truncate max-w-[200px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>

                        <td class="px-3 py-2 border-r border-slate-100 font-mono">{{ $item->kode_item }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-mono">{{ $item->sku }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->no_batch }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->ed }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 truncate max-w-[200px]"
                            title="{{ $item->nama_item }}">{{ $item->nama_item }}</td>

                        <td class="px-3 py-2 border-r border-slate-100 text-right font-bold text-emerald-700">
                            {{ number_format($item->qty, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->satuan_jual }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->qty_i, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->satuan_i }}</td>

                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->nilai, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->rata2, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->up_percent, 2, ',', '.') }}%</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->nilai_up, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->nilai_jual_pembulatan, 0, ',', '.') }}</td>

                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->d1, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->d2, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->diskon_1, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->diskon_2, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->diskon_bawah, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right text-rose-600">
                            {{ number_format($item->total_diskon, 0, ',', '.') }}</td>

                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->nilai_jual_net, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->total_harga_jual, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->ppn_head, 0, ',', '.') }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 text-right font-bold bg-yellow-50/50 text-yellow-700">
                            {{ number_format($item->total_grand, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->ppn_value, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->total_min_ppn, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right text-blue-600 font-bold">
                            {{ number_format($item->margin, 0, ',', '.') }}</td>

                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->pembayaran }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->cash_bank }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->kode_sales }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->sales_name }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->supplier }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->status_pay }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->trx_id }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->year }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->month }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->last_suppliers }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->mother_sku }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->divisi }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->program }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->outlet_code_sales_name }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->city_code_outlet_program }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->sales_name_outlet_code }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="51" class="px-6 py-12 text-center text-slate-400">Data tidak ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $penjualans->links() }}</div>
    </div>
</div>