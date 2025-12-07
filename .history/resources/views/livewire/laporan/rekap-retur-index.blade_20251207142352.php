<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
            class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-red-100 text-xs font-bold uppercase tracking-wider">Total Nilai Retur</p>
                <h3 class="text-2xl font-bold mt-1">Rp
                    {{ number_format($summary->total_nilai_retur ?? 0, 0, ',', '.') }}</h3>
            </div>
            <i class="fas fa-undo-alt absolute right-4 bottom-4 text-white/20 text-5xl"></i>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Transaksi Retur</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($summary->total_trx ?? 0) }}</h3>
            </div>
            <div class="p-3 bg-red-50 rounded-full text-red-600">
                <i class="fas fa-box-open text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Dari</label>
                <input type="date" wire:model.live="startDate" class="w-full text-sm border-gray-200 rounded-lg">
            </div>
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sampai</label>
                <input type="date" wire:model.live="endDate" class="w-full text-sm border-gray-200 rounded-lg">
            </div>

            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Cabang</label>
                <select wire:model.live="filterCabang" class="w-full text-sm border-gray-200 rounded-lg">
                    <option value="">Semua</option>
                    @foreach($optCabang as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sales</label>
                <select wire:model.live="filterSales" class="w-full text-sm border-gray-200 rounded-lg">
                    <option value="">Semua</option>
                    @foreach($optSales as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Pencarian</label>
                <div class="relative">
                    <input wire:model.live.debounce.500ms="search" type="text"
                        placeholder="No Retur, Pelanggan, Barang..."
                        class="w-full pl-10 text-sm border-gray-200 rounded-lg">
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
                        <th class="px-4 py-3 border-b">No Retur</th>
                        <th class="px-4 py-3 border-b">Tanggal</th>
                        <th class="px-4 py-3 border-b">Pelanggan</th>
                        <th class="px-4 py-3 border-b">Barang</th>
                        <th class="px-4 py-3 border-b text-center">Qty</th>
                        <th class="px-4 py-3 border-b text-right">Nilai</th>
                        <th class="px-4 py-3 border-b">Status</th>
                        <th class="px-4 py-3 border-b">Sales</th>
                        <th class="px-4 py-3 border-b">Cabang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($retur as $item)
                    <tr class="hover:bg-red-50 transition-colors">
                        <td class="px-4 py-3 font-bold text-gray-700">{{ $item->no_retur }}</td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $item->tgl_retur ? date('d/m/Y', strtotime($item->tgl_retur)) : '-' }}</td>
                        <td class="px-4 py-3 font-medium">{{ $item->nama_pelanggan }}</td>
                        <td class="px-4 py-3 text-gray-600 truncate max-w-[200px]" title="{{ $item->nama_item }}">
                            {{ $item->nama_item }}</td>
                        <td class="px-4 py-3 text-center font-bold">{{ $item->qty }}</td>
                        <td class="px-4 py-3 text-right font-mono text-red-600">
                            {{ number_format((float)$item->total_grand, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">{{ $item->status }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $item->sales_name }}</td>
                        <td class="px-4 py-3 text-indigo-600 font-bold text-[10px]">{{ $item->cabang }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">Tidak ada data retur sesuai filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $retur->links() }}</div>
    </div>
</div>