<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
            class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Total Uang Masuk</p>
                <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($summary->total_uang_masuk ?? 0, 0, ',', '.') }}
                </h3>
            </div>
            <i class="fas fa-wallet absolute right-4 bottom-4 text-white/20 text-5xl"></i>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Transaksi Lunas</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($summary->total_trx ?? 0) }}</h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-full text-emerald-600">
                <i class="fas fa-check-double text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-emerald-500">
            </div>
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-emerald-500">
            </div>

            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-emerald-500">
                    <option value="">Semua</option>
                    @foreach($optCabang as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Penagih (Collector)</label>
                <select wire:model.live="filterPenagih"
                    class="w-full text-sm border-gray-200 rounded-lg focus:ring-emerald-500">
                    <option value="">Semua</option>
                    @foreach($optPenagih as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>

            <div class="md:col-span-1 relative">
                <input wire:model.live.debounce.500ms="search" type="text" placeholder="No Bukti, Invoice, Pelanggan..."
                    class="w-full pl-10 text-sm border-gray-200 rounded-lg focus:ring-emerald-500">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[70vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="text-gray-500 uppercase bg-gray-50 sticky top-0 z-20 shadow-sm font-bold">
                    <tr>
                        <th class="px-4 py-3 border-b border-r min-w-[100px]">Cabang</th>
                        <th class="px-4 py-3 border-b border-r min-w-[120px]">No Bukti (Receive)</th>
                        <th class="px-4 py-3 border-b border-r min-w-[90px]">Tanggal</th>
                        <th class="px-4 py-3 border-b border-r min-w-[120px]">Penagih</th>
                        <th class="px-4 py-3 border-b border-r min-w-[120px]">Invoice</th>
                        <th class="px-4 py-3 border-b border-r min-w-[150px]">Pelanggan</th>
                        <th class="px-4 py-3 border-b border-r text-right bg-emerald-50 text-emerald-900">Nominal Bayar
                        </th>
                        <th class="px-4 py-3 border-b border-r">Sales</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($collection as $item)
                    <tr class="hover:bg-emerald-50 transition-colors">
                        <td class="px-4 py-3 font-bold text-indigo-600 text-[10px] border-r">{{ $item->cabang }}</td>
                        <td class="px-4 py-3 font-bold text-gray-800 border-r">{{ $item->receive_no }}</td>
                        <td class="px-4 py-3 text-gray-500 border-r">
                            {{ $item->tanggal ? date('d/m/Y', strtotime($item->tanggal)) : '-' }}</td>
                        <td class="px-4 py-3 border-r">{{ $item->penagih }}</td>
                        <td class="px-4 py-3 font-mono text-gray-600 border-r">{{ $item->invoice_no }}</td>
                        <td class="px-4 py-3 font-medium border-r truncate max-w-[150px]"
                            title="{{ $item->outlet_name }}">{{ $item->outlet_name }}</td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-700 bg-emerald-50/50 border-r">
                            Rp {{ number_format((float)$item->receive_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $item->sales_name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">Tidak ada data pembayaran sesuai
                            filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $collection->links() }}</div>
    </div>
</div>