<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Tagihan (Awal)</p>
                <h3 class="text-xl font-bold text-gray-800">Rp
                    {{ number_format($summary->total_tagihan ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 bg-gray-100 rounded-full text-gray-600"><i class="fas fa-file-invoice text-xl"></i></div>
        </div>

        <div
            class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-5 text-white shadow-lg flex justify-between items-center">
            <div>
                <p class="text-orange-100 text-xs font-bold uppercase">Sisa Piutang (Belum Bayar)</p>
                <h3 class="text-2xl font-bold">Rp {{ number_format($summary->sisa_piutang ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 bg-white/20 rounded-full text-white"><i class="fas fa-exclamation-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative w-full md:w-64">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Invoice / Pelanggan..."
                class="pl-10 pr-4 py-2 w-full border-gray-200 rounded-lg text-sm focus:ring-orange-500">
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
        </div>

        <select wire:model.live="filterCabang" class="w-full md:w-40 text-sm border-gray-200 rounded-lg">
            <option value="">Semua Cabang</option>
            @foreach($optCabang as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
        </select>

        <select wire:model.live="filterSales" class="w-full md:w-40 text-sm border-gray-200 rounded-lg">
            <option value="">Semua Sales</option>
            @foreach($optSales as $opt) <option value="{{ $opt }}">{{ $opt }}</option> @endforeach
        </select>

        <select wire:model.live="status" class="w-full md:w-40 text-sm border-gray-200 rounded-lg">
            <option value="all">Semua Status</option>
            <option value="belum">Belum Lunas</option>
            <option value="lunas">Lunas</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[70vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="text-gray-500 uppercase bg-gray-50 sticky top-0 z-20 shadow-sm font-bold">
                    <tr>
                        <th class="px-4 py-3 border-b">Cabang</th>
                        <th class="px-4 py-3 border-b">Invoice / Tgl</th>
                        <th class="px-4 py-3 border-b">Pelanggan</th>
                        <th class="px-4 py-3 border-b">Sales</th>
                        <th class="px-4 py-3 border-b text-right">Nilai Awal</th>
                        <th class="px-4 py-3 border-b text-right bg-orange-50">Sisa Piutang</th>
                        <th class="px-4 py-3 border-b text-center">Umur</th>
                        <th class="px-4 py-3 border-b text-center">Jatuh Tempo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($ar as $item)
                    <tr class="hover:bg-orange-50 transition-colors">
                        <td class="px-4 py-3 border-r font-medium text-orange-600">{{ $item->cabang }}</td>
                        <td class="px-4 py-3 border-r">
                            <div class="font-bold text-gray-800">{{ $item->no_penjualan }}</div>
                            <div class="text-[10px] text-gray-400">
                                {{ $item->tgl_penjualan ? date('d/m/Y', strtotime($item->tgl_penjualan)) : '-' }}</div>
                        </td>
                        <td class="px-4 py-3 border-r font-medium">{{ $item->pelanggan_name }}</td>
                        <td class="px-4 py-3 border-r">{{ $item->sales_name }}</td>
                        <td class="px-4 py-3 border-r text-right text-gray-500">
                            {{ number_format((float)$item->total_nilai, 0, ',', '.') }}</td>
                        <td
                            class="px-4 py-3 border-r text-right font-bold {{ (float)$item->nilai > 0 ? 'text-red-600 bg-red-50' : 'text-green-600' }}">
                            {{ number_format((float)$item->nilai, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 border-r text-center">
                            <span
                                class="px-2 py-1 rounded-full text-[10px] font-bold {{ (int)$item->umur_piutang > 30 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $item->umur_piutang }} Hari
                            </span>
                        </td>
                        <td
                            class="px-4 py-3 border-r text-center {{ $item->jatuh_tempo && now()->gt($item->jatuh_tempo) ? 'text-red-500 font-bold' : '' }}">
                            {{ $item->jatuh_tempo ? date('d/m/Y', strtotime($item->jatuh_tempo)) : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">Data Tidak Ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $ar->links() }}</div>
    </div>
</div>