<div class="space-y-4 font-jakarta">

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-blue-100">
        <div class="flex flex-col md:flex-row gap-3 items-end">
            <div class="w-full md:flex-1 relative">
                <i class="fas fa-search absolute left-3 top-2.5 text-blue-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-9 w-full border-blue-100 rounded-xl text-xs focus:ring-blue-500"
                    placeholder="No Bukti, Toko...">
            </div>
            <div class="flex items-center gap-1">
                <input type="date" wire:model.live="startDate" class="border-blue-100 rounded-xl text-xs w-28">
                <span class="text-slate-300">-</span>
                <input type="date" wire:model.live="endDate" class="border-blue-100 rounded-xl text-xs w-28">
            </div>
            <div class="w-40 relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full bg-white border border-blue-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                    <span
                        class="truncate">{{ count($filterCabang) ? count($filterCabang).' Dipilih' : 'Semua Cabang' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div x-show="open"
                    class="absolute z-50 mt-1 w-full bg-white border border-blue-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                    @foreach($optCabang as $c)
                    <label class="flex items-center px-2 py-1.5 hover:bg-blue-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                            class="rounded border-blue-300 text-blue-600 h-3 w-3 mr-2"> <span
                            class="text-[10px]">{{ $c }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <button wire:click="resetFilter" class="px-3 py-2 bg-slate-100 rounded-xl"><i
                    class="fas fa-undo text-slate-500"></i></button>
            <button class="px-3 py-2 bg-blue-600 text-white rounded-xl shadow-blue-200"><i
                    class="fas fa-file-export"></i></button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-blue-100 flex flex-col h-[75vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                <thead class="bg-blue-50 text-blue-800 font-bold border-b border-blue-200 uppercase sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-2 border-r border-blue-200">Cabang</th>
                        <th class="px-3 py-2 border-r border-blue-200">No Bukti</th>
                        <th class="px-3 py-2 border-r border-blue-200">Tgl Bayar</th>
                        <th class="px-3 py-2 border-r border-blue-200">Penagih</th>
                        <th class="px-3 py-2 border-r border-blue-200">No Invoice</th>
                        <th class="px-3 py-2 border-r border-blue-200">Kode Cust</th>
                        <th class="px-3 py-2 border-r border-blue-200">Nama Toko</th>
                        <th class="px-3 py-2 border-r border-blue-200">Salesman</th>
                        <th class="px-3 py-2 border-r border-blue-200 text-right bg-blue-100">Jumlah Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-50">
                    @forelse($data as $item)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-3 py-1.5 border-r border-blue-50 text-blue-700 font-bold">{{ $item->cabang }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50 font-mono">{{ $item->receive_no }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ date('d/m/Y', strtotime($item->tanggal)) }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ $item->penagih }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ $item->invoice_no }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ $item->code_customer }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50 font-medium truncate max-w-[150px]">
                            {{ $item->outlet_name }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ $item->sales_name }}</td>
                        <td
                            class="px-3 py-1.5 border-r border-blue-50 text-right font-bold text-emerald-600 bg-blue-50/30">
                            {{ number_format((float)$item->receive_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center text-slate-400">Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-2 border-t bg-blue-50">{{ $data->links() }}</div>
    </div>
</div>