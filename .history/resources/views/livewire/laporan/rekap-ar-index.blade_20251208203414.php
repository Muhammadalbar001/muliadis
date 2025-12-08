<div class="space-y-4 font-jakarta">

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-orange-100">
        <div class="flex flex-col md:flex-row gap-3 items-end">
            <div class="w-full md:flex-1 relative">
                <i class="fas fa-search absolute left-3 top-2.5 text-orange-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-9 w-full border-orange-100 rounded-xl text-xs focus:ring-orange-500"
                    placeholder="Cari Faktur / Toko...">
            </div>
            <select wire:model.live="filterUmur"
                class="border-orange-100 rounded-xl text-xs w-32 focus:ring-orange-500">
                <option value="">Semua Umur</option>
                <option value="lancar">✅ Lancar</option>
                <option value="macet">⚠️ Macet</option>
            </select>
            <div class="w-40 relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full bg-white border border-orange-100 text-slate-600 px-3 py-2 rounded-xl text-xs flex justify-between items-center">
                    <span
                        class="truncate">{{ count($filterCabang) ? count($filterCabang).' Dipilih' : 'Semua Cabang' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div x-show="open"
                    class="absolute z-50 mt-1 w-full bg-white border border-orange-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                    @foreach($optCabang as $c)
                    <label class="flex items-center px-2 py-1.5 hover:bg-orange-50 rounded cursor-pointer">
                        <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                            class="rounded border-orange-300 text-orange-600 h-3 w-3 mr-2"> <span
                            class="text-[10px]">{{ $c }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <button wire:click="resetFilter" class="px-3 py-2 bg-slate-100 rounded-xl"><i
                    class="fas fa-undo text-slate-500"></i></button>
            <button class="px-3 py-2 bg-orange-500 text-white rounded-xl shadow-orange-200"><i
                    class="fas fa-file-export"></i></button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-orange-100 flex flex-col h-[75vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                <thead
                    class="bg-orange-50 text-orange-800 font-bold border-b border-orange-200 uppercase sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-2 border-r border-orange-200">Cabang</th>
                        <th class="px-3 py-2 border-r border-orange-200">No Penjualan</th>
                        <th class="px-3 py-2 border-r border-orange-200">Tgl Faktur</th>
                        <th class="px-3 py-2 border-r border-orange-200">Jatuh Tempo</th>
                        <th class="px-3 py-2 border-r border-orange-200">Pelanggan</th>
                        <th class="px-3 py-2 border-r border-orange-200">Salesman</th>

                        <th class="px-3 py-2 border-r border-orange-200 text-center">Umur (Hari)</th>
                        <th class="px-3 py-2 border-r border-orange-200 text-right">Nilai Faktur</th>
                        <th class="px-3 py-2 border-r border-orange-200 text-right bg-orange-100">Sisa Tagihan</th>
                        <th class="px-3 py-2 border-r border-orange-200 text-center">Status Antar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-orange-50">
                    @forelse($data as $item)
                    <tr class="hover:bg-orange-50/50 transition-colors">
                        <td class="px-3 py-1.5 border-r border-orange-50 text-orange-700 font-bold">{{ $item->cabang }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-orange-50 font-mono">{{ $item->no_penjualan }}</td>
                        <td class="px-3 py-1.5 border-r border-orange-50">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                        <td
                            class="px-3 py-1.5 border-r border-orange-50 {{ \Carbon\Carbon::parse($item->jatuh_tempo)->isPast() ? 'text-red-500 font-bold' : '' }}">
                            {{ date('d/m/Y', strtotime($item->jatuh_tempo)) }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-orange-50 truncate max-w-[150px]">
                            {{ $item->pelanggan_name }}</td>
                        <td class="px-3 py-1.5 border-r border-orange-50">{{ $item->sales_name }}</td>

                        <td class="px-3 py-1.5 border-r border-orange-50 text-center font-bold">
                            <span
                                class="px-1.5 py-0.5 rounded text-white {{ $item->umur_piutang > 30 ? 'bg-red-500' : 'bg-emerald-500' }}">
                                {{ $item->umur_piutang }}
                            </span>
                        </td>
                        <td class="px-3 py-1.5 border-r border-orange-50 text-right">
                            {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                        <td
                            class="px-3 py-1.5 border-r border-orange-50 text-right font-bold text-slate-800 bg-orange-50/30">
                            {{ number_format($item->nilai, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-orange-50 text-center text-[9px]">
                            {{ $item->status_antar }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-10 text-center text-slate-400">Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-2 border-t bg-orange-50">{{ $data->links() }}</div>
    </div>
</div>