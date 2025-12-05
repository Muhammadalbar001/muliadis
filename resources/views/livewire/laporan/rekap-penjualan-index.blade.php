<div class="space-y-4">
    <!-- Header Controls -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex justify-between items-center gap-4">
            <div class="w-full md:w-1/2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i
                        class="fas fa-search text-gray-400"></i></div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Cari Trans No / Pelanggan / SKU..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <!-- Tombol Export (Placeholder) -->
            <button
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-bold shadow flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- TABEL 51 KOLOM -->
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col">
        <div class="flex-1 overflow-x-auto overflow-y-auto min-w-full" style="max-height: calc(100vh - 200px);">
            <table class="min-w-max text-xs text-left border-collapse table-auto">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-20">
                    <tr>
                        <!-- Copy Header 51 Kolom dari kode sebelumnya -->
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[100px] whitespace-nowrap">Cabang</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[120px] whitespace-nowrap">Trans No
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Status</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[90px] whitespace-nowrap">Tgl Jual</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Period</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[90px] whitespace-nowrap">Jatuh Tempo
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Kode Pel</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[150px] whitespace-nowrap">Nama
                            Pelanggan</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Kode Item
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[100px] whitespace-nowrap">SKU</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[90px] whitespace-nowrap">No Batch</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[90px] whitespace-nowrap">ED</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[200px] whitespace-nowrap">Nama Item
                        </th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Qty</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Satuan</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Qty I</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Satuan I</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Rata2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Up %</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai Up</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">N. Jual+Pemb</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">D1</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">D2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Disc 1</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Disc 2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Disc Bawah</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total Disc</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">N. Jual Net</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total Harga</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">PPN Head</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] bg-green-50 font-bold whitespace-nowrap">
                            TOTAL</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">PPN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total - PPN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">MARGIN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Pembayaran</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Cash/Bank</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Kode Sales</th>
                        <th class="px-2 py-2 border-b border-r min-w-[120px] whitespace-nowrap">Sales</th>
                        <th class="px-2 py-2 border-b border-r min-w-[120px] whitespace-nowrap">Supplier</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Status Pay</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Trx ID</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Year</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Month</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">Last Supp</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Mother SKU</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Divisi</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Program</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">Outlet Sales</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">City Code</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">Sales Outlet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($penjualan as $item)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-2 py-1 border-r font-bold text-blue-800 whitespace-nowrap">{{ $item->cabang }}
                        </td>
                        <td class="px-2 py-1 border-r font-mono whitespace-nowrap">{{ $item->trans_no }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->status }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">
                            {{ $item->tgl_penjualan ? \Carbon\Carbon::parse($item->tgl_penjualan)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->period }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">
                            {{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kode_pelanggan }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap truncate max-w-[150px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kode_item }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->sku }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->no_batch }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">
                            {{ $item->ed ? \Carbon\Carbon::parse($item->ed)->format('d-m-Y') : '-' }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap truncate max-w-[200px]"
                            title="{{ $item->nama_item }}">{{ $item->nama_item }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->qty }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->satuan_jual }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->qty_i }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->satuan_i }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->rata2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->up_percent }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_up }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_jual_pembulatan }}
                        </td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->d1 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->d2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_1 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_bawah }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total_diskon }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_jual_net }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total_harga_jual }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->ppn_head }}</td>
                        <td class="px-2 py-1 border-r text-right font-bold bg-green-50 whitespace-nowrap">
                            {{ $item->total_grand }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->ppn_value }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total_min_ppn }}</td>
                        <td
                            class="px-2 py-1 border-r text-right whitespace-nowrap @if(str_contains($item->margin, '-')) text-red-600 font-bold @endif">
                            {{ $this->formatNegativeParentheses($item->margin) }}
                        </td>

                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->pembayaran }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->cash_bank }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kode_sales }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->sales_name }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->supplier }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->status_pay }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->trx_id }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->year }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->month }}</td>

                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->last_suppliers }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->mother_sku }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->divisi }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->program }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->outlet_code_sales_name }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->city_code_outlet_program }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->sales_name_outlet_code }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="51" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <i class="fas fa-database fa-3x text-gray-300"></i>
                            <h3 class="text-lg font-bold text-gray-600">Belum Ada Data Rekap</h3>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-2 border-t bg-gray-50">
            {{ $penjualan->links() }}
        </div>
    </div>
</div>