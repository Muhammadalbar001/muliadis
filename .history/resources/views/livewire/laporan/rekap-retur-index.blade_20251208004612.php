<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gradient-to-r from-red-600 to-red-500 rounded-xl p-5 text-white shadow-lg">
            <p class="text-red-100 text-xs font-bold uppercase">Total Nilai Retur</p>
            <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($summary->total_nilai_retur ?? 0, 0, ',', '.') }}
            </h3>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <p class="text-gray-500 text-xs font-bold uppercase">Total Nota Retur</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($summary->total_trx ?? 0) }}</h3>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Dari</label>
                <input type="date" wire:model.live="startDate" class="w-full text-sm border-gray-200 rounded-lg">
            </div>
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sampai</label>
                <input type="date" wire:model.live="endDate" class="w-full text-sm border-gray-200 rounded-lg">
            </div>
            <div class="md:col-span-1">
                <select wire:model.live="filterCabang" class="w-full text-sm border-gray-200 rounded-lg">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>
            <div class="md:col-span-2 relative">
                <input wire:model.live.debounce.500ms="search" type="text" placeholder="No Retur, Pelanggan..."
                    class="w-full pl-10 text-sm border-gray-200 rounded-lg">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[70vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="text-[10px] text-gray-600 uppercase bg-gray-50 sticky top-0 z-20 shadow-sm font-bold">
                    <tr>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 min-w-[80px]">Cabang</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 min-w-[120px]">No Retur</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Status</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Retur</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">No Inv</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Kode</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 min-w-[200px]">Nama Pelanggan</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Kode Item</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 min-w-[250px]">Nama Item</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-center">Qty</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Satuan Retur</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Nilai</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">RATA2</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Up (%)</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Nilai Up</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Nilai Retur + Pembulatan</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-center">D1</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-center">D2</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Diskon1</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Diskon2</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Diskon Bawah</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">T. Diskon</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Nilai Retur - T. Diskon</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">Total Harga Retur</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">PPN Head</th>
                        <th class="px-2 py-3 border-b border-r bg-red-100 text-red-900 text-right font-bold">TOTAL</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">PPN</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">TOTAL-PPN</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50 text-right">MARGIN</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Pembayaran</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Sales</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Supplier</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Year</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Month</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Divisi</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Program</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">City & Code</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Mother SKU</th>
                        <th class="px-2 py-3 border-b border-r bg-gray-50">Last Suppliers</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($retur as $item)
                    <tr class="hover:bg-red-50 transition-colors">
                        <td class="px-2 py-1.5 border-r font-medium text-red-600">{{ $item->cabang }}</td>
                        <td class="px-2 py-1.5 border-r font-mono">{{ $item->no_retur }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->status }}</td>
                        <td class="px-2 py-1.5 border-r">
                            {{ $item->tgl_retur ? date('d-m-Y', strtotime($item->tgl_retur)) : '-' }}</td>
                        <td class="px-2 py-1.5 border-r text-gray-500">{{ $item->no_inv }}</td>
                        <td class="px-2 py-1.5 border-r text-gray-500">{{ $item->kode_pelanggan }}</td>
                        <td class="px-2 py-1.5 border-r font-medium truncate max-w-xs"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->kode_item }}</td>
                        <td class="px-2 py-1.5 border-r truncate max-w-xs" title="{{ $item->nama_item }}">
                            {{ $item->nama_item }}</td>
                        <td class="px-2 py-1.5 border-r text-center font-bold bg-red-50/50">{{ $item->qty }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->satuan_retur }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->nilai, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->rata2, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">{{ $item->up_percent }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->nilai_up, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->nilai_retur_pembulatan, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-center">{{ $item->d1 }}</td>
                        <td class="px-2 py-1.5 border-r text-center">{{ $item->d2 }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->diskon_1, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->diskon_2, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->diskon_bawah, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->total_diskon, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->nilai_retur_net, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->total_harga_retur, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->ppn_head, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right font-bold text-red-700 bg-red-50/50">
                            {{ number_format((float)$item->total_grand, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->ppn_value, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->total_min_ppn, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->margin, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->pembayaran }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->sales_name }}</td>
                        <td class="px-2 py-1.5 border-r truncate max-w-[100px]">{{ $item->supplier }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->year }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->month }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->divisi }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->program }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->city_code }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->mother_sku }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->last_suppliers }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="39" class="px-6 py-12 text-center text-gray-400">Data Retur Kosong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $retur->links() }}</div>
    </div>
</div>