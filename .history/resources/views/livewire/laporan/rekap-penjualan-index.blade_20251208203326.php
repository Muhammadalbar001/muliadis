<div class="space-y-4 font-jakarta">

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-emerald-100">
        <div class="flex flex-col md:flex-row gap-3 items-end">

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Cari Data</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-2.5 text-emerald-400"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-9 w-full border-emerald-100 rounded-xl text-xs focus:ring-emerald-500"
                        placeholder="Faktur, Pelanggan, Barang...">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Periode</label>
                <div class="flex items-center gap-1">
                    <input type="date" wire:model.live="startDate" class="border-emerald-100 rounded-xl text-xs w-28">
                    <span class="text-slate-300">-</span>
                    <input type="date" wire:model.live="endDate" class="border-emerald-100 rounded-xl text-xs w-28">
                </div>
            </div>

            <div class="w-40 relative" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Cabang</label>
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full bg-white border border-emerald-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                    <span class="truncate">{{ count($filterCabang) ? count($filterCabang).' Dipilih' : 'Semua' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div x-show="open"
                    class="absolute z-50 mt-1 w-full bg-white border border-emerald-100 rounded-xl shadow-xl max-h-48 overflow-y-auto p-1">
                    @foreach($optCabang as $c)
                    <label class="flex items-center px-2 py-1.5 hover:bg-emerald-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                            class="rounded border-emerald-300 text-emerald-600 h-3 w-3 mr-2">
                        <span class="text-[10px]">{{ $c }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="w-40 relative" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Salesman</label>
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full bg-white border border-emerald-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                    <span class="truncate">{{ count($filterSales) ? count($filterSales).' Sales' : 'Semua' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div x-show="open"
                    class="absolute z-50 mt-1 w-56 bg-white border border-emerald-100 rounded-xl shadow-xl max-h-48 overflow-y-auto p-1">
                    @foreach($optSales as $s)
                    <label class="flex items-center px-2 py-1.5 hover:bg-emerald-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $s }}" wire:model.live="filterSales"
                            class="rounded border-emerald-300 text-emerald-600 h-3 w-3 mr-2">
                        <span class="text-[10px]">{{ Str::limit($s, 20) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <button wire:click="resetFilter"
                class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl transition">
                <i class="fas fa-undo"></i>
            </button>

            <button
                class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm shadow-emerald-200 transition">
                <i class="fas fa-file-export"></i>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-emerald-100 flex flex-col h-[75vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">

                <thead
                    class="bg-emerald-50 text-emerald-800 font-bold border-b border-emerald-200 uppercase sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-2 border-r border-emerald-200">Cabang</th>
                        <th class="px-3 py-2 border-r border-emerald-200">No Faktur</th>
                        <th class="px-3 py-2 border-r border-emerald-200">Tanggal</th>
                        <th class="px-3 py-2 border-r border-emerald-200">Pelanggan</th>
                        <th class="px-3 py-2 border-r border-emerald-200">Salesman</th>

                        <th class="px-3 py-2 border-r border-emerald-200 bg-white">Kode Item</th>
                        <th class="px-3 py-2 border-r border-emerald-200 bg-white">Nama Barang</th>
                        <th class="px-3 py-2 border-r border-emerald-200 bg-white text-center">Qty</th>
                        <th class="px-3 py-2 border-r border-emerald-200 bg-white text-center">Satuan</th>

                        <th class="px-3 py-2 border-r border-emerald-200 text-right">Harga</th>
                        <th class="px-3 py-2 border-r border-emerald-200 text-right">Diskon</th>
                        <th class="px-3 py-2 border-r border-emerald-200 text-right">DPP</th>
                        <th class="px-3 py-2 border-r border-emerald-200 text-right">PPN</th>
                        <th class="px-3 py-2 border-r border-emerald-200 text-right bg-emerald-100">Total Grand</th>

                        <th class="px-3 py-2 border-r border-emerald-200 text-right bg-yellow-50">HPP (Modal)</th>
                        <th class="px-3 py-2 border-r border-emerald-200 text-right bg-yellow-50 text-orange-600">Margin
                            (Rp)</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-emerald-50">
                    @forelse($data as $item)
                    <tr class="hover:bg-emerald-50/50 transition-colors">
                        <td class="px-3 py-1.5 border-r border-emerald-50 text-emerald-700 font-bold">
                            {{ $item->cabang }}</td>
                        <td class="px-3 py-1.5 border-r border-emerald-50 font-mono">{{ $item->trans_no }}</td>
                        <td class="px-3 py-1.5 border-r border-emerald-50">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                        <td class="px-3 py-1.5 border-r border-emerald-50 truncate max-w-[150px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-3 py-1.5 border-r border-emerald-50">{{ $item->sales_name }}</td>

                        <td class="px-3 py-1.5 border-r border-emerald-50 font-mono text-slate-500">
                            {{ $item->kode_item }}</td>
                        <td class="px-3 py-1.5 border-r border-emerald-50 font-medium text-slate-700 truncate max-w-[200px]"
                            title="{{ $item->nama_item }}">{{ $item->nama_item }}</td>
                        <td class="px-3 py-1.5 border-r border-emerald-50 text-center font-bold">{{ $item->qty }}</td>
                        <td class="px-3 py-1.5 border-r border-emerald-50 text-center text-[9px]">{{ $item->satuan }}
                        </td>

                        <td class="px-3 py-1.5 border-r border-emerald-50 text-right">
                            {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td class="px-3 py-1.5 border-r border-emerald-50 text-right">
                            {{ number_format($item->diskon, 0, ',', '.') }}</td>
                        <td class="px-3 py-1.5 border-r border-emerald-50 text-right">
                            {{ number_format($item->dpp, 0, ',', '.') }}</td>
                        <td class="px-3 py-1.5 border-r border-emerald-50 text-right">
                            {{ number_format($item->ppn, 0, ',', '.') }}</td>
                        <td
                            class="px-3 py-1.5 border-r border-emerald-50 text-right font-bold text-emerald-700 bg-emerald-50/30">
                            {{ number_format($item->total_grand, 0, ',', '.') }}
                        </td>

                        <td class="px-3 py-1.5 border-r border-emerald-50 text-right bg-yellow-50/30">
                            {{ number_format($item->modal, 0, ',', '.') }}</td>
                        <td
                            class="px-3 py-1.5 border-r border-emerald-50 text-right font-bold text-orange-600 bg-yellow-50/30">
                            {{ number_format($item->margin, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="16" class="px-6 py-12 text-center text-slate-400">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-2 border-t bg-emerald-50">
            {{ $data->links() }}
        </div>
    </div>
</div>