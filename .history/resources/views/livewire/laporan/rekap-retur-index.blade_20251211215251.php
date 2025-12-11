<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-rose-50/90 p-4 rounded-b-2xl shadow-sm border-b border-rose-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-rose-100 rounded-lg text-rose-600 shadow-sm">
                    <i class="fas fa-undo-alt text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-rose-900 tracking-tight">Rekap Retur</h1>
                    <p class="text-xs text-rose-600 font-medium mt-0.5">Detail retur barang (Excel Style).</p>
                </div>
                <div
                    class="hidden md:flex px-3 py-1 bg-white text-rose-600 rounded-lg text-[10px] font-bold border border-rose-100 items-center gap-2 shadow-sm">
                    <i class="fas fa-list-ol"></i> {{ $returs->total() }} Baris
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-rose-500/50 text-xs"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-8 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-rose-500 py-2 shadow-sm placeholder-slate-400 transition-all"
                        placeholder="No Retur / Pelanggan...">
                </div>

                <div
                    class="flex items-center gap-1 bg-white border border-white rounded-lg px-2 py-1 shadow-sm h-[34px]">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                    <span class="text-slate-300 text-[10px]">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                </div>

                <div class="w-full sm:w-32">
                    <select wire:model.live="filterCabang"
                        class="w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-rose-500 py-2 shadow-sm cursor-pointer bg-white hover:bg-rose-50 transition-colors">
                        <option value="">Semua Cabang</option>
                        @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
                </div>

                <div class="hidden sm:block h-6 w-px bg-rose-200 mx-1"></div>

                <button wire:click="export" wire:loading.attr="disabled"
                    class="px-3 py-2 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700 shadow-md shadow-rose-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <span wire:loading.remove wire:target="export"><i class="fas fa-file-excel"></i> Export</span>
                    <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin"></i> Proses...</span>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[85vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap w-full">
                <thead
                    class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200 sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-0 z-30 shadow-sm">Cabang
                        </th>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-[60px] z-30 shadow-sm">No
                            Retur</th>
                        <th class="px-3 py-3 border-r border-slate-200">Tanggal</th>
                        <th class="px-3 py-3 border-r border-slate-200">No Inv Asal</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[150px]">Pelanggan</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[200px]">Barang</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right bg-rose-50 text-rose-700">Qty</th>
                        <th class="px-3 py-3 border-r border-slate-200">Satuan</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Nilai</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right text-rose-600">Diskon</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">Net</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right">PPN</th>
                        <th
                            class="px-3 py-3 border-r border-slate-200 text-right font-bold bg-yellow-50 text-yellow-700">
                            Total Grand</th>
                        <th class="px-3 py-3 border-r border-slate-200">Salesman</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($returs as $item)
                    <tr class="hover:bg-rose-50/20 transition-colors odd:bg-white even:bg-slate-50/30">
                        <td class="px-3 py-2 border-r border-slate-100 font-bold sticky left-0 bg-inherit z-10">
                            {{ $item->cabang }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 font-mono text-rose-600 sticky left-[60px] bg-inherit z-10">
                            {{ $item->no_retur }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ date('d/m/Y', strtotime($item->tgl_retur)) }}
                        </td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->no_inv }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[200px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 truncate max-w-[250px]">{{ $item->nama_item }}
                        </td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 text-right font-bold bg-rose-50/30 text-rose-700">
                            {{ number_format($item->qty, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->satuan_retur }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->nilai, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right text-rose-500">
                            {{ number_format($item->total_diskon, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->nilai_retur_net, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right">
                            {{ number_format($item->ppn_head, 0, ',', '.') }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 text-right font-bold bg-yellow-50/50 text-yellow-700">
                            {{ number_format($item->total_grand, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->sales_name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="px-6 py-12 text-center text-slate-400">Data tidak ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $returs->links() }}</div>
    </div>
</div>