<div class="space-y-4 font-jakarta">

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-rose-100">
        <div class="flex flex-col gap-4">

            <div class="flex flex-col md:flex-row gap-3 items-end">
                <div class="w-full md:flex-1 relative">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pencarian</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-2.5 text-rose-400"></i>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="pl-9 w-full border-rose-100 rounded-xl text-xs focus:ring-rose-500"
                            placeholder="No Retur, Pelanggan, Barang...">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Periode Retur</label>
                    <div class="flex items-center gap-1">
                        <input type="date" wire:model.live="startDate" class="border-rose-100 rounded-xl text-xs w-28">
                        <span class="text-slate-300">-</span>
                        <input type="date" wire:model.live="endDate" class="border-rose-100 rounded-xl text-xs w-28">
                    </div>
                </div>
                <button wire:click="resetFilter"
                    class="px-3 py-2 bg-slate-100 rounded-xl hover:bg-slate-200 text-slate-600 font-bold text-xs flex items-center gap-1">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-2">

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-rose-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                        <span
                            class="truncate">{{ count($filterCabang) ? count($filterCabang).' Cabang' : 'Semua Cabang' }}</span>
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-rose-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                        @foreach($optCabang as $o)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-rose-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $o }}" wire:model.live="filterCabang"
                                class="rounded border-rose-300 text-rose-600 mr-2 h-3.5 w-3.5"> <span
                                class="text-[10px]">{{ $o }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-rose-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                        <span
                            class="truncate">{{ count($filterSupplier) ? count($filterSupplier).' Supplier' : 'Semua Supplier' }}</span>
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-rose-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                        @foreach($optSupplier as $o)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-rose-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $o }}" wire:model.live="filterSupplier"
                                class="rounded border-rose-300 text-rose-600 mr-2 h-3.5 w-3.5"> <span
                                class="text-[10px]">{{ Str::limit($o, 15) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-rose-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                        <span
                            class="truncate">{{ count($filterDivisi) ? count($filterDivisi).' Divisi' : 'Semua Divisi' }}</span>
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-rose-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                        @foreach($optDivisi as $o)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-rose-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $o }}" wire:model.live="filterDivisi"
                                class="rounded border-rose-300 text-rose-600 mr-2 h-3.5 w-3.5"> <span
                                class="text-[10px]">{{ $o }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-rose-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                        <span
                            class="truncate">{{ count($filterSales) ? count($filterSales).' Sales' : 'Semua Sales' }}</span>
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-rose-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                        @foreach($optSales as $o)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-rose-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $o }}" wire:model.live="filterSales"
                                class="rounded border-rose-300 text-rose-600 mr-2 h-3.5 w-3.5"> <span
                                class="text-[10px]">{{ $o }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full bg-white border border-rose-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                        <span
                            class="truncate">{{ count($filterStatus) ? count($filterStatus).' Status' : 'Semua Status' }}</span>
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-rose-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                        @foreach($optStatus as $o)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-rose-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $o }}" wire:model.live="filterStatus"
                                class="rounded border-rose-300 text-rose-600 mr-2 h-3.5 w-3.5"> <span
                                class="text-[10px]">{{ $o }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-rose-100 flex flex-col h-[75vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                <thead
                    class="bg-rose-50 text-rose-900 font-bold border-b border-rose-200 uppercase sticky top-0 z-20 shadow-sm">
                    <tr>
                        <th class="px-3 py-2 border-r border-rose-200 sticky left-0 bg-rose-50 z-30">Cabang</th>
                        <th class="px-3 py-2 border-r border-rose-200">No Retur</th>
                        <th class="px-3 py-2 border-r border-rose-200">Status</th>
                        <th class="px-3 py-2 border-r border-rose-200">Tgl Retur</th>
                        <th class="px-3 py-2 border-r border-rose-200">No Invoice</th>
                        <th class="px-3 py-2 border-r border-rose-200">Kode Pelanggan</th>
                        <th class="px-3 py-2 border-r border-rose-200 bg-white sticky left-[60px] z-20">Nama Pelanggan
                        </th>

                        <th class="px-3 py-2 border-r border-rose-200 bg-white">Kode Item</th>
                        <th class="px-3 py-2 border-r border-rose-200 bg-white">Nama Barang</th>
                        <th class="px-3 py-2 border-r border-rose-200 bg-white text-center">Qty</th>
                        <th class="px-3 py-2 border-r border-rose-200 bg-white text-center">Satuan</th>

                        <th class="px-3 py-2 border-r border-rose-200 text-right">Harga Satuan</th>
                        <th class="px-3 py-2 border-r border-rose-200 text-right">Diskon 1</th>
                        <th class="px-3 py-2 border-r border-rose-200 text-right">Diskon 2</th>
                        <th class="px-3 py-2 border-r border-rose-200 text-right">Total Diskon</th>
                        <th class="px-3 py-2 border-r border-rose-200 text-right">Netto</th>
                        <th class="px-3 py-2 border-r border-rose-200 text-right">PPN</th>
                        <th class="px-3 py-2 border-r border-rose-200 text-right bg-rose-100 text-rose-900 font-bold">
                            Total Grand</th>

                        <th class="px-3 py-2 border-r border-rose-200">Salesman</th>
                        <th class="px-3 py-2 border-r border-rose-200">Supplier</th>
                        <th class="px-3 py-2 border-r border-rose-200">Divisi</th>
                        <th class="px-3 py-2 border-r border-rose-200">Alasan Retur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rose-50">
                    @forelse($data as $item)
                    <tr class="hover:bg-rose-50/50 transition-colors">
                        <td
                            class="px-3 py-1.5 border-r border-rose-50 text-rose-700 font-bold sticky left-0 bg-white z-20">
                            {{ $item->cabang }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 font-mono">{{ $item->no_retur }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-center">
                            <span
                                class="px-1.5 py-0.5 rounded-sm bg-rose-50 text-rose-600 text-[9px] border border-rose-100">{{ $item->status ?? 'Retur' }}</span>
                        </td>
                        <td class="px-3 py-1.5 border-r border-rose-50">{{ date('d/m/Y', strtotime($item->tgl_retur)) }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-slate-500">{{ $item->no_inv }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 font-mono">{{ $item->kode_pelanggan }}</td>
                        <td
                            class="px-3 py-1.5 border-r border-rose-50 font-medium truncate max-w-[200px] sticky left-[60px] bg-white z-10">
                            {{ $item->nama_pelanggan }}</td>

                        <td class="px-3 py-1.5 border-r border-rose-50 font-mono text-slate-500">{{ $item->kode_item }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-rose-50 truncate max-w-[250px]">{{ $item->nama_item }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-center font-bold">{{ $item->qty }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-center">{{ $item->satuan_retur }}</td>

                        <td class="px-3 py-1.5 border-r border-rose-50 text-right">
                            {{ number_format($item->nilai, 0, ',', '.') }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-right">
                            {{ number_format($item->diskon_1, 1) }}%</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-right">
                            {{ number_format($item->diskon_2, 1) }}%</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-right">
                            {{ number_format($item->total_diskon, 0, ',', '.') }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-right">
                            {{ number_format($item->nilai_retur_net, 0, ',', '.') }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-right">
                            {{ number_format($item->ppn_value, 0, ',', '.') }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-right font-bold text-red-600 bg-rose-50/30">
                            {{ number_format($item->total_grand, 0, ',', '.') }}
                        </td>

                        <td class="px-3 py-1.5 border-r border-rose-50">{{ $item->sales_name }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-purple-600 truncate max-w-[100px]">
                            {{ $item->supplier }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50">{{ $item->divisi }}</td>
                        <td class="px-3 py-1.5 border-r border-rose-50 text-slate-400 text-[9px]">Bad Stock / Expired
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="21" class="px-6 py-12 text-center text-slate-400">Tidak ada data retur.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-2 border-t bg-rose-50">
            {{ $data->links() }}
        </div>
    </div>
</div>