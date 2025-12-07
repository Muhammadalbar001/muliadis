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
                <p class="text-gray-500 text-xs font-bold uppercase">Total Margin (Profit)</p>
                <h3
                    class="text-xl font-bold {{ ($summary->total_margin ?? 0) < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                    Rp {{ $this->formatUang($summary->total_margin ?? 0) }}
                </h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-full text-emerald-600">
                <i class="fas fa-coins text-2xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Item Terjual</p>
                <h3 class="text-xl font-bold text-gray-800">{{ number_format($summary->total_trx ?? 0) }} <span
                        class="text-sm font-normal text-gray-400">Lines</span></h3>
            </div>
            <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                <i class="fas fa-receipt text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
            </div>
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
            </div>

            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                    <option value="">Semua</option>
                    @foreach($optCabang as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sales</label>
                <select wire:model.live="filterSales"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                    <option value="">Semua</option>
                    @foreach($optSales as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>

            <div class="md:col-span-2 relative">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Pencarian</label>
                <div class="relative">
                    <input wire:model.live.debounce.500ms="search" type="text"
                        placeholder="Invoice, Pelanggan, Barang..."
                        class="w-full pl-10 text-sm border-gray-200 rounded-lg focus:ring-indigo-500">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[70vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="text-gray-500 uppercase bg-gray-50 sticky top-0 z-20 shadow-sm font-bold">
                    <tr>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 min-w-[80px] sticky left-0 z-30">Cabang</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 min-w-[120px] sticky left-[80px] z-30">Trans
                            No</th>
                        <th class="px-3 py-3 border-b border-r min-w-[80px]">Status</th>
                        <th class="px-3 py-3 border-b border-r min-w-[90px]">Tanggal</th>
                        <th class="px-3 py-3 border-b border-r min-w-[200px]">Pelanggan</th>
                        <th class="px-3 py-3 border-b border-r min-w-[250px]">Nama Item</th>

                        <th class="px-3 py-3 border-b border-r text-center bg-blue-50 text-blue-900">Qty</th>
                        <th class="px-3 py-3 border-b border-r text-right">Harga Jual</th>
                        <th class="px-3 py-3 border-b border-r text-right bg-indigo-50 text-indigo-900 font-bold">Total
                        </th>
                        <th class="px-3 py-3 border-b border-r text-right">Margin</th>

                        <th class="px-3 py-3 border-b border-r">Sales</th>
                        <th class="px-3 py-3 border-b border-r">Jatuh Tempo</th>
                        <th class="px-3 py-3 border-b border-r">Pembayaran</th>
                        <th class="px-3 py-3 border-b border-r">Supplier</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($penjualan as $item)
                    <tr class="hover:bg-indigo-50 transition-colors">
                        <td class="px-3 py-2 border-r font-medium text-indigo-600 sticky left-0 bg-inherit z-10">
                            {{ $item->cabang }}</td>
                        <td class="px-3 py-2 border-r font-mono sticky left-[80px] bg-inherit z-10">
                            {{ $item->trans_no }}</td>

                        <td class="px-3 py-2 border-r">
                            <span
                                class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-gray-100 text-gray-600 border border-gray-200">{{ $item->status }}</span>
                        </td>
                        <td class="px-3 py-2 border-r">
                            {{ $item->tgl_penjualan ? date('d/m/y', strtotime($item->tgl_penjualan)) : '-' }}</td>
                        <td class="px-3 py-2 border-r truncate max-w-[180px]" title="{{ $item->nama_pelanggan }}">
                            {{ $item->nama_pelanggan }}</td>
                        <td class="px-3 py-2 border-r truncate max-w-[220px]" title="{{ $item->nama_item }}">
                            {{ $item->nama_item }}</td>

                        <td class="px-3 py-2 border-r text-center font-bold bg-blue-50/30">{{ $item->qty }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $this->formatUang($item->nilai_jual_net) }}</td>
                        <td class="px-3 py-2 border-r text-right font-bold text-indigo-700 bg-indigo-50/30">
                            {{ $this->formatUang($item->total_grand) }}</td>
                        <td
                            class="px-3 py-2 border-r text-right font-medium {{ str_contains($item->margin, '-') ? 'text-red-600' : 'text-emerald-600' }}">
                            {{ $this->formatUang($item->margin) }}
                        </td>

                        <td class="px-3 py-2 border-r">{{ $item->sales_name }}</td>
                        <td
                            class="px-3 py-2 border-r {{ $item->jatuh_tempo && now()->gt($item->jatuh_tempo) ? 'text-red-500 font-bold' : '' }}">
                            {{ $item->jatuh_tempo ? date('d/m/y', strtotime($item->jatuh_tempo)) : '-' }}
                        </td>
                        <td class="px-3 py-2 border-r">{{ $item->pembayaran }}</td>
                        <td class="px-3 py-2 border-r truncate max-w-[100px]">{{ $item->supplier }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="px-6 py-12 text-center text-gray-400">Tidak ada data penjualan sesuai
                            filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $penjualan->links() }}</div>
    </div>
</div>