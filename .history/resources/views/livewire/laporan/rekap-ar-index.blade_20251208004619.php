<div class="space-y-6">
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 grid grid-cols-2 gap-4">
            <div class="bg-orange-50 p-4 rounded-lg">
                <p class="text-orange-800 text-xs font-bold uppercase">Sisa Piutang</p>
                <h3 class="text-xl font-bold text-orange-600">Rp
                    {{ number_format($summary->sisa_piutang ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-500 text-xs font-bold uppercase">Total Tagihan Awal</p>
                <h3 class="text-xl font-bold text-gray-700">Rp
                    {{ number_format($summary->total_awal ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="w-full md:w-auto">
            <select wire:model.live="filterCabang" class="w-full text-sm border-gray-200 rounded-lg">
                <option value="">Semua Cabang</option>
                @foreach($optCabang as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
            </select>
        </div>
        <div class="w-full md:w-auto relative">
            <input wire:model.live.debounce.500ms="search" type="text" placeholder="Invoice..."
                class="w-full pl-10 text-sm border-gray-200 rounded-lg">
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[75vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="text-[10px] text-gray-600 uppercase bg-gray-50 sticky top-0 z-20 shadow-sm font-bold">
                    <tr>
                        <th class="px-2 py-3 border-b border-r min-w-[80px]">Cabang</th>
                        <th class="px-2 py-3 border-b border-r min-w-[120px]">No Penjualan</th>
                        <th class="px-2 py-3 border-b border-r">Pelanggan Code</th>
                        <th class="px-2 py-3 border-b border-r min-w-[150px]">Pelanggan Name</th>
                        <th class="px-2 py-3 border-b border-r">Sales</th>
                        <th class="px-2 py-3 border-b border-r">Info</th>
                        <th class="px-2 py-3 border-b border-r text-right">Total Nilai</th>
                        <th class="px-2 py-3 border-b border-r text-right bg-orange-100 text-orange-900">Nilai (Sisa)
                        </th>
                        <th class="px-2 py-3 border-b border-r">Penjualan</th>
                        <th class="px-2 py-3 border-b border-r">Antar</th>
                        <th class="px-2 py-3 border-b border-r">Status Antar</th>
                        <th class="px-2 py-3 border-b border-r">Jatuh Tempo</th>
                        <th class="px-2 py-3 border-b border-r text-right">Current</th>
                        <th class="px-2 py-3 border-b border-r text-right">(<=15)< /th>
                        <th class="px-2 py-3 border-b border-r text-right">(16-30)</th>
                        <th class="px-2 py-3 border-b border-r text-right">(>30)</th>
                        <th class="px-2 py-3 border-b border-r">Status</th>
                        <th class="px-2 py-3 border-b border-r min-w-[200px]">Alamat</th>
                        <th class="px-2 py-3 border-b border-r">Phone</th>
                        <th class="px-2 py-3 border-b border-r text-center">Umur Piutang</th>
                        <th class="px-2 py-3 border-b border-r">Unique</th>
                        <th class="px-2 py-3 border-b border-r text-right">
                            <14 Days</th>
                        <th class="px-2 py-3 border-b border-r text-right">> 14<30 Days</th>
                        <th class="px-2 py-3 border-b border-r text-right">UP 30 Days</th>
                        <th class="px-2 py-3 border-b border-r">Range Piutang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($ar as $item)
                    <tr class="hover:bg-orange-50 transition-colors">
                        <td class="px-2 py-1.5 border-r text-orange-600 font-medium">{{ $item->cabang }}</td>
                        <td class="px-2 py-1.5 border-r font-mono">{{ $item->no_penjualan }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->pelanggan_code }}</td>
                        <td class="px-2 py-1.5 border-r font-medium">{{ $item->pelanggan_name }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->sales_name }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->info }}</td>
                        <td class="px-2 py-1.5 border-r text-right text-gray-500">
                            {{ number_format((float)$item->total_nilai, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right font-bold text-orange-700 bg-orange-50/50">
                            {{ number_format((float)$item->nilai, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r">
                            {{ $item->tgl_penjualan ? date('d-m-Y', strtotime($item->tgl_penjualan)) : '-' }}</td>
                        <td class="px-2 py-1.5 border-r">
                            {{ $item->tgl_antar ? date('d-m-Y', strtotime($item->tgl_antar)) : '-' }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->status_antar }}</td>
                        <td
                            class="px-2 py-1.5 border-r {{ $item->jatuh_tempo && now()->gt($item->jatuh_tempo) ? 'text-red-500 font-bold' : '' }}">
                            {{ $item->jatuh_tempo ? date('d-m-Y', strtotime($item->jatuh_tempo)) : '-' }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->current, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->le_15_days, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->bt_16_30_days, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right text-red-600">
                            {{ number_format((float)$item->gt_30_days, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->status }}</td>
                        <td class="px-2 py-1.5 border-r truncate max-w-[200px]" title="{{ $item->alamat }}">
                            {{ $item->alamat }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->phone }}</td>
                        <td class="px-2 py-1.5 border-r text-center font-bold">{{ $item->umur_piutang }}</td>
                        <td class="px-2 py-1.5 border-r text-[9px]">{{ $item->unique_id }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->lt_14_days, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->bt_14_30_days, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r text-right">
                            {{ number_format((float)$item->up_30_days, 0, ',', '.') }}</td>
                        <td class="px-2 py-1.5 border-r">{{ $item->range_piutang }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="25" class="px-6 py-12 text-center text-gray-400">Data Piutang Kosong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $ar->links() }}</div>
    </div>
</div>