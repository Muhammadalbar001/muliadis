<div class="space-y-4 font-jakarta">

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-rose-100">
        <div class="flex flex-col md:flex-row gap-3 items-end">
            <div class="w-full md:flex-1 relative">
                <i class="fas fa-search absolute left-3 top-2.5 text-rose-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-9 w-full border-rose-100 rounded-xl text-xs focus:ring-rose-500"
                    placeholder="No Retur, Toko...">
            </div>
            <div class="flex items-center gap-1">
                <input type="date" wire:model.live="startDate" class="border-rose-100 rounded-xl text-xs w-28">
                <span class="text-slate-300">-</span>
                <input type="date" wire:model.live="endDate" class="border-rose-100 rounded-xl text-xs w-28">
            </div>
            <div class="w-40 relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full bg-white border border-rose-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                    <span
                        class="truncate">{{ count($filterCabang) ? count($filterCabang).' Dipilih' : 'Semua Cabang' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div x-show="open"
                    class="absolute z-50 mt-1 w-full bg-white border border-rose-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                    @foreach($optCabang as $c)
                    <label class="flex items-center px-2 py-1.5 hover:bg-rose-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                            class="rounded border-rose-300 text-rose-600 h-3 w-3 mr-2"> <span
                            class="text-[10px]">{{ $c }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <button wire:click="resetFilter" class="px-3 py-2 bg-slate-100 rounded-xl"><i
                    class="fas fa-undo text-slate-500"></i></button>
            <button class="px-3 py-2 bg-rose-600 text-white rounded-xl shadow-rose-200"><i
                    class="fas fa-file-export"></i></button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-rose-100 flex flex-col h-[75vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                <thead class="bg-rose-50 text-rose-800 font-bold border-b border-rose-200 uppercase sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-2 border-r border-rose-200">Cabang</th>
                        <th class="px-3 py-2 border-r border-rose-200">No Retur</th>
                        <th class="px-3 py-2 border-r border-rose-200">Tanggal</th>
                        <th class="px-3 py-2 border-r border-rose-200">Pelanggan</th>
                        <th class="px-3 py-2 border-r border-rose-200">Salesman</th>

                        <th class="px-3 py-2 border-r border-rose-200 bg-white">Kode Item</th>
                        <th class="px-3 py-2 border-r border-rose-200 bg-white">Nama Item</th>
                        <th class="px-3 py-2 border-r border-rose-200 bg-white text-center">Qty</th>

                        <th class="px-3 py-2 border-r border-rose-200 text-right">Nilai</th>
                        <th class="px-3 py-2 border-r border-rose-200 text-right">Diskon</th>
                        <th class="px-3 py-2 border-r border-rose-200 text-right bg-rose-100">Total Net</th>
                        <th class="px-3 py-2 border-r border-rose-200">Alasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rose-50">
                    @forelse($data as $item)
                    <tr class="hover:bg-rose-50/50 transition-colors">
                        <td class="px-3 py-1.5 border-r border-rose-50 text-rose-700 font-bold">{{ $item->cabang }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 font-mono">{{ $item->no_retur }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50">{{ date('d/m/Y', strtotime($item->tgl_retur)) }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-rose-50 truncate max-w-[150px]">
                            {{ $item->nama_pelanggan }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50">{{ $item->sales_name }}</td>

                        <td class="px-3 py-1.5 border-r border-rose-50 font-mono">{{ $item->kode_item }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 font-medium truncate max-w-[200px]">
                            {{ $item->nama_item }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-center font-bold">{{ $item->qty }}</td>

                        <td class="px-3 py-1.5 border-r border-rose-50 text-right">
                            {{ number_format($item->nilai, 0, ',', '.') }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-right">
                            {{ number_format($item->total_diskon, 0, ',', '.') }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-right font-bold text-red-600 bg-rose-50/30">
                            {{ number_format($item->total_grand, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-[9px] text-slate-500">Bad Stock / Expired
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="px-6 py-10 text-center text-slate-400">Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-2 border-t bg-rose-50">{{ $data->links() }}</div>
    </div>
</div>