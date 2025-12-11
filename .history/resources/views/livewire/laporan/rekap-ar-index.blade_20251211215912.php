<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-orange-50/90 p-4 rounded-b-2xl shadow-sm border-b border-orange-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-orange-100 rounded-lg text-orange-600 shadow-sm"><i
                        class="fas fa-file-invoice-dollar text-xl"></i></div>
                <div>
                    <h1 class="text-xl font-extrabold text-orange-900 tracking-tight">Rekap Piutang (AR)</h1>
                    <p class="text-xs text-orange-600 font-medium mt-0.5">Detail Aging Schedule ({{ $ars->total() }}
                        Baris)</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">
                <div class="relative w-full sm:w-48">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-3 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-orange-500 py-2 shadow-sm placeholder-slate-400"
                        placeholder="No Inv / Pelanggan...">
                </div>
                <div class="w-full sm:w-32">
                    <select wire:model.live="filterCabang"
                        class="w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-orange-500 py-2 shadow-sm cursor-pointer bg-white hover:bg-orange-50">
                        <option value="">Semua Cabang</option>
                        @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-32">
                    <select wire:model.live="filterUmur"
                        class="w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-orange-500 py-2 shadow-sm cursor-pointer bg-white hover:bg-orange-50">
                        <option value="">Semua Status</option>
                        <option value="lancar">Lancar (<= 30)</option>
                        <option value="macet">Macet (> 30)</option>
                    </select>
                </div>
                <div class="hidden sm:block h-6 w-px bg-orange-200 mx-1"></div>
                <button wire:click="export" wire:loading.attr="disabled"
                    class="px-3 py-2 bg-orange-600 text-white rounded-lg text-xs font-bold hover:bg-orange-700 shadow-md shadow-orange-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <span wire:loading.remove wire:target="export"><i class="fas fa-file-excel"></i> Export</span>
                    <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin"></i> Proses...</span>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[85vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                <thead
                    class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200 sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-0 z-30 shadow-sm">Cabang
                        </th>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-[60px] z-30 shadow-sm">No
                            Invoice</th>
                        <th class="px-3 py-3 border-r border-slate-200">Kode Pelanggan</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[150px]">Nama Pelanggan</th>
                        <th class="px-3 py-3 border-r border-slate-200">Salesman</th>
                        <th class="px-3 py-3 border-r border-slate-200">Info</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Total Nilai</th>
                        <th
                            class="px-3 py-3 border-r border-slate-200 text-right font-bold bg-orange-50 text-orange-700">
                            Nilai (Sisa)</th>
                        <th class="px-3 py-3 border-r border-slate-200">Tgl Penjualan</th>
                        <th class="px-3 py-3 border-r border-slate-200">Tgl Antar</th>
                        <th class="px-3 py-3 border-r border-slate-200">Status Antar</th>
                        <th class="px-3 py-3 border-r border-slate-200">Jatuh Tempo</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Current</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">LE 15 Days</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">16-30 Days</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right text-red-600 font-bold">> 30 Days</th>
                        <th class="px-3 py-3 border-r border-slate-200">Status</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[200px]">Alamat</th>
                        <th class="px-3 py-3 border-r border-slate-200">Phone</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-center font-bold">Umur</th>
                        <th class="px-3 py-3 border-r border-slate-200">Unique ID</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">LT 14 Days</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">14-30 Days</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">UP 30 Days</th>
                        <th class="px-3 py-3 border-r border-slate-200">Range</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($ars as $item)
                    <tr class="hover:bg-orange-50/20 transition-colors odd:bg-white even:bg-slate-50/30">
                        <td class="px-3 py-2 border-r border-slate-100 font-bold sticky left-0 bg-inherit z-10">
                            {{ $item->cabang }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 font-mono text-orange-600 sticky left-[60px] bg-inherit z-10">
                            {{ $item->no_penjualan }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->pelanggan_code }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[200px]"
                            title="{{ $item->pelanggan_name }}">{{ $item->pelanggan_name }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->sales_name }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->info }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right font-bold text-orange-700">
                            {{ number_format($item->nilai, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->tgl_penjualan }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->tgl_antar }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->status_antar }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->jatuh_tempo }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->current, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->le_15_days, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->bt_16_30_days, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right text-red-600 font-bold">
                            {{ number_format($item->gt_30_days, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->status }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 truncate max-w-[200px]">{{ $item->alamat }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->phone }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-center font-bold">{{ $item->umur_piutang }}
                        </td>
                        <td class="px-3 py-2 border-r border-slate-100 text-[9px] font-mono">{{ $item->unique_id }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->lt_14_days, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->bt_14_30_days, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->up_30_days, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->range_piutang }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="25" class="px-6 py-12 text-center text-slate-400">Data tidak ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $ars->links() }}</div>
    </div>
</div>