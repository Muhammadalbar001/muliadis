<div class="space-y-4">

    <!-- HEADER CONTROLS -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Search Bar -->
            <div class="w-full md:w-1/2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Cari No Faktur, Pelanggan, Sales..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
            </div>
            <!-- Tombol Export -->
            <button
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-bold shadow flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- TABEL DATA 25 KOLOM -->
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col">
        <div class="flex-1 overflow-x-auto overflow-y-auto min-w-full" style="max-height: calc(100vh - 200px);">
            <table class="min-w-max text-xs text-left border-collapse table-auto">
                <!-- HEADER (NON-STICKY) -->
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <!-- === KOLOM KIRI (FIXED NO MORE) === -->
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Cabang</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[120px] whitespace-nowrap">No Penjualan</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[150px] whitespace-nowrap">Nama Pelanggan</th>

                        <!-- === KOLOM DATA LAINNYA === -->
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Kode Pel.</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[120px] whitespace-nowrap">Sales</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[100px] whitespace-nowrap">Info</th>

                        <!-- NILAI -->
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[100px] text-right whitespace-nowrap">Total
                            Nilai</th>
                        <th
                            class="px-2 py-2 border-r bg-gray-100 min-w-[100px] text-right font-bold bg-yellow-50 whitespace-nowrap">
                            Nilai (Sisa)</th>

                        <!-- TANGGAL -->
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[90px] whitespace-nowrap">Tgl Jual</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[90px] whitespace-nowrap">Tgl Antar</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Status Antar</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[90px] whitespace-nowrap">Jatuh Tempo</th>

                        <!-- AGING 1 -->
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[100px] text-right whitespace-nowrap">Current
                        </th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[100px] text-right whitespace-nowrap">
                            <= 15 Hari</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[100px] text-right whitespace-nowrap">16-30 Hari
                        </th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[100px] text-right whitespace-nowrap">> 30 Hari
                        </th>

                        <!-- META -->
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[80px] whitespace-nowrap">Status</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[200px] whitespace-nowrap">Alamat</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[100px] whitespace-nowrap">Phone</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[60px] text-center whitespace-nowrap">Umur
                            Piutang</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[120px] whitespace-nowrap">Unique ID</th>

                        <!-- AGING 2 (Additional) -->
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[100px] text-right whitespace-nowrap">
                            < 14 Days</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[100px] text-right whitespace-nowrap">> 14 <
                                30</th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[100px] text-right whitespace-nowrap">UP 30 Days
                        </th>
                        <th class="px-2 py-2 border-r bg-gray-100 min-w-[100px] whitespace-nowrap">Range Piutang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($ar as $item)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <!-- KOLOM KIRI (NON-STICKY) -->
                        <td class="px-2 py-1 border-r font-bold text-blue-800 whitespace-nowrap">{{ $item->cabang }}
                        </td>
                        <td class="px-2 py-1 border-r font-mono whitespace-nowrap">{{ $item->no_penjualan }}</td>
                        <td class="px-2 py-1 border-r truncate max-w-[150px] whitespace-nowrap"
                            title="{{ $item->pelanggan_name }}">
                            {{ $item->pelanggan_name }}
                        </td>

                        <!-- DATA LAINNYA -->
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->pelanggan_code }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->sales_name }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->info }}</td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->total_nilai }}</td>
                        <td class="px-2 py-1 border-r text-right font-bold text-red-600 bg-yellow-50 whitespace-nowrap">
                            {{ $item->nilai }}</td>

                        <td class="px-2 py-1 border-r whitespace-nowrap">
                            {{ $item->tgl_penjualan ? \Carbon\Carbon::parse($item->tgl_penjualan)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">
                            {{ $item->tgl_antar ? \Carbon\Carbon::parse($item->tgl_antar)->format('d-m-Y') : '-' }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->status_antar }}</td>
                        <td
                            class="px-2 py-1 border-r whitespace-nowrap {{ $item->jatuh_tempo && \Carbon\Carbon::parse($item->jatuh_tempo)->isPast() ? 'text-red-600 font-bold' : '' }}">
                            {{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d-m-Y') : '-' }}
                        </td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->current }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->le_15_days }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->bt_16_30_days }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->gt_30_days }}</td>

                        <td class="px-2 py-1 border-r text-center whitespace-nowrap">
                            <span
                                class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $item->status == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-2 py-1 border-r truncate max-w-[200px] whitespace-nowrap"
                            title="{{ $item->alamat }}">{{ $item->alamat }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->phone }}</td>
                        <td class="px-2 py-1 border-r text-center font-bold whitespace-nowrap">{{ $item->umur_piutang }}
                        </td>
                        <td class="px-2 py-1 border-r text-xs text-gray-400 whitespace-nowrap">{{ $item->unique_id }}
                        </td>

                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->lt_14_days }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->bt_14_30_days }}</td>
                        <td class="px-2 py-1 border-r text-right whitespace-nowrap">{{ $item->up_30_days }}</td>
                        <td class="px-2 py-1 border-r whitespace-nowrap">{{ $item->range_piutang }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="25" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <i class="fas fa-file-invoice-dollar fa-3x text-gray-300"></i>
                                <h3 class="text-lg font-bold text-gray-600">Belum Ada Data Piutang</h3>
                                <p class="text-sm max-w-sm">Silakan lakukan Import data di menu **Transaksi > AR
                                    (Piutang)**.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-2 border-t bg-gray-50">
            {{ $ar->links() }}
        </div>
    </div>
</div>