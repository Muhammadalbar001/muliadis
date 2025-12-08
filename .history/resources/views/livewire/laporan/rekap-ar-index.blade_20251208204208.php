<div class="space-y-4 font-jakarta">

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-orange-100">
        <div class="flex flex-col md:flex-row gap-3 items-end">
            <div class="w-full md:flex-1 relative">
                <i class="fas fa-search absolute left-3 top-2.5 text-orange-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-9 w-full border-orange-100 rounded-xl text-xs focus:ring-orange-500"
                    placeholder="Faktur, Pelanggan...">
            </div>
            <button wire:click="resetFilter" class="px-3 py-2 bg-slate-100 rounded-xl"><i
                    class="fas fa-undo text-slate-500"></i></button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-orange-100 flex flex-col h-[75vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                <thead
                    class="bg-orange-50 text-orange-800 font-bold border-b border-orange-200 uppercase sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-2 border-r border-orange-200 sticky left-0 bg-orange-50 z-30">Cabang</th>
                        <th class="px-3 py-2 border-r border-orange-200">No Penjualan</th>
                        <th class="px-3 py-2 border-r border-orange-200">Kode Pelanggan</th>
                        <th class="px-3 py-2 border-r border-orange-200 sticky left-[60px] z-20 bg-orange-50">Nama
                            Pelanggan</th>
                        <th class="px-3 py-2 border-r border-orange-200">Salesman</th>

                        <th class="px-3 py-2 border-r border-orange-200 text-right">Total Nilai</th>
                        <th class="px-3 py-2 border-r border-orange-200 text-right bg-orange-100">Sisa Tagihan</th>

                        <th class="px-3 py-2 border-r border-orange-200">Tgl Faktur</th>
                        <th class="px-3 py-2 border-r border-orange-200">Tgl Antar</th>
                        <th class="px-3 py-2 border-r border-orange-200">Status Antar</th>
                        <th class="px-3 py-2 border-r border-orange-200">Jatuh Tempo</th>

                        <th class="px-3 py-2 border-r border-orange-200 text-right">Current</th>
                        <th class="px-3 py-2 border-r border-orange-200 text-right">
                            <= 15 Hari</th>
                        <th class="px-3 py-2 border-r border-orange-200 text-right">16-30 Hari</th>
                        <th class="px-3 py-2 border-r border-orange-200 text-right">> 30 Hari</th>

                        <th class="px-3 py-2 border-r border-orange-200 text-center">Umur Piutang</th>
                        <th class="px-3 py-2 border-r border-orange-200">Alamat</th>
                        <th class="px-3 py-2 border-r border-orange-200">No Telp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-orange-50">
                    @forelse($data as $item)
                    <tr class="hover:bg-orange-50/50 transition-colors">
                        <td
                            class="px-3 py-1.5 border-r border-orange-50 text-orange-700 font-bold sticky left-0 bg-white z-20">
                            {{ $item->cabang }}</td>
                        <td class="px-3 py-1.5 border-r border-orange-50 font-mono">{{ $item->no_penjualan }}</td>
                        <td class="px-3 py-1.5 border-r border-orange-50 font-mono">{{ $item->pelanggan_code }}</td>
                        <td
                            class="px-3 py-1.5 border-r border-orange-50 font-medium truncate max-w-[200px] sticky left-[60px] bg-white z-10">
                            {{ $item->pelanggan_name }}</td>
                        <td class="px-3 py-1.5 border-r border-orange-50">{{ $item->sales_name }}</td>

                        <td class="px-3 py-1.5 border-r border-orange-50 text-right">
                            {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                        <td
                            class="px-3 py-1.5 border-r border-orange-50 text-right font-bold text-slate-800 bg-orange-50/30">
                            {{ number_format($item->nilai, 0, ',', '.') }}
                        </td>

                        <td class="px-3 py-1.5 border-r border-orange-50">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                        <td class="px-3 py-1.5 border-r border-orange-50">{{ $item->tgl_antar }}</td>
                        <td class="px-3 py-1.5 border-r border-orange-50">{{ $item->status_antar }}</td>
                        <td class="px-3 py-1.5 border-r border-orange-50 text-red-600 font-bold">
                            {{ date('d/m/Y', strtotime($item->jatuh_tempo)) }}</td>

                        <td class="px-3 py-1.5 border-r border-orange-50 text-right">
                            {{ number_format($item->current, 0) }}</td>
                        <td class="px-3 py-1.5 border-r border-orange-50 text-right">
                            {{ number_format($item->le_15_days, 0) }}</td>
                        <td class="px-3 py-1.5 border-r border-orange-50 text-right">
                            {{ number_format($item->bt_16_30_days, 0) }}</td>
                        <td class="px-3 py-1.5 border-r border-orange-50 text-right text-red-600 font-bold">
                            {{ number_format($item->gt_30_days, 0) }}</td>

                        <td class="px-3 py-1.5 border-r border-orange-50 text-center font-bold">
                            {{ $item->umur_piutang }} Hari</td>
                        <td class="px-3 py-1.5 border-r border-orange-50 truncate max-w-[200px]">{{ $item->alamat }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-orange-50">{{ $item->phone }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="18" class="px-6 py-12 text-center text-slate-400">Data Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-2 border-t bg-orange-50">{{ $data->links() }}</div>
    </div>
</div>