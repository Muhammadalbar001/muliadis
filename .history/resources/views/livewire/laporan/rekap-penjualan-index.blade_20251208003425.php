<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
            class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-4 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-indigo-100 text-sm font-medium uppercase tracking-wider">Total Penjualan (Grand Total)
                </p>
                <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($summary->total_omzet ?? 0, 0, ',', '.') }}</h3>
            </div>
            <i class="fas fa-chart-line absolute right-3 bottom-3 text-white/20 text-6xl"></i>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Margin (Profit)</p>
                <h3
                    class="text-xl font-bold {{ ($summary->total_margin ?? 0) < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                    Rp {{ $this->formatUang($summary->total_margin ?? 0) }}
                </h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-full text-emerald-600">
                <i class="fas fa-hand-holding-dollar text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Baris / Transaksi</p>
                <h3 class="text-xl font-bold text-gray-800">{{ number_format($summary->total_trx ?? 0) }} <span
                        class="text-sm font-normal text-gray-500">Items</span></h3>
            </div>
            <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                <i class="fas fa-receipt text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-auto">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate"
                    class="w-full md:w-40 text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
            </div>
            <div class="w-full md:w-auto">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate"
                    class="w-full md:w-40 text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
            </div>

            <div class="w-full md:w-48">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
                </select>
            </div>

            <div class="w-full md:w-48">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sales</label>
                <select wire:model.live="filterSales"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                    <option value="">Semua Sales</option>
                    @foreach($optSales as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
                </select>
            </div>

            <div class="w-full md:flex-1 relative">
                <input wire:model.live.debounce.500ms="search" type="text" placeholder="Cari No Invoice, SKU..."
                    class="w-full pl-10 text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>

            <button
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg shadow-sm transition">
                <i class="fas fa-file-excel mr-2"></i> Export
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[70vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead
                    class="text-[10px] text-gray-500 uppercase bg-gray-50 sticky top-0 z-20 shadow-sm font-bold tracking-wider">
                    <tr>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 min-w-[100px] sticky left-0 z-30">Cabang</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 min-w-[120px] sticky left-[100px] z-30">Trans
                            No</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-50">Status</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Tgl Jual</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Jatuh Tempo</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 min-w-[200px]">Pelanggan</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Sales</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Kode Item</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 min-w-[250px]">Nama Item</th>

                        <th class="px-3 py-3 border-b border-r bg-blue-50 text-blue-800 text-center">Qty</th>
                        <th class="px-3 py-3 border-b border-r bg-blue-50 text-blue-800 text-right">Harga Satuan</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right text-red-600">Disc Total</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">DPP (Net)</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">PPN</th>
                        <th
                            class="px-3 py-3 border-b border-r bg-emerald-100 text-emerald-900 text-right font-bold text-sm">
                            TOTAL GRAND</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">Margin</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">%</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-50">Period</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Kode Pel</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">SKU</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">No Batch</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">ED</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Satuan</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-center">Qty I</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Satuan I</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">Nilai</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">Rata2</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">Up %</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">Nilai Up</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-center">D1</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-center">D2</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">Disc 1</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">Disc 2</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">Disc Bawah</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">Total Harga</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">PPN Head</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 text-right">Total-PPN</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Pembayaran</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Cash/Bank</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Kode Sales</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Supplier</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Status Pay</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Trx ID</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Year</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Month</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Divisi</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">Program</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($penjualan as $index => $item)
                    <tr
                        class="hover:bg-indigo-50 transition-colors {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50/50' }}">
                        <td class="px-3 py-2 border-r font-medium text-indigo-600 bg-inherit sticky left-0 z-10">
                            {{ $item->cabang }}</td>
                        <td class="px-3 py-2 border-r font-mono bg-inherit sticky left-[100px] z-10">
                            {{ $item->trans_no }}</td>

                        <td class="px-3 py-2 border-r"><span
                                class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-gray-200 text-gray-700">{{ $item->status }}</span>
                        </td>
                        <td class="px-3 py-2 border-r">
                            {{ $item->tgl_penjualan ? \Carbon\Carbon::parse($item->tgl_penjualan)->format('d/m/y') : '-' }}
                        </td>
                        <td
                            class="px-3 py-2 border-r {{ $item->jatuh_tempo && \Carbon\Carbon::parse($item->jatuh_tempo)->isPast() ? 'text-red-600 font-bold' : '' }}">
                            {{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d/m/y') : '-' }}
                        </td>
                        <td class="px-3 py-2 border-r truncate max-w-[180px]" title="{{ $item->nama_pelanggan }}">
                            {{ $item->nama_pelanggan }}</td>
                        <td class="px-3 py-2 border-r truncate max-w-[100px]">{{ $item->sales_name }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->kode_item }}</td>
                        <td class="px-3 py-2 border-r truncate max-w-[220px]" title="{{ $item->nama_item }}">
                            {{ $item->nama_item }}</td>

                        <td class="px-3 py-2 border-r text-center font-bold bg-blue-50/50">{{ $item->qty }}</td>
                        <td class="px-3 py-2 border-r text-right bg-blue-50/50">{{ $this->formatUang($item->nilai) }}
                        </td>
                        <td class="px-3 py-2 border-r text-right text-red-500">
                            {{ $item->total_diskon == '0' ? '-' : $this->formatUang($item->total_diskon) }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $this->formatUang($item->nilai_jual_net) }}</td>
                        <td class="px-3 py-2 border-r text-right text-xs text-gray-500">
                            {{ $this->formatUang($item->ppn_value) }}</td>
                        <td class="px-3 py-2 border-r text-right font-bold text-emerald-700 bg-emerald-50/50">
                            {{ $this->formatUang($item->total_grand) }}</td>
                        <td
                            class="px-3 py-2 border-r text-right font-medium {{ str_contains($item->margin, '-') ? 'text-red-600' : 'text-blue-600' }}">
                            {{ $this->formatUang($item->margin) }}</td>
                        <td class="px-3 py-2 border-r text-right text-[10px]">{{ $item->percent_margin }}%</td>

                        <td class="px-3 py-2 border-r text-gray-400">{{ $item->period }}</td>
                        <td class="px-3 py-2 border-r text-gray-400">{{ $item->kode_pelanggan }}</td>
                        <td class="px-3 py-2 border-r text-gray-400">{{ $item->sku }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->no_batch }}</td>
                        <td class="px-3 py-2 border-r text-red-500">
                            {{ $item->ed ? \Carbon\Carbon::parse($item->ed)->format('d/m/y') : '-' }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->satuan_jual }}</td>
                        <td class="px-3 py-2 border-r text-center">{{ $item->qty_i }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->satuan_i }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $this->formatUang($item->nilai) }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $this->formatUang($item->rata2) }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->up_percent }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $this->formatUang($item->nilai_up) }}</td>
                        <td class="px-3 py-2 border-r text-center">{{ $item->d1 }}</td>
                        <td class="px-3 py-2 border-r text-center">{{ $item->d2 }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $this->formatUang($item->diskon_1) }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $this->formatUang($item->diskon_2) }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $this->formatUang($item->diskon_bawah) }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $this->formatUang($item->total_harga_jual) }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $this->formatUang($item->ppn_head) }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $this->formatUang($item->total_min_ppn) }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->pembayaran }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->cash_bank }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->kode_sales }}</td>
                        <td class="px-3 py-2 border-r truncate max-w-[100px]">{{ $item->supplier }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->status_pay }}</td>
                        <td class="px-3 py-2 border-r text-[9px]">{{ $item->trx_id }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->year }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->month }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->divisi }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->program }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="51" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <i class="fas fa-search fa-3x mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Data tidak ditemukan</p>
                            <p class="text-sm">Coba ubah filter tanggal atau kata kunci.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $penjualan->links() }}
        </div>
    </div>
</div>