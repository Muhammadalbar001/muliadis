<div class="space-y-6">

    <div
        class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400 transition-colors"
                placeholder="Cari Trans No, Pelanggan, atau SKU...">
        </div>

        <button
            class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm hover:shadow-md">
            <i class="fas fa-file-excel mr-2"></i> Export Excel
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[80vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="text-xs text-gray-600 uppercase bg-gray-100 sticky top-0 z-20 shadow-sm">
                    <tr>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] font-bold">Cabang</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">Trans No</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Status</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[90px]">Tgl Jual</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Period</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[90px]">Jatuh Tempo</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Kode Pel</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[200px]">Nama Pelanggan</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Kode Item</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">SKU</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[90px]">No Batch</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[90px]">ED</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[250px]">Nama Item</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[60px] text-center">Qty</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Satuan</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[60px] text-center">Qty I</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Satuan I</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Nilai</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Rata2</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[60px] text-right">Up %</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Nilai Up</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">N. Jual+Pemb</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[60px] text-center">D1</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[60px] text-center">D2</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">Disc 1</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">Disc 2</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Disc Bawah</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Total Disc</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">N. Jual Net</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Total Harga</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">PPN Head</th>
                        <th
                            class="px-3 py-3 border-b border-r bg-emerald-100 text-emerald-900 min-w-[120px] text-right font-bold">
                            TOTAL</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] text-right">PPN</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Total - PPN</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">MARGIN</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Pembayaran</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Cash/Bank</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Kode Sales</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">Sales</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">Supplier</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Status Pay</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Trx ID</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[60px]">Year</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[60px]">Month</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[150px]">Last Supp</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Mother SKU</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Divisi</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Program</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[150px]">Outlet Sales</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[150px]">City Code</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[150px]">Sales Outlet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($penjualan as $item)
                    <tr class="hover:bg-indigo-50 transition-colors">
                        <td class="px-3 py-2 border-r font-medium text-indigo-600 whitespace-nowrap">{{ $item->cabang }}
                        </td>
                        <td class="px-3 py-2 border-r font-mono whitespace-nowrap">{{ $item->trans_no }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->status }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">
                            {{ $item->tgl_penjualan ? \Carbon\Carbon::parse($item->tgl_penjualan)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->period }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">
                            {{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-3 py-2 border-r whitespace-nowrap text-gray-500">{{ $item->kode_pelanggan }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap font-medium text-gray-800">
                            {{ $item->nama_pelanggan }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->kode_item }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap font-mono">{{ $item->sku }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->no_batch }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap text-red-500">
                            {{ $item->ed ? \Carbon\Carbon::parse($item->ed)->format('d-m-Y') : '-' }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap text-gray-700">{{ $item->nama_item }}</td>

                        <td class="px-3 py-2 border-r text-center font-bold">{{ $item->qty }}</td>
                        <td class="px-3 py-2 border-r text-gray-500">{{ $item->satuan_jual }}</td>
                        <td class="px-3 py-2 border-r text-center">{{ $item->qty_i }}</td>
                        <td class="px-3 py-2 border-r text-gray-500">{{ $item->satuan_i }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->nilai }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->rata2 }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->up_percent }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->nilai_up }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->nilai_jual_pembulatan }}</td>

                        <td class="px-3 py-2 border-r text-center">{{ $item->d1 }}</td>
                        <td class="px-3 py-2 border-r text-center">{{ $item->d2 }}</td>
                        <td class="px-3 py-2 border-r text-right text-red-500">{{ $item->diskon_1 }}</td>
                        <td class="px-3 py-2 border-r text-right text-red-500">{{ $item->diskon_2 }}</td>
                        <td class="px-3 py-2 border-r text-right text-red-500">{{ $item->diskon_bawah }}</td>
                        <td class="px-3 py-2 border-r text-right text-red-600 font-medium">{{ $item->total_diskon }}
                        </td>

                        <td class="px-3 py-2 border-r text-right">{{ $item->nilai_jual_net }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->total_harga_jual }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->ppn_head }}</td>
                        <td class="px-3 py-2 border-r text-right font-bold bg-emerald-50 text-emerald-700">
                            {{ $item->total_grand }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->ppn_value }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->total_min_ppn }}</td>
                        <td
                            class="px-3 py-2 border-r text-right font-medium {{ str_contains($item->margin, '-') ? 'text-red-600' : 'text-blue-600' }}">
                            {{ $this->formatNegativeParentheses($item->margin) }}
                        </td>

                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->pembayaran }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->cash_bank }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->kode_sales }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->sales_name }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->supplier }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">
                            @if($item->status_pay == 'Lunas')
                            <span
                                class="px-2 py-0.5 rounded-full bg-green-100 text-green-800 text-[10px] font-bold">LUNAS</span>
                            @else
                            <span
                                class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800 text-[10px] font-bold">{{ $item->status_pay }}</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 border-r whitespace-nowrap text-xs text-gray-400">{{ $item->trx_id }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->year }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->month }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->last_suppliers }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->mother_sku }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->divisi }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->program }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->outlet_code_sales_name }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->city_code_outlet_program }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->sales_name_outlet_code }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="51" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <i class="fas fa-database fa-3x mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Belum Ada Data Rekap Penjualan</p>
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