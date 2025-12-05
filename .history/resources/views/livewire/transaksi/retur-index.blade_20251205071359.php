<div class="space-y-4">

    <!-- Notifikasi -->
    <div>
        @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow mb-4">
            <p class="font-bold"><i class="fas fa-check-circle"></i> Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif
        @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow mb-4">
            <p class="font-bold">Gagal!</p>
            <p>{{ session('error') }}</p>
        </div>
        @endif
        @error('file')
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 shadow mb-4">
            <p class="font-bold">Peringatan:</p>
            <p>{{ $message }}</p>
        </div>
        @enderror
    </div>

    <!-- Controls -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="w-full {{ !$isLaporanMode ? 'md:w-1/3' : 'md:w-2/3' }} relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No Retur / Pelanggan..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <div class="absolute left-3 top-2.5 text-gray-400"><i class="fas fa-search"></i></div>
            </div>

            @if (!$isLaporanMode)
            <div class="w-full md:w-auto bg-gray-50 p-2 rounded-lg border border-dashed border-gray-300">
                <form wire:submit.prevent="import" class="flex flex-col md:flex-row gap-2 items-center">
                    <input type="file" wire:model="file" id="upload_{{ $iteration }}"
                        class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer" />
                    <button type="submit" wire:loading.attr="disabled" wire:target="file, import"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-4 py-1.5 rounded text-sm font-bold shadow flex items-center gap-2 whitespace-nowrap">
                        <span wire:loading.remove wire:target="import"><i class="fas fa-file-import"></i> Import
                            Retur</span>
                        <span wire:loading wire:target="import"><i class="fas fa-spinner fa-spin"></i> Proses...</span>
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col">
        <div class="flex-1 overflow-x-auto overflow-y-auto min-w-full" style="max-height: calc(100vh - 200px);">
            <table class="min-w-max text-xs text-left border-collapse table-auto">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-20">
                    <tr>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[100px] whitespace-nowrap">Cabang</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[120px] whitespace-nowrap">No Retur
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Status</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[90px] whitespace-nowrap">Tgl Retur
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[120px] whitespace-nowrap">No Invoice
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Kode Pel</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[150px] whitespace-nowrap">Nama
                            Pelanggan</th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Kode Item
                        </th>
                        <th class="px-2 py-2 border-b border-r bg-gray-100 min-w-[200px] whitespace-nowrap">Nama Item
                        </th>

                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Qty</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Satuan</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Rata2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Up %</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai Up</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai+Pemb</th>

                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">D1</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">D2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Disc 1</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">Disc 2</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Disc Bawah</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total Disc</th>

                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Nilai Net</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total Harga</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">PPN Head</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] bg-green-50 font-bold whitespace-nowrap">
                            TOTAL</th>
                        <th class="px-2 py-2 border-b border-r min-w-[80px] whitespace-nowrap">PPN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Total - PPN</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">MARGIN</th>

                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Pembayaran</th>
                        <th class="px-2 py-2 border-b border-r min-w-[120px] whitespace-nowrap">Sales</th>
                        <th class="px-2 py-2 border-b border-r min-w-[120px] whitespace-nowrap">Supplier</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Year</th>
                        <th class="px-2 py-2 border-b border-r min-w-[60px] whitespace-nowrap">Month</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Divisi</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Program</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">City Code</th>
                        <th class="px-2 py-2 border-b border-r min-w-[100px] whitespace-nowrap">Mother SKU</th>
                        <th class="px-2 py-2 border-b border-r min-w-[150px] whitespace-nowrap">Last Supp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($retur as $item)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-2 py-1 border-r font-bold text-blue-800 whitespace-nowrap">{{ $item->cabang }}
                        </td>
                        <td class="px-2 py-1 border-r font-mono whitespace-nowrap">{{ $item->no_retur }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->status }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">
                            {{ $item->tgl_retur ? \Carbon\Carbon::parse($item->tgl_retur)->format('d-m-Y') : '-' }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->no_inv }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kode_pelanggan }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap truncate max-w-[200px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->kode_item }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap truncate max-w-[200px]"
                            title="{{ $item->nama_item }}">{{ $item->nama_item }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->qty }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->satuan_retur }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->rata2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->up_percent }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_up }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_retur_pembulatan }}
                        </td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->d1 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->d2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_1 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_2 }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->diskon_bawah }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total_diskon }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->nilai_retur_net }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total_harga_retur }}</td>
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
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->sales_name }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->supplier }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->year }}</td>
                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">{{ $item->month }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->divisi }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->program }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->city_code }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->mother_sku }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->last_suppliers }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="39" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <i class="fas fa-undo fa-3x text-gray-300"></i>
                            <h3 class="text-lg font-bold text-gray-600">Belum Ada Data Retur</h3>
                            <p class="text-sm max-w-sm">Silakan upload file Excel Rekap Retur.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t bg-gray-50">
            {{ $retur->links() }}
        </div>
    </div>
</div>