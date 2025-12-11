<div class="space-y-6 font-jakarta">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Rekap Penjualan</h2>
            <p class="text-sm text-slate-500 mt-1">Data detail penjualan sesuai format Excel ({{ $penjualans->total() }} Baris).</p>
        </div>
        
        <button class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all">
            <i class="fas fa-file-export mr-2"></i> Export Excel
        </button>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pencarian</label>
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full border-slate-200 rounded-xl text-sm focus:ring-emerald-500 placeholder-slate-400" placeholder="Cari No Faktur, Pelanggan, SKU...">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Periode</label>
            <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl border border-slate-200">
                <input type="date" wire:model.live="startDate" class="bg-transparent border-none text-xs font-bold text-slate-700 w-32">
                <span class="text-slate-300">|</span>
                <input type="date" wire:model.live="endDate" class="bg-transparent border-none text-xs font-bold text-slate-700 w-32">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[70vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap w-full">
                <thead class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200 sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-0 z-30 shadow-sm">Cabang</th>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-[60px] z-30 shadow-sm">No Faktur</th>
                        
                        <th class="px-3 py-3 border-r border-slate-200">Status</th>
                        <th class="px-3 py-3 border-r border-slate-200">Tanggal</th>
                        <th class="px-3 py-3 border-r border-slate-200">Period</th>
                        <th class="px-3 py-3 border-r border-slate-200">Jatuh Tempo</th>
                        <th class="px-3 py-3 border-r border-slate-200">Kode Pelanggan</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[150px]">Nama Pelanggan</th>
                        <th class="px-3 py-3 border-r border-slate-200">Kode Item</th>
                        <th class="px-3 py-3 border-r border-slate-200">SKU</th>
                        <th class="px-3 py-3 border-r border-slate-200">No Batch</th>
                        <th class="px-3 py-3 border-r border-slate-200">Expired Date</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[200px]">Nama Item</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right bg-emerald-50 text-emerald-700">Qty</th>
                        <th class="px-3 py-3 border-r border-slate-200">Satuan</th>
                        
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Nilai</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Rata2</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">D1</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">D2</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Diskon 1</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Diskon 2</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Disc Bawah</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Total Diskon</th>
                        
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Nilai Net</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Harga Jual</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">PPN</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right font-bold bg-yellow-50 text-yellow-700">Total Grand</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Margin</th>
                        
                        <th class="px-3 py-3 border-r border-slate-200">Pembayaran</th>
                        <th class="px-3 py-3 border-r border-slate-200">Salesman</th>
                        <th class="px-3 py-3 border-r border-slate-200">Supplier</th>
                        <th class="px-3 py-3 border-r border-slate-200">Divisi</th>
                        <th class="px-3 py-3 border-r border-slate-200">Mother SKU</th>
                        <th class="px-3 py-3 border-r border-slate-200">City Code</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($penjualans as $item)
                    <tr class="hover:bg-emerald-50/20 transition-colors odd:bg-white even:bg-slate-50/50">
                        <td class="px-3 py-2 border-r border-slate-100 font-bold text-slate-700 sticky left-0 bg-inherit z-10">{{ $item->cabang }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-mono text-emerald-600 sticky left-[60px] bg-inherit z-10">{{ $item->trans_no }}</td>
                        
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->status }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->tgl_penjualan }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->period }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->jatuh_tempo }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->kode_pelanggan }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[200px]" title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-mono">{{ $item->kode_item }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-mono">{{ $item->sku }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->no_batch }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->ed }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 truncate max-w-[250px]" title="{{ $item->nama_item }}">{{ $item->nama_item }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right font-bold bg-emerald-50/30 text-emerald-700">{{ number_format($item->qty, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->satuan_jual }}</td>
                        
                        <td class="px-3 py-2 border-r border-slate-100 text-right">{{ number_format($item->nilai, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">{{ number_format($item->rata2, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">{{ number_format($item->d1, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">{{ number_format($item->d2, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">{{ number_format($item->diskon_1, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">{{ number_format($item->diskon_2, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">{{ number_format($item->diskon_bawah, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right text-rose-600">{{ number_format($item->total_diskon, 0, ',', '.') }}</td>
                        
                        <td class="px-3 py-2 border-r border-slate-100 text-right">{{ number_format($item->nilai_jual_net, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">{{ number_format($item->total_harga_jual, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">{{ number_format($item->ppn_head, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right font-bold bg-yellow-50/50 text-yellow-700">{{ number_format($item->total_grand, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right text-blue-600">{{ number_format($item->margin, 0, ',', '.') }}</td>
                        
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->pembayaran }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->sales_name }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->supplier }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->divisi }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->mother_sku }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->city_code_outlet_program }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="35" class="px-6 py-12 text-center text-slate-400">Data tidak ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">
            {{ $penjualans->links() }}
        </div>
    </div>
</div>