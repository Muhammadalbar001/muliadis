<div class="space-y-4 font-jakarta">

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-blue-100">
        <div class="flex flex-col gap-4">

            <div class="flex flex-col md:flex-row gap-3">
                <div class="w-full md:flex-1 relative">
                    <i class="fas fa-search absolute left-3 top-2.5 text-blue-400"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-9 w-full border-blue-100 rounded-xl text-xs focus:ring-blue-500"
                        placeholder="No Bukti, Toko, Invoice...">
                </div>
                <div class="flex items-center gap-1">
                    <input type="date" wire:model.live="startDate" class="border-blue-100 rounded-xl text-xs w-32">
                    <span class="text-slate-300">-</span>
                    <input type="date" wire:model.live="endDate" class="border-blue-100 rounded-xl text-xs w-32">
                </div>
                <button wire:click="resetFilter"
                    class="px-3 py-2 bg-slate-100 rounded-xl hover:bg-slate-200 text-xs font-bold text-slate-600"><i
                        class="fas fa-undo mr-1"></i> Reset</button>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-blue-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                        <span
                            class="truncate">{{ count($filterCabang) ? count($filterCabang).' Cabang' : 'Semua Cabang' }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-blue-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                        @foreach($optCabang as $o)
                        <label class="flex items-center px-2 py-1.5 hover:bg-blue-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $o }}" wire:model.live="filterCabang"
                                class="rounded border-blue-300 text-blue-600 mr-2"> <span
                                class="text-[10px]">{{ $o }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-blue-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                        <span
                            class="truncate">{{ count($filterPenagih) ? count($filterPenagih).' Penagih' : 'Semua Penagih' }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-blue-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                        @foreach($optPenagih as $o)
                        <label class="flex items-center px-2 py-1.5 hover:bg-blue-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $o }}" wire:model.live="filterPenagih"
                                class="rounded border-blue-300 text-blue-600 mr-2"> <span
                                class="text-[10px]">{{ $o }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-blue-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                        <span
                            class="truncate">{{ count($filterSales) ? count($filterSales).' Sales' : 'Semua Sales' }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-blue-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                        @foreach($optSales as $o)
                        <label class="flex items-center px-2 py-1.5 hover:bg-blue-50 rounded cursor-pointer">
                            <input type="checkbox" value="{{ $o }}" wire:model.live="filterSales"
                                class="rounded border-blue-300 text-blue-600 mr-2"> <span
                                class="text-[10px]">{{ $o }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-blue-100 flex flex-col h-[75vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                <thead class="bg-blue-50 text-blue-800 font-bold border-b border-blue-200 uppercase sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-2 border-r border-blue-200">Cabang</th>
                        <th class="px-3 py-2 border-r border-blue-200">Receive No</th>
                        <th class="px-3 py-2 border-r border-blue-200">Status</th>
                        <th class="px-3 py-2 border-r border-blue-200">Tgl Bayar</th>
                        <th class="px-3 py-2 border-r border-blue-200 bg-blue-100 text-blue-900">Penagih</th>
                        <th class="px-3 py-2 border-r border-blue-200">No Invoice</th>
                        <th class="px-3 py-2 border-r border-blue-200">Kode Cust</th>
                        <th class="px-3 py-2 border-r border-blue-200">Nama Toko</th>
                        <th class="px-3 py-2 border-r border-blue-200">Salesman</th>
                        <th class="px-3 py-2 border-r border-blue-200 text-right bg-emerald-50 text-emerald-800">Jumlah
                            Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-50">
                    @forelse($data as $item)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-3 py-1.5 border-r border-blue-50 text-blue-700 font-bold">{{ $item->cabang }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50 font-mono">{{ $item->receive_no }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ $item->status }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ date('d/m/Y', strtotime($item->tanggal)) }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-blue-50 font-medium bg-blue-50/20">{{ $item->penagih }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-blue-50 font-mono">{{ $item->invoice_no }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ $item->code_customer }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50 truncate max-w-[200px]">{{ $item->outlet_name }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ $item->sales_name }}</td>
                        <td
                            class="px-3 py-1.5 border-r border-blue-50 text-right font-bold text-emerald-600 bg-emerald-50/30">
                            {{ number_format((float)$item->receive_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-10 text-center text-slate-400">Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-2 border-t bg-blue-50">{{ $data->links() }}</div>
    </div>
</div>