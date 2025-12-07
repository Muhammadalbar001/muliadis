<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
            class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider">Total Penjualan (Omzet)</p>
                <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($summary->total_omzet ?? 0, 0, ',', '.') }}</h3>
            </div>
            <i class="fas fa-chart-line absolute right-4 bottom-4 text-white/20 text-6xl"></i>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Margin</p>
                <h3
                    class="text-xl font-bold {{ ($summary->total_margin ?? 0) < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                    Rp {{ $this->formatUang($summary->total_margin ?? 0) }}
                </h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-full text-emerald-600"><i class="fas fa-coins text-2xl"></i></div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Baris</p>
                <h3 class="text-xl font-bold text-gray-800">{{ number_format($summary->total_trx ?? 0) }}</h3>
            </div>
            <div class="p-3 bg-blue-50 rounded-full text-blue-600"><i class="fas fa-receipt text-2xl"></i></div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate" class="w-full text-sm border-gray-200 rounded-lg">
            </div>
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate" class="w-full text-sm border-gray-200 rounded-lg">
            </div>
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Cabang</label>
                <select wire:model.live="filterCabang" class="w-full text-sm border-gray-200 rounded-lg">
                    <option value="">Semua</option>
                    @foreach($optCabang as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sales</label>
                <select wire:model.live="filterSales" class="w-full text-sm border-gray-200 rounded-lg">
                    <option value="">Semua</option>
                    @foreach($optSales as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>
            <div class="md:col-span-2 relative">
                <input wire:model.live.debounce.500ms="search" type="text" placeholder="Cari Trans No, Pelanggan..."
                    class="w-full pl-10 text-sm border-gray-200 rounded-lg">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[70vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead
                    class="text-[10px] text-gray-600 uppercase bg-gray-50 sticky top-0 z-20 shadow-sm font-bold tracking-wider">
                    <tr>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 min-w-[80px]">Cabang</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 min-w-[120px]">Trans No</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Status</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Penjualan</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Period</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Jatuh Tempo</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Kode</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 min-w-[200px]">Nama Pelanggan</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Kode Item</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">SKU</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">No Batch</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">ED</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 min-w-[250px]">Nama Item</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-center">Qty</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Satuan Jual</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-center">Qtyi</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Satuani</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Nilai</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">RATA2</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Up (%)</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Nilai Up</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">N. Jual+ Pembultn</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-center">D1</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-center">D2</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Diskon1</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Diskon2</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Diskon Bawah</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">T. Diskon</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">N. Jual- T. Diskon</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Total Harga Jual</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">PPN Head</th>
                        <th class="px-2 py-3 border-b border-r bg-emerald-100 text-emerald-900 text-right font-bold">
                            TOTAL</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">PPN</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">TOTAL-PPN</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">MARGIN</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Pembayaran</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Cash/Bank</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Kode Sales</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Sales</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Supplier</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Status Pay</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">ID</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Year</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Month</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Last Suppliers</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Mother SKU</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Divisi</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Program</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Outlet Code & Sales Name</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">City & Code Outlet & Program</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Sales Name & Outlet Code</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($penjualan as $item)
                    <tr class="hover:bg-indigo-50 transition-colors">
                        <td class="px-2 py-1.5 border-r font-medium text-indigo-600">{{ $item->cabang }}</td>
                        <td class="px-2 py-1.5 border-r font-mono">{{ $item->trans_no }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->status }}</td>
                        <td class="px-2 py-1.5 border-r">
                            {{ $item->tgl_penjualan ? date('d-m-Y', strtotime($item->tgl_penjualan)) : '' }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->period }}</td>
                        <td
                            class="px-2 py-1.5 border-r {{ $item->jatuh_tempo && now()->gt($item->jatuh_tempo) ? 'text-red-500 font-bold' : '' }}">
                            {{ $item->jatuh_tempo ? date('d-m-Y', strtotime($item->jatuh_tempo)) : '' }}</td>
                        <td class="px-2 py-1.5 border-r text-gray-500">{{ $item->kode_pelanggan }}</td>
                        <td class="px-2 py-1.5 border-r font-medium text-gray-800 truncate max-w-xs"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->kode_item }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->sku }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->no_batch }}</td>
                        <td class="px-2 py-1.5 border-r text-red-500">
                            {{ $item->ed ? date('d-m-Y', strtotime($item->ed)) : '' }}</td>
                        <td class="px-2 py-1.5 border-r truncate max-w-xs" title="{{ $item->nama_item }}">
                            {{ $item->nama_item }}</td>
                        <td class="px-2 py-1.5 border-r text-center font-bold">{{ $item->qty }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->satuan_jual }}</td>
                        <td class="px-2 py-1.5 border-r text-center">{{ $item->qty_i }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->satuan_i }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $this->formatUang($item->nilai) }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $this->formatUang($item->rata2) }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $item->up_percent }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $this->formatUang($item->nilai_up) }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ $this->formatUang($item->nilai_jual_pembulatan) }}</td>
                        <td class="px-2 py-1.5 border-r text-center">{{ $item->d1 }}</td>
                        <td class="px-2 py-1.5 border-r text-center">{{ $item->d2 }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $this->formatUang($item->diskon_1) }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $this->formatUang($item->diskon_2) }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $this->formatUang($item->diskon_bawah) }}</td>
                        <td class="px-2 py-1.5 border-r text-right text-red-600">
                            {{ $this->formatUang($item->total_diskon) }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $this->formatUang($item->nilai_jual_net) }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $this->formatUang($item->total_harga_jual) }}
                        </td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $this->formatUang($item->ppn_head) }}</td>
                        <td class="px-2 py-1.5 border-r text-right font-bold bg-emerald-50 text-emerald-800">
                            {{ $this->formatUang($item->total_grand) }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $this->formatUang($item->ppn_value) }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $this->formatUang($item->total_min_ppn) }}</td>
                        <td
                            class="px-2 py-1.5 border-r text-right {{ str_contains($item->margin, '-') ? 'text-red-600' : 'text-blue-600' }}">
                            {{ $this->formatUang($item->margin) }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->pembayaran }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->cash_bank }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->kode_sales }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->sales_name }}</td>
                        <td class="px-2 py-1.5 border-r truncate max-w-[100px]">{{ $item->supplier }}</td>
                        <td class="px-2 py-1.5 border-r">
                            <span
                                class="px-1.5 py-0.5 rounded text-[9px] font-bold {{ $item->status_pay == 'Lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $item->status_pay }}
                            </span>
                        </td>
                        <td class="px-2 py-1.5 border-r text-[9px]">{{ $item->trx_id }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->year }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->month }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->last_suppliers }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->mother_sku }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->divisi }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->program }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->outlet_code_sales_name }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->city_code_outlet_program }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->sales_name_outlet_code }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="51" class="px-6 py-12 text-center text-gray-400">Data Penjualan Kosong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $penjualan->links() }}</div>
    </div>
</div>