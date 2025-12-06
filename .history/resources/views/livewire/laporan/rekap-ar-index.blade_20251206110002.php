<div class="space-y-6">

    <div
        class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No Penjualan, Pelanggan..."
                class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-orange-500 focus:ring-orange-500 placeholder-gray-400 transition-colors">
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
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px] font-bold">Cabang</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">No Penjualan</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[150px]">Nama Pelanggan</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Kode Pel.</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">Sales</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Info</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Total Nilai</th>
                        <th
                            class="px-3 py-3 border-b border-r bg-orange-100 text-orange-900 min-w-[100px] text-right font-bold">
                            Nilai (Sisa)</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[90px]">Tgl Jual</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[90px]">Tgl Antar</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Status Antar</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[90px]">Jatuh Tempo</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">Current</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">
                            <= 15 Hari</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">16-30 Hari</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">> 30 Hari</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Status</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[200px]">Alamat</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Phone</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[60px] text-center">Umur</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">Unique ID</th>

                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">
                            < 14 Days</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">> 14 < 30</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] text-right">UP 30 Days</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Range Piutang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($ar as $item)
                    <tr class="hover:bg-orange-50 transition-colors">
                        <td class="px-3 py-2 border-r font-medium text-orange-600 whitespace-nowrap">{{ $item->cabang }}
                        </td>
                        <td class="px-3 py-2 border-r font-mono whitespace-nowrap">{{ $item->no_penjualan }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap font-medium">{{ $item->pelanggan_name }}</td>

                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->pelanggan_code }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->sales_name }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->info }}</td>

                        <td class="px-3 py-2 border-r text-right text-gray-500">{{ $item->total_nilai }}</td>
                        <td class="px-3 py-2 border-r text-right font-bold text-orange-600 bg-orange-50">
                            {{ $item->nilai }}</td>

                        <td class="px-3 py-2 border-r whitespace-nowrap">
                            {{ $item->tgl_penjualan ? \Carbon\Carbon::parse($item->tgl_penjualan)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">
                            {{ $item->tgl_antar ? \Carbon\Carbon::parse($item->tgl_antar)->format('d-m-Y') : '-' }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->status_antar }}</td>
                        <td
                            class="px-3 py-2 border-r whitespace-nowrap {{ $item->jatuh_tempo && \Carbon\Carbon::parse($item->jatuh_tempo)->isPast() ? 'text-red-600 font-bold' : '' }}">
                            {{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d-m-Y') : '-' }}
                        </td>

                        <td class="px-3 py-2 border-r text-right">{{ $item->current }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->le_15_days }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->bt_16_30_days }}</td>
                        <td class="px-3 py-2 border-r text-right text-red-600">{{ $item->gt_30_days }}</td>

                        <td class="px-3 py-2 border-r whitespace-nowrap">
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-bold border 
                                {{ $item->status == 'Lunas' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-orange-100 text-orange-800 border-orange-200' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-3 py-2 border-r whitespace-nowrap truncate max-w-[200px]"
                            title="{{ $item->alamat }}">{{ $item->alamat }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->phone }}</td>
                        <td class="px-3 py-2 border-r text-center font-bold">{{ $item->umur_piutang }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap text-xs text-gray-400">{{ $item->unique_id }}
                        </td>

                        <td class="px-3 py-2 border-r text-right">{{ $item->lt_14_days }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->bt_14_30_days }}</td>
                        <td class="px-3 py-2 border-r text-right">{{ $item->up_30_days }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->range_piutang }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="25" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <i class="fas fa-file-invoice-dollar fa-3x mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Belum Ada Data Rekap Piutang</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $ar->links() }}
        </div>
    </div>
</div>