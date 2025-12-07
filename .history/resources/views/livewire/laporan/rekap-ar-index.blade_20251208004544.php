<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
            class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-orange-100 text-xs font-bold uppercase tracking-wider">Sisa Piutang (Outstanding)</p>
                <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($summary->sisa_piutang ?? 0, 0, ',', '.') }}
                </h3>
            </div>
            <i class="fas fa-exclamation-circle absolute right-4 bottom-4 text-white/20 text-5xl"></i>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Nilai Faktur</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp
                    {{ number_format($summary->total_awal ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="p-3 bg-gray-100 rounded-full text-gray-600">
                <i class="fas fa-file-invoice-dollar text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-orange-500">
                    <option value="">Semua</option>
                    @foreach($optCabang as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>

            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sales</label>
                <select wire:model.live="filterSales"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-orange-500">
                    <option value="">Semua</option>
                    @foreach($optSales as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>

            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Status Bayar</label>
                <select wire:model.live="status"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-orange-500">
                    <option value="all">Semua</option>
                    <option value="belum">Belum Lunas</option>
                    <option value="lunas">Lunas</option>
                </select>
            </div>

            <div class="md:col-span-2 relative">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Pencarian</label>
                <div class="relative">
                    <input wire:model.live.debounce.500ms="search" type="text" placeholder="No Invoice, Pelanggan..."
                        class="w-full pl-10 text-sm border-gray-200 rounded-lg focus:ring-orange-500">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[70vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="text-gray-500 uppercase bg-gray-50 sticky top-0 z-20 shadow-sm font-bold">
                    <tr>
                        <th class="px-4 py-3 border-b border-r min-w-[80px]">Cabang</th>
                        <th class="px-4 py-3 border-b border-r min-w-[120px]">Invoice</th>
                        <th class="px-4 py-3 border-b border-r min-w-[150px]">Pelanggan</th>
                        <th class="px-4 py-3 border-b border-r">Sales</th>
                        <th class="px-4 py-3 border-b border-r text-center">Umur</th>
                        <th class="px-4 py-3 border-b border-r text-center">Jatuh Tempo</th>
                        <th class="px-4 py-3 border-b border-r text-right">Nilai Awal</th>
                        <th class="px-4 py-3 border-b border-r text-right bg-orange-50 text-orange-900 font-bold">Sisa
                            Piutang</th>
                        <th class="px-4 py-3 border-b border-r">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($ar as $item)
                    <tr class="hover:bg-orange-50 transition-colors">
                        <td class="px-4 py-2 border-r font-medium text-orange-600">{{ $item->cabang }}</td>
                        <td class="px-4 py-2 border-r font-mono text-gray-800">
                            {{ $item->no_penjualan }}
                            <div class="text-[10px] text-gray-400">
                                {{ $item->tgl_penjualan ? date('d/m/Y', strtotime($item->tgl_penjualan)) : '-' }}</div>
                        </td>
                        <td class="px-4 py-2 border-r font-medium truncate max-w-[150px]"
                            title="{{ $item->pelanggan_name }}">{{ $item->pelanggan_name }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->sales_name }}</td>
                        <td class="px-4 py-2 border-r text-center">
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ (int)$item->umur_piutang > 30 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $item->umur_piutang }} Hari
                            </span>
                        </td>
                        <td
                            class="px-4 py-2 border-r text-center {{ $item->jatuh_tempo && now()->gt($item->jatuh_tempo) ? 'text-red-600 font-bold' : '' }}">
                            {{ $item->jatuh_tempo ? date('d/m/Y', strtotime($item->jatuh_tempo)) : '-' }}
                        </td>
                        <td class="px-4 py-2 border-r text-right text-gray-500">
                            {{ number_format((float)$item->total_nilai, 0, ',', '.') }}</td>
                        <td
                            class="px-4 py-2 border-r text-right font-bold {{ (float)$item->nilai > 0 ? 'text-orange-600' : 'text-green-600' }}">
                            {{ number_format((float)$item->nilai, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 border-r">
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $item->status == 'Lunas' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-orange-100 text-orange-700 border-orange-200' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">Tidak ada data piutang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $ar->links() }}</div>
    </div>
</div>