<div class="space-y-6">

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-auto">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
            <input type="date" wire:model.live="startDate"
                class="w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="w-full md:w-auto">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
            <input type="date" wire:model.live="endDate"
                class="w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="w-full md:flex-1">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cari Data</label>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="No Invoice, Pelanggan, Sales...">
            </div>
        </div>

        <button
            class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-lg transition shadow-sm">
            <i class="fas fa-file-excel mr-2"></i> Export
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-indigo-600 rounded-xl p-6 text-white shadow-lg shadow-indigo-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-indigo-100 text-sm font-medium">Total Omzet (Periode Ini)</p>
                    <h3 class="text-3xl font-bold mt-1">Rp {{ number_format((float)$totalOmzet, 0, ',', '.') }}</h3>
                </div>
                <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                    <i class="fas fa-chart-line fa-lg text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Transaksi</p>
                    <h3 class="text-3xl font-bold mt-1 text-gray-800">{{ number_format($totalTransaksi) }}</h3>
                    <p class="text-xs text-gray-400 mt-1">Invoice diterbitkan</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <i class="fas fa-receipt fa-lg text-indigo-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">No Invoice</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Sales</th>
                        <th class="px-6 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($penjualans as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($item->tgl_penjualan)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-indigo-600">
                            {{ $item->trans_no }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $item->nama_pelanggan }}</div>
                            <div class="text-xs text-gray-400">{{ $item->kode_pelanggan }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $item->sales_name }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">
                            Rp {{ number_format((float)$item->total_grand, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <p>Tidak ada data penjualan pada periode ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $penjualans->links() }}
        </div>
    </div>
</div>