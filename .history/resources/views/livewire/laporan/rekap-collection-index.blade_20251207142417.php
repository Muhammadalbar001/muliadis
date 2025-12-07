<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
            class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-green-100 text-xs font-bold uppercase tracking-wider">Total Uang Masuk (Collection)</p>
                <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($summary->total_uang_masuk ?? 0, 0, ',', '.') }}
                </h3>
            </div>
            <i class="fas fa-wallet absolute right-4 bottom-4 text-white/20 text-5xl"></i>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Total Nota Terbayar</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($summary->total_trx ?? 0) }}</h3>
            </div>
            <div class="p-3 bg-green-50 rounded-full text-green-600">
                <i class="fas fa-check-double text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate" class="w-full text-sm border-gray-200 rounded-lg">
            </div>
            <div class="md:col-span-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
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
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Penagih (Collector)</label>
                <select wire:model.live="filterPenagih" class="w-full text-sm border-gray-200 rounded-lg">
                    <option value="">Semua</option>
                    @foreach($optPenagih as $o) <option value="{{ $o }}">{{ $o }}</option> @endforeach
                </select>
            </div>

            <div class="md:col-span-1 relative">
                <input wire:model.live.debounce.500ms="search" type="text" placeholder="No Bukti / Invoice..."
                    class="w-full pl-10 text-sm border-gray-200 rounded-lg">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[70vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="text-gray-500 uppercase bg-gray-50 sticky top-0 z-20 shadow-sm font-bold">
                    <tr>
                        <th class="px-4 py-3 border-b">No Bukti (Receive)</th>
                        <th class="px-4 py-3 border-b">Tanggal</th>
                        <th class="px-4 py-3 border-b">Penagih</th>
                        <th class="px-4 py-3 border-b">Invoice Dibayar</th>
                        <th class="px-4 py-3 border-b">Pelanggan</th>
                        <th class="px-4 py-3 border-b text-right">Nominal Bayar</th>
                        <th class="px-4 py-3 border-b">Cabang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($collection as $item)
                    <tr class="hover:bg-green-50 transition-colors">
                        <td class="px-4 py-3 font-bold text-gray-800">{{ $item->receive_no }}</td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $item->tanggal ? date('d/m/Y', strtotime($item->tanggal)) : '-' }}</td>
                        <td class="px-4 py-3">{{ $item->penagih }}</td>
                        <td class="px-4 py-3 font-mono text-gray-600">{{ $item->invoice_no }}</td>
                        <td class="px-4 py-3 font-medium">{{ $item->outlet_name }}</td>
                        <td class="px-4 py-3 text-right font-bold text-green-700 bg-green-50/50">
                            Rp {{ number_format((float)$item->receive_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-indigo-600 font-bold text-[10px]">{{ $item->cabang }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Tidak ada data pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $collection->links() }}</div>
    </div>
</div>